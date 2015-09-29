<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin commande Fields
 *
 * Manage custom cartess fields for
 * your commande.
 *
 * @author 		Radja Lomas
 * @package 	PyroCMS\Core\Modules\Users\Controllers
 */
class Admin_fields extends Admin_Controller {

	protected $section = 'fields';

	// --------------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();

		// If they cannot administer profile fields,
		// then they can't access anythere here.
		role_or_die('users', 'admin_commande_fields');

		$this->load->driver('streams');
//		$this->lang->load(array('commandes', 'categories'));
	}

	// --------------------------------------------------------------------------
	
	/**
	 * List out profile fields
	 *
	 * @access 	public
	 * @return 	void
	 */
	public function index()
	{
		$buttons = array(
			array(
				'url'		=> 'admin/commandes/fields/edit/-assign_id-', 
				'label'		=> $this->lang->line('global:edit')
			),
			array(
				'url'		=> 'admin/commandes/fields/delete/-assign_id-',
				'label'		=> $this->lang->line('global:delete'),
				'confirm'	=> true
			)
		);

		$this->template->title(lang('global:custom_fields'));

		$this->streams->cp->assignments_table(
								'commande',
								'commandes',
								Settings::get('records_per_page'),
								'admin/commandes/fields/index',
								true,
								array('buttons' => $buttons));
	}

	// --------------------------------------------------------------------------

	/**
	 * Create
	 *
	 * Create a new custom commande field
	 *
	 * @access 	public
	 * @return 	void
	 */
	public function create()
	{
		$extra['title'] 		= lang('streams:add_field');
		$extra['show_cancel'] 	= true;
		$extra['cancel_uri'] 	= 'admin/commandes/fields';

		$this->streams->cp->field_form('commande', 'commandes', 'new', 'admin/commandes/fields', null, array(), true, $extra);
	}

	// --------------------------------------------------------------------------

	/**
	 * Delete
	 *
	 * Delete a custom commande profile field.
	 *
	 * @access 	public
	 * @return 	void
	 */
	public function delete()
	{
		if ( ! $assign_id = $this->uri->segment(5))
		{
			show_error(lang('streams:cannot_find_assign'));
		}
	
		// Tear down the assignment
		if ( ! $this->streams->cp->teardown_assignment_field($assign_id))
		{
		    $this->session->set_flashdata('notice', lang('streams:field_delete_error'));
		}
		else
		{
		    $this->session->set_flashdata('success', lang('streams:field_delete_success'));			
		}
	
		redirect('admin/commandes/fields');
	}

	// --------------------------------------------------------------------------

	/**
	 * Edit a profile field
	 *
	 * @access 	public
	 * @return 	void
	 */
	public function edit()
	{
		if ( ! $assign_id = $this->uri->segment(5))
		{
			show_error(lang('streams:cannot_find_assign'));
		}

		$extra = array(
			'title'			=> lang('streams:edit_field'),
			'show_cancel'	=> true,
			'cancel_uri'	=> 'admin/commandes/fields'
		);

		$this->streams->cp->field_form('commande', 'commandes', 'edit', 'admin/commandes/fields', $assign_id, array(), true, $extra);
	}
}