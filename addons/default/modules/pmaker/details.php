<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * FAQ details file
 * 
 * @author      Alireza Youhan
 * @package 	PyroCMS
 * @subpackage  PMaker Module
 * @category	module class
 */
class Module_Pmaker extends Module {

	public $version = '1.2.0';
	
	public function info()
	{
		return array(
			'name' => array(
				'en' => 'PMaker'
			),
			'description' => array(
				'en' => 'Add Image Sliders to your website, simple.'
			),
			'frontend' => FALSE,
			'backend'  => TRUE,
			'menu'	  => 'content',
			'author' => 'Youhan',
                        'shortcuts' => array(
				array(
			 	   'name' => 'pm:create_slider',
				   'uri' => 'admin/pmaker/create',
				   'class' => 'add'
				),
			),
		);
	}
	
	public function install()
	{
		
		$this->dbforge->drop_table('pmsliders');
		$this->dbforge->drop_table('pmslides');
		
		$tbl_sliders = $this->db->dbprefix('pmsliders');
		$tbl_slides = $this->db->dbprefix('pmslides');
		
		$sliders = "
			CREATE TABLE `{$tbl_sliders}` (
			      `id` int(11) NOT NULL AUTO_INCREMENT,
                              `slug` varchar(255) NOT NULL,
                              `title` varchar(255) NOT NULL,
                              `creation_date` int(11) NOT NULL,
                              `jquery` enum('Yes','No') NOT NULL DEFAULT 'No',
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		";
		
		$slides = "
			CREATE TABLE `{$tbl_slides}` (
                           `id` int(11) NOT NULL AUTO_INCREMENT,
                          `file_id` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                          `slider_id` int(11) DEFAULT NULL,
                          `order` int(3) NOT NULL DEFAULT '0',
                          `html` text,
                          `showcaption` tinyint(4) NOT NULL DEFAULT '0',
                          `link` varchar(255) NOT NULL DEFAULT '#',
                          `linkable` tinyint(4) NOT NULL DEFAULT '0',
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		";
		
		$sliders_sql = $this->db->query($sliders);
		$slide_sql = $this->db->query($slides);
		
		return ($sliders_sql && $slide_sql);
	}

	public function uninstall()
	{
		$sliders_sql = $this->dbforge->drop_table('pmslider');
		$slide_sql = $this->dbforge->drop_table('pmslide');
		
		return ($sliders_sql && $slide_sql);
	}

	public function upgrade($old_version)
	{
		if($old_version == '1.1.0')
                {
                    $this->upgrade_1_1_0_to_1_2_0();
                }
		return TRUE;
	}
	
	public function help()
	{
		// Return a string containing help info
		// You could include a file and return it here.
		return "Some Help Stuff";
	}
         private function upgrade_1_1_0_to_1_2_0()
        {
             
             $this->db->query('ALTER TABLE '.$this->db->dbprefix('pmslides').' CHANGE `file_id` `file_id` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL');
         }
}
/* End of file details.php */