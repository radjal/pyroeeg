<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Show HTML code on your site
 * 
 * @author  	Radja LOMAS
 * @package		based on PyroCMS\Core\Widgets
 */
class Widget_Htmlv2 extends Widgets
{


	/**
	 * The widget title
	 *
	 * @var array
	 */
	public $title = 'HTMLv2';

	/**
	 * The translations for the widget description
	 *
	 * @var array
	 */
	public $description = array(
		'en' => 'Create blocks of custom HTML with extra wrapper',
		'fr' => 'Créez des blocs HTML personnalisés avec un div envelopant',
	);
	
	/**
	 * The author of the widget
	 *
	 * @var string
	 */
	public $author = 'Radja Lomas';

	/**
	 * The author's website.
	 * 
	 * @var string 
	 */
	public $website = 'http:/radjal.free.fr/';

	/**
	 * The version of the widget
	 *
	 * @var string
	 */
	public $version = '1.0.0';

	/**
	 * The fields for customizing the options of the widget.
	 *
	 * @var array 
	 */
	public $fields = array(
		array(
			'field' => 'htmlv2',
			'label' => 'HTMLv2',
			'rules' => 'required'
		)
	);

	/**
	 * The main function of the widget.
	 *
	 * @param array $options The options for displaying an HTML widget.
	 * @return array 
	 */
	public function run($options)
	{
		if (empty($options['htmlv2']))
		{
			return array('output' => '');
		}

		// Store the feed items
		return array('output' => $this->parser->parse_string($options['htmlv2'], null, true));
	}

}