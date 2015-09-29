<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Commandes controller (frontend)
 *
 * @package		custom\Modules\Commandes\Controllers
 * @author		Radja Lomas
 * @copyright   Copyright (c) 2012, PyroCMS LLC
 */
class Commandes extends Public_Controller
{
	/**
	 * An array containing the validation rules
	 * 
	 * @var array
	 */
	private $validation_rules = array(
		array(
			'field' => 'telephone',
			'label' => 'lang:commandes:telephone_label',
			'rules' => 'trim|required|min_length[10]'
		),
		array(
			'field' => 'commande',
			'label' => 'lang:commandes:commande_label',
			'rules' => 'trim|required'
		),
	);

	/**
	 * Constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		// Load the required classes
		$this->load->library('form_validation');
		$this->load->model('commande_m');
		$this->lang->load('commandes');
	}

	/**
	 * Create a new order
	 *
	 * @param type $module The module that has a commande-able model.
	 * @param int $id The id for the respective commande-able model of a module.
	 */
	public function create($module = null)
	{
		if ( ! $module or ! $this->input->post('entry')) 
		{
			show_404();
		}

		// Get information back from the entry hash
		// @HACK This should be part of the controllers lib, but controllers & libs cannot share a name
		$entry = unserialize($this->encrypt->decode($this->input->post('entry')));

		$commande = array(
			'module'            => $module,
			'entry_id'          => $entry['id'],
			'entry_title'       => $entry['title'],
			'entry_key'         => $entry['singular'],
			'entry_plural'      => $entry['plural'],
			'uri'               => $entry['uri'],
			'telephone'         => $this->input->post('telephone'),
			'company'           => $this->input->post('company'),
			'info_acces'        => $this->input->post('info_acces'),
			'heure_livraison'   => $this->input->post('heure_livraison'),
			'info_payment'      => $this->input->post('info_payment'),
			'adresse_livraison' => $this->input->post('adresse_livraison'),
			'commande'          => $this->input->post('commande'),
			'total'             => $this->input->post('total'),
			'message'           => $this->input->post('message'),
			'parsed'            => parse_markdown(htmlspecialchars($this->input->post('message'), null, false)),
			'is_active'         => (bool) ((isset($this->current_user->group) and $this->current_user->group == 'admin') or ! Settings::get('moderate_commandes')),
		);
               

		// Logged in? in which case, we already know their name and email
	/*	if ($this->current_user)
		{ */
			$commande['user_id'] = $this->current_user->id;
			$commande['user_name'] = $this->current_user->display_name;
			$commande['user_email'] = $this->current_user->email;
	 /* 	//	$commande['address'] = $this->current_user->address & $this->current_user->postcode & $this->current_user->ville;
                              
			if (isset($this->current_user->website))
			{
				$commande['website'] = $this->current_user->website;
			} 
		}
		else
		{
			$this->validation_rules[0]['rules'] .= '|required';
			$this->validation_rules[1]['rules'] .= '|required';
                       */ /*
			$commande['user_name'] = $this->input->post('name');
			$commande['user_email'] = $this->input->post('email');
			$commande['user_website'] = $this->input->post('website');
		} */

		// Set the validation rules
		$this->form_validation->set_rules($this->validation_rules);
	/*  $this->form_validation->set_rules('telephone', 'Téléphone', 'required|min_length[10]');
		$this->form_validation->set_rules('commande', 'La commande', 'required'); */
		// Validate the results
		if ($this->form_validation->run())
		{
			// Allow orders 
			$result = $this->_allow_commande();

			foreach ($commande as &$data)
			{
				// Remove {pyro} tags and html
				$data = escape_tags($data);
			}
                 //die(print_r($commande)) ;		

			// Save the order
			if ($commande_id = $this->commande_m->insert($commande))
			{
				// Approve the order straight away
				if (!$this->settings->moderate_commandes or (isset($this->current_user->group) and $this->current_user->group == 'admin'))
				{
					$this->session->set_flashdata('success', lang('commandes:add_success'));

					// Add an event so third-party devs can hook on
					Events::trigger('commande_approved', $commande);
				}

				// Do we need to approve the order?
				else
				{
					$this->session->set_flashdata('success', lang('commandes:add_approve'));
				}

				$commande['commande_id'] = $commande_id;

				// If markdown is allowed we will parse the body for the email
				if (Settings::get('commande_markdown'))
				{
					$commande['message'] = parse_markdown($commande['message']);
				}
                                
                                // adjust stock levels
                                $this->commande_m->change_stock_qty($commande['commande']);
                                
                                // add html order block
                                $commande['commande_html'] = $this->commande_m->order_html($this->commande_m->order_product_details($commande['commande'])) ;
				// Send the notification email
				$this->_send_email($commande, $entry);
                                
                                // redirect to thankyou page        
                                $uri = Settings::get('site_url') . '/merci';
                                redirect($uri);
			}

			// Failed to add the order
			else
			{
				$this->session->set_flashdata('error', lang('commandes:add_error'));

				$this->_repopulate_commande();
			}		
		}

		// The validation has failed
		else
		{
			$this->session->set_flashdata('error', validation_errors());

			$this->_repopulate_commande();
		}
                
		// If for some reason the post variable doesnt exist, just send to module main page
		$uri = ! empty($entry['uri']) ? $entry['uri'] : $module;

		// If this is default to pages then just send it home instead
		$uri === 'pages' and $uri = '/';

		redirect($uri);
	}
        
	/**
	 * Repopulate order
	 *
	 * There are a few places where we need to repopulate
	 * the order.
	 *
	 * @access 	private
	 * @return 	void
	 */
	private function _repopulate_commande()
	{
		// Loop through each rule
		foreach ($this->validation_rules as $rule)
		{
			if ($this->input->post($rule['field']) !== false)
			{
				$commande[$rule['field']] = escape_tags($this->input->post($rule['field']));
			}
		}
		$this->session->set_flashdata('commandes', $commande);
	}

	/**
	 * Method to check whether we want to allow the order or not
	 * 
	 * @return array
	 */
	private function _allow_commande()
	{
		// Dumb-check
		$this->load->library('user_agent');

		// Sneaky bot-check
		if ($this->agent->is_robot() or $this->input->post('d0ntf1llth1s1n'))
		{
			return array('status' => false, 'message' => 'You are probably a robot.');
		}

		// F**k knows, its probably fine...
		return array('status' => true);
	}

	/**
	 * Send an email
	 *
	 * @param array $commande The order data.
	 * @param array $entry The entry data.
	 * @return boolean 
	 */
	private function _send_email($commande, $entry)
	{
            
		$this->load->library('email') ;
		$this->load->library('user_agent') ;
        
		// Add in some extra details
		$commande['slug'] = 'commandes' ;
		$commande['sender_agent'] = $this->agent->browser().' '.$this->agent->version() ;
		$commande['sender_ip'] = $this->input->ip_address() ;
		$commande['sender_os'] = $this->agent->platform() ;
		$commande['redirect_url'] = anchor(ltrim($entry['uri'], '/').'#'.$commande['commande_id']) ;
		$commande['reply-to'] = $commande['user_email'] ;

		//trigger the event
		return (bool) Events::trigger('email', $commande );
	}
	/**
	 * Show orders for user
	 *
	 */
	public function account()
	{   
            if(is_logged_in()) {
                $user_id = $this->current_user->id ;

                // Get our orders count 
                $results = $this->db->select("COUNT(id)")
                                    ->from('commandes')
                                    ->where("user_id=". $user_id)
                                    ->get();           
                $count_arr[] = $results->row();    

                // Get the orders full details
                $results = $this->db->select("id, user_id, entry_title, is_active, adresse_livraison, commande")
                                    ->from('commandes')
                                    ->where("user_id=". $user_id)
                                    ->get();           
                $orders_arr[] = $results->result_array();             

                // rebuild produc details
                foreach ($orders_arr[0]as $order_key => $orderval) { 
            //  echo "<br>";
                    $commande_arr = $this->commande_m->order_product_details($orderval['commande'])  ; 
                    $orders_arr[0][$order_key]['order_arr'] = $commande_arr ;
                    $orders_arr[0][$order_key]['order_html'] = $this->commande_m->order_html($commande_arr)  ;
                }
            $this->template
                    ->title($this->module_details['name'])
              //      ->set_breadcrumb(lang('commande:carte_title'))
                    ->set_metadata('og:title', $this->module_details['name'], 'og')
           /*       ->set_metadata('og:type', 'carte', 'og')
                    ->set_metadata('og:url', current_url(), 'og')
                    ->set_metadata('og:description', $meta['description'], 'og')
                    ->set_metadata('description', $meta['description'])
                    ->set_metadata('keywords', $meta['keywords'])     
                    ->set_stream($this->stream->stream_slug, $this->stream->stream_namespace) */
                    ->set('commandes', $orders_arr[0])
                    // ->set('pagination', $posts['pagination'])
                    ->build('account');                          

             } 
            else  
            {
           $this->template
                    ->title($this->module_details['name'])
                    ->set_metadata('og:title', $this->module_details['name'], 'og')
                    ->set('commandes', "Vous n'etes pas connecté.")
                    ->build('account');                          
            }

        } 
       
}
