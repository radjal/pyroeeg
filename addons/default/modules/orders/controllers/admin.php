<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * FAQ Module
 *
 * This is a sample module for PyroCMS
 * that illustrates how to use the streams core API
 * for data management. It is also a fully-functional
 * FAQ module so feel free to use it on your sites.
 *
 * Most of these functions use the Streams API CP driver which
 * is designed to handle repetitive CP tasks, down to even loading the page.
 *
 * @author 		Adam Fairholm - PyroCMS Dev Team
 * @package 	PyroCMS
 * @subpackage 	Streams Sample Module
 */
class Admin extends Admin_Controller
{
    // This will set the active section tab
    protected $section = 'order';

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('order');
        $this->load->driver('Streams');
    }

    /**
     * List all Orders using Streams CP Driver
     *
     * We are using the Streams API to grab
     * data from the orders database. It handles
     * pagination as well.
     *
     * @return	void
     */
    public function index()
    {
        // The extra array is where most of our
        // customization options go.
        $extra = array();

        // The title can be a string, or a language
        // string, prefixed by lang:
        $extra['title'] = 'lang:order:order';
        
        // We can customize the buttons that appear
        // for each row. They point to our own functions
        // elsewhere in this controller. -entry_id- will
        // be replaced by the entry id of the row.
        $extra['buttons'] = array(
            array(
                'label' => lang('global:edit'),
                'url' => 'admin/order/edit/-entry_id-'
            ),
            array(
                'label' => lang('global:delete'),
                'url' => 'admin/order/delete/-entry_id-',
                'confirm' => true
            )
        );

        // In this example, we are setting the 5th parameter to true. This
        // signals the function to use the template library to build the page
        // so we don't have to. If we had that set to false, the function
        // would return a string with just the form.
        $this->streams->cp->entries_table('order', 'order', 3, 'admin/order/index', true, $extra);
    }

    /**
     * List all Orders (Alternate)
     *
     * This example is similar to index(), but we are
     * getting entries manually using the entries API
     * and displaying the output in a custom view file.
     *
     * @return  void
     */
    public function index_alt()
    {
        // Get our entries. We are simply specifying
        // the stream/namespace, and then setting the pagination up.
        $params = array(
            'stream' => 'order',
            'namespace' => 'order',
            'paginate' => 'yes',
            'limit' => 4,
            'pag_segment' => 4
        );
        $data['order'] = $this->streams->entries->get_entries($params);

        // Build the page. See views/admin/index.php
        // for the view code.
        $this->template
                    ->title($this->module_details['name'])
                    ->build('admin/index', $data);
    }

    /**
     * Create a new FAQ entry
     *
     * We're using the entry_form function
     * to generate the form.
     *
     * @return	void
     */
    public function create()
    {
        $extra = array(
            'return' => 'admin/order',
            'success_message' => lang('order:submit_success'),
            'failure_message' => lang('order:submit_failure'),
            'title' => 'lang:order:new',
         );

        $this->streams->cp->entry_form('order', 'order', 'new', null, true, $extra);
    }
    
    /**
     * Edit a FAQ entry
     *
     * We're using the entry_form function
     * to generate the edit form. We're passing the
     * id of the entry, which will allow entry_form to
     * repopulate the data from the database.
     *
     * @param   int [$id] The id of the FAQ to the be deleted.
     * @return	void
     */
    public function edit($id = 0)
    {
        $extra = array(
            'return' => 'admin/order',
            'success_message' => lang('order:submit_success'),
            'failure_message' => lang('order:submit_failure'),
            'title' => 'lang:order:edit'
        );

        $this->streams->cp->entry_form('order', 'order', 'edit', $id, true, $extra);
    }

    /**
     * Delete a FAQ entry
     * 
     * @param   int [$id] The id of FAQ to be deleted
     * @return  void
     */
    public function delete($id = 0)
    {
        $this->streams->entries->delete_entry($id, 'order', 'order');
        $this->session->set_flashdata('error', lang('order:deleted'));
 
        redirect('admin/order/');
    }

}