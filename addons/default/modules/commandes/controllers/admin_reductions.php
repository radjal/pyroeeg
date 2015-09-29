<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * @author 		Radja Lomas
 * @package 	custom\Modules\Commandes\Controllers
 */
class Admin_Reductions extends Admin_Controller {

	/**
	 * Array that contains the validation rules
	 * @access private
	 * @var array
	 */
	private $validation_rules = array(
            /*
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
             * 
             */
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
		//$this->form_validation->set_rules($this->validation_rules);
	}

	/**
	 * Index
	 * 
	 * @return void
	 */
	public function index()
	{   
            die("admin_reductions/index");
            $content_title = "title" ;
            $this->template
                    ->title($this->module_details['name'])
                    ->append_js('admin/filter.js')
                    ->set('content_title', $content_title)
                    ->set('pagination', $pagination)
                        ->build('admin/printout');
       }

}
