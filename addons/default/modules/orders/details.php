<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Orders extends Module
{
    public $version = '1.0';

    public function info()
    {
        return array(
            'name' => array(
                'en' => 'Orders'
            ),
            'description' => array(
                'en' => 'Frequently Asked Questions'
            ),
            'frontend' => true,
            'backend' => true,
            'menu' => 'content',
            'sections' => array(
                'order' => array(
                    'name' => 'order:order',
                    'uri' => 'admin/order',
                    'shortcuts' => array(
                        'create' => array(
                            'name' => 'order:new',
                            'uri' => 'admin/order/create',
                            'class' => 'add'
                        )
                    )
                ),
                'categories' => array(
                    'name' => 'order:categories',
                    'uri' => 'admin/order/categories/index',
                    'shortcuts' => array(
                        'create' => array(
                            'name' => 'order:category:new',
                            'uri' => 'admin/order/categories/create',
                            'class' => 'add'
                        )
                    )
                ),
                'fields' => array(
                    'name' => 'global:custom_fields',
                    'uri' => 'admin/order/fields/index',
                    'shortcuts' => array(
                        'create' => array(
                            'name' => 'streams:add_field',
                            'uri' => 'admin/order/fields/create',
                            'class' => 'add'
                        )
                    )
                ),
                
            )
        );
    }

    /**
     * Install
     *
     * This function will set up our
     * FAQ/Category streams.
     */
    public function install()
    {
        // We're using the streams API to
        // do data setup.
        $this->load->driver('Streams');

        $this->load->language('orders/order');

        // Add orders streams
        if ( ! $this->streams->streams->add_stream('lang:order:order', 'order', 'order', 'order_', null)) return false;
        if ( ! $categories_stream_id = $this->streams->streams->add_stream('lang:order:categories', 'categories', 'order', 'order_', null)) return false;

        //$order_categories

        // Add some fields
        $fields = array(
            array(
                'name' => 'Question',
                'slug' => 'question',
                'namespace' => 'order',
                'type' => 'text',
                'extra' => array('max_length' => 200),
                'assign' => 'order',
                'title_column' => true,
                'required' => true,
                'unique' => true
            ),
            array(
                'name' => 'Answer',
                'slug' => 'answer',
                'namespace' => 'order',
                'type' => 'textarea',
                'assign' => 'order',
                'required' => true
            ),
            array(
                'name' => 'Title',
                'slug' => 'order_category_title',
                'namespace' => 'order',
                'type' => 'text',
                'assign' => 'categories',
                'title_column' => true,
                'required' => true,
                'unique' => true
            ),
            array(
                'name' => 'Category',
                'slug' => 'order_category_select',
                'namespace' => 'order',
                'type' => 'relationship',
                'assign' => 'order',
                'extra' => array('choose_stream' => $categories_stream_id)
            )
        );

        $this->streams->fields->add_fields($fields);

        $this->streams->streams->update_stream('order', 'order', array(
            'view_options' => array(
                'id',
                'question',
                'answer',
                'order_category_select'
            )
        ));

        $this->streams->streams->update_stream('categories', 'order', array(
            'view_options' => array(
                'id',
                'order_category_title'
            )
        ));

        return true;
    }

    /**
     * Uninstall
     *
     * Uninstall our module - this should tear down
     * all information associated with it.
     */
    public function uninstall()
    {
        $this->load->driver('Streams');

        // For this teardown we are using the simple remove_namespace
        // utility in the Streams API Utilties driver.
        $this->streams->utilities->remove_namespace('order');

        return true;
    }

    public function upgrade($old_version)
    {
        return true;
    }

    public function help()
    {
        // Return a string containing help info
        // You could include a file and return it here.
        return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
    }

}