<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pmslider_m extends MY_Model {
    
    /**
     * Constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        parent::__construct();
    }
    
    
    public function create_slider($data)
    {
        return $this->insert($data, TRUE);
    }
    
    public function list_sliders()
    {
        $sliders = $this->get_all();
        $list = array();
        
        if(!empty($sliders))
        {
            foreach($sliders as $slider)
            {
                $list[$slider->id] = $slider->title;
            }
        }
        
        return $list;
    }
    
}
