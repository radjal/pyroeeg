<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Pmaker admin controller
 * 
 * @author      Alireza Youhan 
 * @package 	PyroCMS
 * @subpackage  Pmaker Module
 * @category	controller
 */
class Admin extends Admin_Controller
{
	/**
	 * Validation rules for creating a new faq
	 *
	 * @var array
	 * @access private
	 */
	private $validation_rules = array();

	
	public function __construct()
	{
		// First call the parent's constructor
		parent::__construct();

		// Load all the required classes
		$this->load->model(array('pmslider_m', 'pmslide_m'));
		$this->lang->load('pmaker');
		
		// Set the validation rules
		$this->validation_rules = array(
			array(
				'field' => 'title',
				'label' => "Title",//@todo lang
				'rules' => 'trim|max_length[255]|required'
			),
			array(
				'field' => 'slug',
				'label' => 'slug',  //@todo lang
				'rules' => 'trim|required'
			),
			array(
				'field' => 'jquery',
				'label'	=> "jQuery",  //@todo lang
				'rules'	=> 'trim|required'
			)

		);

	
		$this->template
                        ->append_css('module::pmaker_admin.css')
                        ->append_js('module::pmaker_admin.js');
		
		//if the request is ajax set layout to false		
		$this->_is_ajax() and $this->template->set_layout(FALSE);
	}


	public function index()
        {
		$this->template->sliders = $this->pmslider_m->order_by('id','DESC')->get_all();
		
		//build output
		$this->template->build('admin/index');
	}


	public function create()
	{
		// Set the validation rules
		$this->form_validation->set_rules($this->validation_rules);
		
		//validate the form
		if($this->form_validation->run())
		{
			//prep data array
			$data = array(
                                    'title' => $this->input->post('title'),
                                    'slug' => $this->input->post('slug'),
                                    'jquery' => $this->input->post('jquery'),
                                    'creation_date' => time()
                        );
			
			//insert data
			if($this->pmslider_m->create_slider($data))
			{
				//success message
				$message = lang('pm:create_success');
				$status = 'success';
			}
			else
			{
				//failure message
				$message= lang('pm:create_error');
				$status = 'error';
			}
			
			//form validated so either the record saved or there was a db error
			if($this->_is_ajax())
			{
				//lets only return a json encoded array
				$json = array('message' => $message,
					      'status' => $status
					      );
				echo json_encode($json);
				return TRUE;
			}
			
			//not ajax lets redirect
			else
			{
				$this->session->set_flashdata($status, $message);
				redirect('admin/pmaker');
			}
		}
		
		//form didn't validate and post is set so we should return our validation errors in json
		if($this->_is_ajax() && $_POST)
		{
			echo json_encode(
                                array(
                                        'status' => 'error',
                                        'message' => validation_errors()
                                )
                        );
		}
		
		//just show the form view
		else
		{
			// Load the view
			$this->template->build('admin/create');	
		}
	}

	/**
	 * Delete an single or many 
	 *
	 * @access public
	 * @return void
	 */
	public function delete()
	{
		$ids = $this->input->post('action_to');
		
		if(!empty($ids))
		{
			//counter
			$i = 0;
			
			$count = count($ids);
			
			//loop through each id and try to delete
			foreach($ids as $id)
			{
				//delete success
				if($this->pmslider_m->delete($id))
				{
					$i++;
				}
			}
			$this->session->set_flashdata('success', sprintf(lang('pm:delete_success'), $i, $count));
		}
		else
		{
			//oops no ids.. ids required here.
			$this->session->set_flashdata('notice', lang('pm:action_empty'));
		}
		
		redirect('admin/pmaker');
	}

	/**
	 * Edit an existing Slider
	 *
	 * @param id the ID to edit
	 * @access public
	 * @return void
	 */
	public function settings($id = FALSE)
	{
		$id_rule = array(
                        'field' => 'slider_id',
                        'label' => lang('pm:id_label'),
                        'rules' => 'required|is_numeric|trim'
                );
		
		//push the special id rule into the validation rules
		array_push($this->validation_rules, $id_rule);
		
		$this->form_validation->set_rules($this->validation_rules);
		
		//form valid lets do something with the data
		if($this->form_validation->run())
		{
			//prep the data
			$data = array(
					'title' => $this->input->post('title'),
					'slug' => $this->input->post('slug'),
					'jquery' => $this->input->post('jquery')
			);
			//update data
			if($this->pmslider_m->update($this->input->post('slider_id'), $data, TRUE))
			{
				$message = lang('pm:update_success');
				$status = 'success';
			}
			else
			{
				$message = lang('pm:update_error');
				$status = 'error';
			}
			
			if($this->_is_ajax())
			{
				$json = array('message' => $message,
					      'status' => $status
					      );
				echo json_encode($json);
				return TRUE;
			}
			else
			{
				$this->session->set_flashdata($status, $message);
				redirect('admin/pmaker');
			}
		}
		
		//id is set.
		if($id)
		{
			//get the Slider
			$this->template->slider = $this->pmslider_m->get($id);
		}
		else
		{
			//oops no id can't do nothing without that!
			redirect('admin/pmaker');
		}
		
		//form didn't validate and post is set so we should return our validation errors in json
		if($this->_is_ajax() && $_POST)
		{
			echo json_encode(
                                array(
                                        'status' => 'error',
                                        'message' => validation_errors()
                                )
                        );
		}
		
		//just build the output
		else
		{
			$this->template->build('admin/settings');
		}
	}
	
        /**
         *  Add/edit slides within a slider
         */
        public function slides($id= FALSE)
        {
                
                if ($id===FALSE){
                    redirect('admin/pmaker');
                }
                //load files model 
                $this->load->model('files/file_folders_m');
                
                //get files folders
                $file_folders = $this->file_folders_m->get_folders();
                $folders_tree = array();
		foreach($file_folders as $folder)
		{
			$indent = repeater('&raquo; ', $folder->depth);
			$folders_tree[$folder->id] = $indent.$folder->name;
		}
                
                //get this slider 
                $slider = $this->pmslider_m->get_by('id',$id);
                
                //get slides of this slider
                $slides = $this->pmslide_m->get_slides($id);
                
                $this->template
			->title($this->module_details['name'])
			->set('folders_tree',$folders_tree)
                        ->set('slider',$slider)
                        ->set('slides',$slides)
			->build('admin/slide');
        }
        
        
        public function ajax_select_folder($folder_id)
	{
                //load files model 
                $this->load->model('files/file_folders_m');
            
                $folder = $this->file_folders_m->get($folder_id);
		
		if (isset($folder->id))
		{
			$folder->images = $this->pmslide_m->get_images_in_folder($folder->id);
			
			return $this->template->build_json($folder);
		}

		echo FALSE;
	}
        
        public function ajax_make_slide()
        {
                $validation_rule = array( 
                    array(
                        'field' => 'file_id',
                        'label' => lang('pm:file_id'),
                        'rules' => 'required|is_numeric|trim'
                    ),
                    array(
                        'field' => 'slider_id',
                        'label' => lang('pm:slider_id'),
                        'rules' => 'required|is_numeric|trim'
                    )
                );

		
		$this->form_validation->set_rules($validation_rule);
		
		//form valid lets do something with the data
		if($this->form_validation->run())
		{
                    //get data and insert to db
                    $data = array(
                        'file_id'   => $this->input->post('file_id'),
                        'slider_id' => $this->input->post('slider_id'),
                        'html'      => $this->input->post('html')
                    );
                    $slide_id = $this->pmslide_m->insert_slide($data);
                    
                    if (intval($slide_id)>0) 
                    {
                            
                            echo json_encode(
                                    array(
                                            'status' => 'success',
                                            'message' => $slide_id
                                    )
                            );
                        
                    }
                    else
                    {
                        
                            echo json_encode(
                                    array(
                                            'status' => 'error',
                                            'message' => 'insert error' //@todo lang
                                    )
                            );
                    }
                    
                }
                else
                {
                        echo json_encode(
                                array(
                                        'status' => 'error',
                                        'message' => validation_errors()
                                )
                        );
                }
        }
        
        public function edit_slide($slide_id)
        {
            
            if (isset($_POST['file_id'])) {
                
                //get slide info
                $slide= $this->pmslide_m->get_slide($slide_id);
                
                $validation_rule = array( 
                    array(
                        'field' => 'html',
                        'label' => lang('pm:html_lable'),
                        'rules' => 'required|trim'
                    ),
                    array(
                        'field' => 'showcaption',
                        'label' => lang('pm:show_caption'),
                        'rules' => 'required|trim'
                    ),
                    array(
                        'field' => 'link',
                        'label' => lang('pm:hyperlink'), 
                        'rules' => 'required|trim'
                    ),
                    array(
                        'field' => 'linkable',
                        'label' => lang('pm:linkable'),
                        'rules' => 'required|trim'
                    )
                );

                $this->form_validation->set_rules($validation_rule);

                //form valid lets do something with the data
                if($this->form_validation->run())
                {
                    //update info
                    $data= array(
                        'html' =>$this->input->post('html'),
                        'showcaption' =>$this->input->post('showcaption'),
                        'link' =>$this->input->post('link'),
                        'linkable' =>$this->input->post('linkable')
                    );

                    if($this->pmslide_m->edit_slide($slide_id,$data)){
                        $this->session->set_flashdata('success', 'Slide updated successfull.');
                    }else{
                        $this->session->set_flashdata('error', 'Slide updated error.');
                    }

                    redirect('admin/pmaker/slides/'.$slide[0]->slider_id);


                }else{

                    $this->session->set_flashdata('error', validation_errors());
                    redirect('admin/pmaker/slides/'.$slide[0]->slider_id);
                }
                
            }else{
                
                //get slide info
                $slide= $this->pmslide_m->get_slide($slide_id);
                
                //load slide form
                $this->load->view('admin/partials/slide_form',$slide[0]);
                
            }
                
            
            
            
        }
        
        public function ajax_load_slide_form($file_id)
        {
                //load files model 
                $this->load->model('files/file_m');
                
                //get image data
                $file = $this->file_m->get_by('id',$file_id);
                        
                $this->load->view('admin/partials/slide_form',$file);
        }
        
        public function delete_slide($slide_id=null)
        {
            if ($slide_id==null) {
                echo json_encode(
                        array(
                                'status' => 'error',
                                'message' => 'Slide ID is missing in the request'
                        )
                );
                return false;
            }
            if ($this->db->delete('pmslides',array('id'=>$slide_id))) {
                echo json_encode(
                        array(
                                'status' => 'success',
                                'message' => 'Slide deleted.'
                        )
                );
            }else{
                echo json_encode(
                        array(
                                'status' => 'error',
                                'message' => 'database error'
                        )
                );
            }
            
        }
        
        public function ajax_view_slide($slide_id)
        {
            //get slide
            $data['slide']= $this->pmslide_m->get_slide($slide_id);
            
            $this->load->view('admin/partials/slide_view',$data);
        }
	/**
	 * Helper method to allow one form to controll multiple actions
	 *
	 * @access public
	 * @return void
	 */
	public function action()
	{		
		if($this->input->post('btnAction') == 'delete')
		{
			$this->delete();
		}
	}
	
	
        
        /**
	 * Ajax helper to update the sort/display order
	 *
	 * @access	public
	 * @param	none
	 * @return	void
	 */
	public function update_order()
	{
		$data = $this->input->post('order');
		if(is_array($data))
		{
			$order = 1;
			foreach($data as $id)
			{
				$this->pmslide_m->update_order($id, $order);
				$order++;
			}
		}
	}
	
	protected function _is_ajax()
	{
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') ? TRUE : FALSE;
	}
}
