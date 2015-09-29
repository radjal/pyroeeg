<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Theme_Eeg2 extends Theme {

    public $name			= 'Eeg v2 Theme';
    public $author			= 'Radja lomas';
    public $author_website	= 'http://radjal@free.Fr/';
    public $website			= 'http://radjal@free.Fr/';
    public $description		= 'Based on Default PyroCMS v1.0 Theme - 2 Column, Fixed width, Horizontal navigation, CSS3 styling.';
    public $version			= '1.0.0';
	public $options 		= array('show_breadcrumbs' => 	array('title' 		=> 'Show Breadcrumbs',
																'description'   => 'Would you like to display breadcrumbs?',
																'default'       => 'yes',
																'type'          => 'radio',
																'options'       => 'yes=Yes|no=No',
																'is_required'   => true),
									'layout' => 			array('title' => 'Layout',
																'description'   => 'Which type of layout shall we use?',
																'default'       => '2 column',
																'type'          => 'select',
																'options'       => '2 column=Two Column|full-width=Full Width|full-width-home=Full Width Home Page',
																'is_required'   => true),
								   );
}

/* End of file theme.php */
