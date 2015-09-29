<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Commandes (Orders) Plugin
 *
 * @author   Radja Lomas
 * @package  custom\Modules\Commandes\Plugins
 */
class Plugin_Commandes extends Plugin
{

	public $version = '1.0.0';
	public $name = array(
		'en' => 'Orders',
		'fr' => 'Commandes',
	);
	public $description = array(
		'en' => 'Display information about orders.',
		'fr' => 'Affiche les infos des commandes.',
	);

	/**
	 * Returns a PluginDoc array that PyroCMS uses 
	 * to build the reference in the admin panel
	 *
	 * All options are listed here but refer 
	 * to the Blog plugin for a larger example
	 *
	 *
	 * @return array
	 */
	public function _self_doc()
	{
		$info = array(
			'count' => array(// the name of the method you are documenting
				'description' => array(// a single sentence to explain the purpose of this method
					'en' => 'Display the number of orders for the specified menu.',
					'fr' => 'Affiche le nombre de commandes pour une carte.'
				),
				'single' => true,// will it work as a single tag?
				'double' => false,// how about as a double tag?
				'variables' => '',// list all variables available inside the double tag. Separate them|like|this
				'attributes' => array(
					'entry_id' => array(// this is the order-dir="asc" attribute
						'type' => 'number|text',// Can be: slug, number, flag, text, array, any.
						'flags' => '',
						'default' => '0',// attribute defaults to this if no value is given
						'required' => true,// is this attribute required?
					),
					'entry_key' => array(
						'type' => 'text|lang',
						'flags' => '',
						'default' => '',
						'required' => true,
					),
					'module' => array(
						'type' => 'slug',
						'flags' => '',
						'default' => 'current module',
						'required' => false,
					),
				),
			),// end first method
			'count_string' => array(// the name of the method you are documenting
				'description' => array(// a single sentence to explain the purpose of this method
					'en' => 'Display the order count as a translated string.',
					'fr' => 'Affiche le nombre de commandes en tant que texte traduit.'
				),
				'single' => true,// will it work as a single tag?
				'double' => false,// how about as a double tag?
				'variables' => '',// list all variables available inside the double tag. Separate them|like|this
				'attributes' => array(
					'entry_id' => array(// this is the order-dir="asc" attribute
						'type' => 'number|text',// Can be: slug, number, flag, text, array, any.
						'flags' => '',
						'default' => '0',// attribute defaults to this if no value is given
						'required' => true,// is this attribute required?
					),
					'entry_key' => array(
						'type' => 'text|lang',
						'flags' => '',
						'default' => '',
						'required' => true,
					),
					'module' => array(
						'type' => 'slug',
						'flags' => '',
						'default' => 'current module',
						'required' => false,
					),
				),
			),// end second method
			'display' => array(// the name of the method you are documenting
				'description' => array(// a single sentence to explain the purpose of this method
					'en' => 'Output the orders html for the specified item.',
					'fr' => 'Affiche le code HTML des commandes pour le menu.'
				),
				'single' => true,// will it work as a single tag?
				'double' => false,// how about as a double tag?
				'variables' => '',// list all variables available inside the double tag. Separate them|like|this
				'attributes' => array(
					'entry_id' => array(// this is the order-dir="asc" attribute
						'type' => 'number|text',// Can be: slug, number, flag, text, array, any.
						'flags' => '',
						'default' => '0',// attribute defaults to this if no value is given
						'required' => true,// is this attribute required?
					),
					'entry_key' => array(
						'type' => 'text|lang',
						'flags' => '',
						'default' => '',
						'required' => true,
					),
					'module' => array(
						'type' => 'slug',
						'flags' => '',
						'default' => 'current module',
						'required' => false,
					),
				),
			),// end third method
		);
	
		return $info;
	}

	/**
	 * Count
	 *
	 * Usage:
	 * {{ commandes:count entry_id=page:id entry_key="pages:page" [module="pages"] }}
	 *
	 * @param array
	 * @return array
	 */
	public function count()
	{
		$entry_id 	= $this->attribute('entry_id', $this->attribute('item_id'));
		$entry_key 	= $this->attribute('entry_key');
		$module  	= $this->attribute('module', $this->module);
		
		$this->load->library('commandes/commandes', 
			array(
				'entry_id' => $entry_id, 
				'singular' => $entry_key, 
				'module' => $module
				)
			);
		
		return $this->commandes->count();
	}

	/**
	 * Count and return a translated string
	 *
	 * Usage:
	 * {{ commandes:count_string entry_id=page:id entry_key="pages:page" [module="pages"] }}
	 *
	 * @param array
	 * @return array
	 */
	public function count_string()
	{
		// Are we passing a number directly?
		if ($commande_count = $this->attribute('count')) {
			$this->load->library('commandes/commandes');
			return $this->commandes->count_string($commande_count);
		}

		$entry_id 		= $this->attribute('entry_id', $this->attribute('item_id'));
		$entry_key 		= $this->attribute('entry_key');
		$entry_plural 	= $this->attribute('entry_plural');
		$module  		= $this->attribute('module', $this->module);
		
		$this->load->library('commandes/commandes', 
			array(
				'entry_id' => $entry_id, 
				'singular' => $entry_key,
				'plural' => $entry_plural,
				'module' => $module
				)
			);
		
		return $this->commandes->count_string();
	}

	/**
	 * Display
	 *
	 * Usage:
	 * {{ commandes:display entry_id=page:id entry_key="pages:page" [module="pages"] }}
	 *
	 * @param array
	 * @return array
	 */
	public function display()
	{
		$entry_id 	= $this->attribute('entry_id', $this->attribute('item_id'));
		$entry_key 	= $this->attribute('entry_key');
		$module  	= $this->attribute('module', $this->module);
		
		$this->load->library('commandes/commandes', 
			array(
				'entry_id' => $entry_id, 
				'singular' => $entry_key, 
				'module' => $module
				)
			);
		
		return $this->commandes->display();
	}
}

/* End of file plugin.php */
