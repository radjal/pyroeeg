<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Faq extends Public_Controller
{

    /**
     * The constructor
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->lang->load('order');
        $this->load->driver('Streams');
        $this->template->append_css('module::order.css');
    }
     /**
     * List all Orders
     *
     * We are using the Streams API to grab
     * data from the orders database. It handles
     * pagination as well.
     *
     * @access	public
     * @return	void
     */
    public function index()
    {
        $params = array(
            'stream' => 'order',
            'namespace' => 'order',
            'paginate' => 'yes',
            'pag_segment' => 4
        );

        $this->orders = $this->streams->entries->get_entries($params);
//        var_dump($this->orders);
        // Build the page
        $this->template->title($this->module_details['name'])
                ->set('order', $this->orders )
                ->build('index', $this);
    }

}

/* End of file order.php */
