<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Commandes (Orders) library
 *
 * @author		Radja Lomas
 * @package		custom\Modules\Commandes\Libraries
 */

class Commandes
{
	/**
	 * The name of the module in use
	 * 
	 * @var	string
	 */
	protected $module;

	/**
	 * Singular language key
	 * 
	 * @var	string
	 */
	protected $singular;

	/**
	 * Plural language key
	 * 
	 * @var	string
	 */
	protected $plural;

	/**
	 * Entry for this, be it an auto increment id or string
	 * 
	 * @var	string|int
	 */
	protected $entry_id;

	/**
	 * Title of the entry
	 * 
	 * @var	string
	 */
	protected $entry_title;

	/**
	 * What is the URL of this entry?
	 * 
	 * @var	string
	 */
	protected $entry_uri;

	/**
	 * Encrypted hash containing title, singular and plural keys
	 * 
	 * @var	bool
	 */
	protected $entry_hash;

	/**
	 * Order Count
	 *
	 * Setting to 0 by default.
	 *
	 * @var 	int
	 */
	protected $count = 0;

	/**
	 * Function to display a order
	 *
	 * Reference is a actually an object reference, a.k.a. categorization of the commande table rows.
	 * The reference id is a further categorization on this. (For example, for example for
	 *
	 * @param	string	$module		The name of the module in use
	 * @param	string	$singular	Singular language key
	 * @param	string	$plural		Plural language key
	 * @param	string|int	$entry_id	Entry for this, be it an auto increment id or string, or null
	 */
	public function __construct($params)
	{
		ci()->load->model('commandes/commande_m');
		ci()->lang->load('commandes/commandes');

	  // This shouldnt be required if static loading was possible, but its not in CI
		if (is_array($params))
		{
			// Required
			$this->module = $params['module'];
			$this->singular = $params['singular'];
			$this->plural = $params['plural'];

			// Overridable
			$this->entry_uri = isset($params['uri']) ? $params['uri'] : uri_string();

			// Optional
			isset($params['entry_id']) and $this->entry_id = $params['entry_id'];
			isset($params['entry_title']) and $this->entry_title = $params['entry_title'];
		}
	}
	
	/**
	 * Display orders
	 *
	 * @return	string	Returns the HTML for any existing order
	 */
	public function display()
	{
		// Fetch orders, then process them
		$commandes = $this->process(ci()->commande_m->get_by_entry($this->module, $this->singular, $this->entry_id));
		
		// Return the awesome order view
		return $this->load_view('display', compact(array('commandes')));
	}
	
	/**
	 * Display form
	 *
	 * @return	string	Returns the HTML for the order submission form
	 */
	public function form()
	{
		// Return the awesome order view
		return $this->load_view('form', array(
			'module'		=>	$this->module,
			'entry_hash'	=>	$this->encode_entry(),
			'commande'		=>  ci()->session->flashdata('commande'),
		));
	}

	/**
	 * Count orders
	 *
	 * @return	int	Return the number of orders for this entry item
	 */
	public function count()
	{
		return (int) ci()->db->where(array(
			'module'	=> $this->module,
			'entry_key'	=> $this->singular,
			'entry_id'	=> $this->entry_id,
			'is_active'	=> true,
		))->count_all_results('commandes');
	}

	/**
	 * Count commandes as string
	 *
	 * @return	string 	Language string with the total in it
	 */
	public function count_string($commande_count = null)
	{
		$total = ($commande_count) ? $commande_count : $this->count;

		switch ($total)
		{
			case 0:
				$line = 'none';
				break;
			case 1:
				$line = 'singular';
				break;
			default:
				$line = 'plural';
		}

		return sprintf(lang('commandes:counter_'.$line.'_label'), $total);
	}

	/**
	 * Function to process the items in an X amount of commandes
	 *
	 * @param array $commandes The commandes to process
	 * @return array
	 */
	public function process($commandes)
	{
		// Remember which modules have been loaded
		static $modules = array();

		foreach ($commandes as &$commande)
		{
			// Override specified website if they are a user
			if ($commande->user_id and Settings::get('enable_profiles'))
			{
				$commande->website = 'user/'.$commande->user_name;
			}

			// We only want to load a lang file once
			if ( ! isset($modules[$commande->module]))
			{
				if (ci()->module_m->exists($commande->module))
				{
					ci()->lang->load("{$commande->module}/{$commande->module}");

					$modules[$commande->module] = true;
				}
				// If module doesn't exist (for whatever reason) then sssh!
				else
				{
					$modules[$commande->module] = false;
				}
			}

			$commande->singular = lang($commande->entry_key) ? lang($commande->entry_key) : humanize($commande->entry_key);
			$commande->plural = lang($commande->entry_plural) ? lang($commande->entry_plural) : humanize($commande->entry_plural);

			// work out who passed the order
			if ($commande->user_id > 0)
			{
				$commande->user_name = anchor('admin/users/edit/'.$commande->user_id, $commande->user_name);
			}

			// Security: Escape any Lex tags
			foreach ($commande as $field => $value)
			{
				$commande->{$field} = escape_tags($value);
			}
		}
		
		return $commandes;
	}

	/**
	 * Load View
	 *
	 * @return	string	HTML of the orders and form
	 */
	protected function load_view($view, $data)
	{
		$ext = pathinfo($view, PATHINFO_EXTENSION) ? '' : '.php';
		
		if (file_exists(ci()->template->get_views_path().'modules/commandes/'.$view.$ext))
		{
			// look in the theme for overloaded views
			$path = ci()->template->get_views_path().'modules/commandes/';
		}
		else
		{
			// or look in the module
			list($path, $view) = Modules::find($view, 'commandes', 'views/');
		}
		
		// add this view location to the array
		ci()->load->set_view_path($path);
		ci()->load->vars($data);

		return ci()->load->_ci_load(array('_ci_view' => $view, '_ci_return' => true));
	}

	/**
	 * Encode Entry
	 *
	 * @return	string	Return a hash of entry details, so we can send it via a form safely.
	 */
	protected function encode_entry()
	{
		return ci()->encrypt->encode(serialize(array(
			'id'			=>	$this->entry_id,
			'title'			=> 	$this->entry_title,
			'uri'			=>	$this->entry_uri,
			'singular'		=>	$this->singular,
			'plural'		=>	$this->plural,
		)));
	}


}