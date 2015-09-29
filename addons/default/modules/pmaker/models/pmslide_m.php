<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pmslide_m extends MY_Model {
    
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
    
    /**
     * Get al images within a folder 
     *
     * @author Youhan
     * 
     * @access public
     * @param int $id -->The ID of the folder
     * @param array $options -->Options
     * @return mixed
     */
    public function get_images_in_folder($id, $options = array())
    {

            if (isset($options['offset']))
            {
                    $this->db->limit($options['offset']);
            }

            if (isset($options['limit']))
            {
                    $this->db->limit($options['limit']);
            }

            return $this->db
                            ->select('files.*')
                            ->where('folder_id', $id)
                            ->where('files.type', 'i')
                            ->get('files')
                            ->result();

    }
    
    public function insert_slide($data)
    {
        //get biggest order
        $q = $this->db->select('max(`order`) as maxorder')->from('pmslides')->where('slider_id',$data['slider_id'])->get()->row()->maxorder;
        if ($q===NULL) {
            $data['order'] = 0;
        }else{
            $data['order']=$q+1;
        }
        $this->insert($data,TRUE);
        return $this->db->insert_id();
    }
    
    public function edit_slide($slide_id,$data)
    {
        return $this->db->where('id',$slide_id)->update('pmslides',$data);
    }
    
    public function get_slides($slider_id)
    {
        return $this->db->select('pmslides.*,files.path, files.name, files.description, files.filename,files.filesize,files.height,files.width')
                ->from('pmslides')
                ->where('pmslides.slider_id',$slider_id)
                ->join('files','pmslides.file_id = files.id')
                ->order_by('pmslides.order','ASCE')
                ->get()->result();      
    }
    
    public function get_slide($slide_id)
    {
        return $this->db->select('pmslides.*,files.path, files.name, files.description, files.filename,files.filesize,files.height,files.width')
                ->from('pmslides')
                ->where('pmslides.id',$slide_id)
                ->join('files','pmslides.file_id = files.id')
                ->get()->result();
    }
    
    public function update_order($id, $order)
    {
       return $this->update($id, array("{$this->_table}.order" => $order), TRUE);
    }
}
