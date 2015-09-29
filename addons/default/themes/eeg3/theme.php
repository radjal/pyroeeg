<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Theme_Eeg3 extends Theme
{
    public $name			= 'Equilibre et gourmandise V3';
    public $author			= 'Radja Lomas';
    public $author_website	= ' ';
    public $website			= ' ';
    public $description		= 'Based on Scott Parry\'s HTML5 base template.';
    public $version			= '1.0.0';
	public $options 		= array(
		'slider' => array(
			'title'         => 'Slider',
			'description'   => 'Would you like to display the slider on the homepage?',
			'default'       => 'yes',
			'type'          => 'radio',
			'options'       => 'yes=Yes|no=No',
			'is_required'   => true
		),
            	'theme_css' => array(
			'title'         => 'Theme css',
			'description'   => 'Theme css code…',
			'default'       => '//* css code here *//',
			'type'          => 'textarea',
			'options'       => '',
			'is_required'   => true
		),
		'show_breadcrumbs' 	=> array(
			'title'         => 'Do you want to show breadcrumbs?',
			'description'   => 'If selected it shows a string of breadcrumbs at the top of the page.',
			'default'       => 'yes',
			'type'          => 'radio',
			'options'       => 'yes=Yes|no=No',
			'is_required'   => true
		),
	);
}

/* End of file theme.php */