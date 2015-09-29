<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * @author 		Radja Lomas
 * @package 	custom\Modules\Commandes\Controllers
 */
class Admin extends Admin_Controller {

	/**
	 * Array that contains the validation rules
	 * @access private
	 * @var array
	 */
	private $validation_rules = array(
            
		array(
			'field' => 'commande',
			'label' => 'lang:commandes:name_label',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'adresse_livraison',
			'label' => 'lang:adresse_livraison_label',
			'rules' => 'trim|required'
		),
                array(
			'field' => 'telephone',
			'label' => 'lang:telephone_label',
			'rules' => 'trim|required|numeric'
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

		// Load the required libraries, models, etc
		$this->load->library('form_validation');
		$this->load->library('commandes');
		$this->load->helper('user');
		$this->load->model(array('commande_m'));
		$this->lang->load('commandes');

		// Set the validation rules
		$this->form_validation->set_rules($this->validation_rules);
	}

	/**
	 * Index
	 * 
	 * @return void
	 */
	public function index()
	{   
            // Only show is_active = 0 if we are moderating commandes
            $is_active = Settings::get('moderate_commandes') ;
            $where = '';
            if ($is_active == 0) 
            {
              $where = "(`is_active` = '1' OR `is_active` = '0')" ;              
            } else {
              //$where = "`is_active` = '0'" ;                              
                if($this->input->post('f_active') == 1)
                {
                   $where =   "`is_active` = '1'" ;                    

                } else {
                   $where =   "`is_active` = '0'" ;
                }
            }            
             //   die($where);            
            
            $content_title = $is_active ? lang('commandes:active_title') : lang('commandes:inactive_title') ;

            // keywords filter
            if (trim($this->input->post('f_keywords')))
            {
                $search = trim($this->input->post('f_keywords')) ;
                $where .= " AND (`entry_title` LIKE '%$search%' OR `adresse_livraison` LIKE '%$search%')" ;
            } 
            //die($where);
            
            // Create pagination links
            $total_rows = $this->commande_m->count_by($where);
            $pagination = create_pagination('admin/commandes/index', $total_rows);
            $result = $this->db->select()
                    ->from('commandes')
                    ->where($where)
                    ->limit($pagination['limit'], $pagination['offset'])
                    ->order_by('created_on', 'desc')
                    ->get();

            $commandes = $result->result();

            $this->input->is_ajax_request() && $this->template->set_layout(false);

            $module_list = $this->commande_m->get_slugs();

            $this->template
                    ->title($this->module_details['name'])
                    ->append_js('admin/filter.js')
                    ->set('module_list',		$module_list)
                    ->set('content_title',		$content_title)
                    ->set('commandes',		$this->commandes->process($commandes))
                    ->set('commandes_active',	$is_active)
                    ->set('pagination',		$pagination);

            $this->input->is_ajax_request() ? $this->template->build('admin/tables/commandes') : $this->template->build('admin/index');
	}

	/**
	 * Action method, called whenever the user submits the form
	 * 
	 * @return void
	 */
	public function action()
	{
		$action = strtolower($this->input->post('btnAction'));

		if ($action)
		{
			// Get the id('s)
			$id_array = $this->input->post('action_to');

			// Call the action we want to do
			if (method_exists($this, $action))
			{
				$this->{$action}($id_array);
			}
		}

		redirect('admin/commandes');
	}

	/**
	 * Edit an existing commande
	 * 
	 * @return void
	 */
	public function edit($id = 0)
	{
		$id or redirect('admin/commandes');

		// Get the commande based on the ID
		$commande = $this->commande_m->get($id);

		// Validate the results
		if ($this->form_validation->run())
		{

                        if ($commande->user_id > 0)
			{
				$client['user_id'] = $this->input->post('user_id');

			}
			else
			{
				$client['user_name']    = $this->input->post('user_name');
				$client['user_email']   = $this->input->post('user_email');
			}
 			$commande_arr = array_merge($client, array(
//				'user_website' => $this->input->post('user_website'),
				'telephone' => $this->input->post('telephone'),
				'heure_livraison' => $this->input->post('heure_livraison'),
				'info_acces' => $this->input->post('info_acces'),
				'info_payment' => $this->input->post('info_payment'),
				'adresse_livraison' => $this->input->post('adresse_livraison'),
				'company' => $this->input->post('company'),
				'message' => $this->input->post('message'),
				'commande' => $this->input->post('commande'),                             
				'total' => $this->input->post('total'),                             
			));
                   
			// Update the commande
			$this->commande_m->update($id, $commande_arr)
				? $this->session->set_flashdata('success', lang('commandes:edit_success'))
				: $this->session->set_flashdata('error', lang('commandes:edit_error'));

			// Fire an event. A commande has been updated.
			Events::trigger('commande_updated', $id);

			redirect('admin/commandes');
		}

		// Loop through each rule
		foreach ($this->validation_rules as $rule)
		{
			if ($this->input->post($rule['field']) !== null)
			{
				$commande->{$rule['field']} = $this->input->post($rule['field']);
			}
		}


  //            die(print_r($commande));
		$this->template
			->title($this->module_details['name'], sprintf(lang('commandes:edit_title'), $commande->id))
			->append_metadata($this->load->view('fragments/wysiwyg', array(), true))
			->set('commande', $commande)
			->build('admin/form');
	}

        // Admin: Delete an order
	public function delete($ids)
	{
		// Check for one
		$ids = ( ! is_array($ids)) ? array($ids) : $ids;

		// Go through the array of ids to delete
		$commandes = array();
		foreach ($ids as $id)
		{
			// Get the current commande so we can grab the id too
			if ($commande = $this->commande_m->get($id))
			{
				$this->commande_m->delete((int) $id);

				// Wipe cache for this model, the content has changed
				$this->pyrocache->delete('commande_m');
				$commandes[] = $commande->id;
			}
		}

		// Some commandes have been deleted
		if ( ! empty($commandes))
		{
			(count($commandes) == 1)
				? $this->session->set_flashdata('success', sprintf(lang('commandes:delete_single_success'), $commandes[0]))				/* Only deleting one commande */
				: $this->session->set_flashdata('success', sprintf(lang('commandes:delete_multi_success'), implode(', #', $commandes )));	/* Deleting multiple commandes */
		
			// Fire an event. One or more commandes were deleted.
			Events::trigger('commande_deleted', $commandes);
		}

		// For some reason, none of them were deleted
		else
		{
			$this->session->set_flashdata('error', lang('commandes:delete_error'));
		}

		redirect('admin/commandes');
	}

	/**
	 * Approve a commande
	 * 
	 * @param  mixed $ids		id or array of ids to process
	 * @param  bool $redirect	optional if a redirect should be done
	 * @return void
	 */
	public function approve($id = 0, $redirect = true)
	{
		$id && $this->_do_action($id, 'approve');

		$redirect AND redirect('admin/commandes');
	}

	/**
	 * Unapprove a commande
	 * 
	 * @param  mixed $ids		id or array of ids to process
	 * @param  bool $redirect	optional if a redirect should be done
	 * @return void
	 */
	public function unapprove($id = 0, $redirect = true)
	{
		$id && $this->_do_action($id, 'unapprove');

		if ($redirect)
		{
			$this->session->set_flashdata('is_active', 1);

			redirect('admin/commandes');
		}
	}

	/**
	 * Do the actual work for approve/unapprove
	 * @access protected
	 * @param  int|array $ids	id or array of ids to process
	 * @param  string $action	action to take: maps to model
	 * @return void
	 */
	protected function _do_action($ids, $action)
	{
		$ids		= ( ! is_array($ids)) ? array($ids) : $ids;
		$multiple	= (count($ids) > 1) ? '_multiple' : null;
		$status		= 'success';

		foreach ($ids as $id)
		{
			if ( ! $this->commande_m->{$action}($id))
			{
				$status = 'error';
				break;
			}

			if ($action == 'approve')
			{
				// add an event so third-party devs can hook on
				Events::trigger('commande_approved', $this->commande_m->get($id));
			}
			else
			{
				Events::trigger('commande_unapproved', $id);
			}
		}

		$this->session->set_flashdata(array($status => lang('commandes:' . $action . '_' . $status . $multiple)));
	}

	public function preview($id = 0)
	{
     
            // get order info
            $commande = $this->commande_m->get($id) ;

            // get products details from order
            $commande_arr = $this->commande_m->order_product_details($commande->commande);
            // convert to html div for use in view
            $commande_html = $this->commande_m->order_html($commande_arr) ;

            $this->template
                ->set_layout(false)
                ->set('commande', $commande)
                ->set('commande_arr', $commande_arr)
                ->set('commande_html', $commande_html)
                    ->build('admin/preview');
	}
        
        /**
	 * Prints an order
	 *
	 */
	public function printout($id = 0)
        {
            $this->load->library('variables/variables');
            $vars = $this->variables_m->get_all();

            foreach ($vars as $value) {
                $variables[$value->name] = $value->data ;
            }

            // get order info
            $commande = $this->commande_m->get($id) ;
            // get products details from order
            $commande_arr = $this->commande_m->order_product_details($commande->commande);
            // convert to html div for use in view
            $commande_html = $this->commande_m->order_html($commande_arr) ;
            
            $this->template
                ->set_layout(false)
                ->set('commande', $commande)
                ->set('variables', $variables)
                ->set('commande_arr', $commande_arr)
                ->set('commande_html', $commande_html)
                     ->build('admin/printout');
        }
        
        /**
	 * Prints an order
	 *
	 */
	public function reductions($id = 0)
        {
            $where =   "`is_active` = '1'" ;              
            // Create pagination links
//            $total_rows = $this->commande_m->count_by($where);           
//            $pagination = create_pagination('admin/commandes/index', $total_rows);
            $result = $this->db->select()
                    ->from('commandes_promo')
//                    ->where($where)
//                    ->limit($pagination['limit'], $pagination['offset'])
//                    ->order_by('created_on', 'desc')
                    ->get();
            
            //die("admin/reductions");
            $this->template
              //  ->set_layout(false)
                     ->build('admin/tables/reductions');
        }
        
        /**
	 * Create a reduction code
	 *
	 */
	public function make_reduction($id = 0)
        {
                die('make_reduction');
        }        
	/**
	 * Recap
	 * 
	 * @return void
	 */
	public function recap()
	{
            // Only show is_active = 0 if we are moderating commandes
            $is_active = Settings::get('moderate_commandes') ;
            $where = '';
            if ($is_active == 0) 
            {
              $where = "(`is_active` = '1' OR `is_active` = '0')" ;              
            } else {
              //$where = "`is_active` = '0'" ;                              
                if($this->input->post('f_active') == 1)
                {
                   $where =   "`is_active` = '1'" ; ;                    

                } else {
                   $where =   "`is_active` = '0'" ;
                }
            }            
            
            $content_title = $is_active ? lang('commandes:active_title') : lang('commandes:inactive_title') ;

            // keywords filter
            if (trim($this->input->post('f_keywords')))
            {
                $search = trim($this->input->post('f_keywords')) ;
                $where .= " AND (`entry_title` LIKE '%$search%' "
                        . " OR `adresse_livraison` LIKE '%$search%'"
                        . " OR `telephone` LIKE '%$search%'"
                        . " OR `adresse_livraison` LIKE '%$search%'"
                        . " OR `company` LIKE '%$search%'"
                        . ")" ;
            } 
            //die($where);
            
            
            // Create pagination links
            $total_rows = $this->commande_m->count_by($where);
            $pagination = create_pagination('admin/commandes/recap', $total_rows);
            $result = $this->db->select()
                    ->from('commandes')
                    ->join('users', 'users.id = commandes.user_id')
                    ->join('profiles', 'users.id = profiles.user_id')
                    ->where($where)
                    ->limit($pagination['limit'], $pagination['offset'])
                    ->order_by('commandes.created_on', 'desc')
                    ->get();

            $commandes = $result->result();
            
            $this->input->is_ajax_request() && $this->template->set_layout(false);

            $module_list = $this->commande_m->get_slugs();
//die(var_dump($commandes));
            $this->template
                    ->title($this->module_details['name'])
                    ->append_js('admin/filter.js')
                    ->set('module_list',		$module_list)
                    ->set('content_title',		$content_title)
                    ->set('commandes',		$this->commandes->process($commandes))
                    ->set('commandes_active',	$is_active)
                    ->set('pagination',		$pagination);

            $this->input->is_ajax_request() ? $this->template->build('admin/tables/recap') : $this->template->build('admin/index');
	}                
}
