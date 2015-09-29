<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Show a list of carte categories.
 *
 * @author        Radja Lomas
 * @package       PyroCMS\Core\Modules\Carte\Widgets
 */
class Widget_Carte_categories extends Widgets
{
	public $author = 'Stephen Cozart';

	public $website = 'http://github.com/clip/';

	public $version = '1.0.0';

	public $title = array(
		'en' => 'Carte Categories',
		'fr' => 'Catégories du Carte',
	);

	public $description = array(
		'en' => 'Show a list of carte categories',
		'fr' => 'Permet d\'afficher la liste de Catégories du Carte',
	);

	public function run()
	{
		$this->load->model('carte/carte_categories_m');

		$categories = $this->carte_categories_m->order_by('title')->get_all();

		return array('categories' => $categories);
	}

}
