<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Show RSS feeds in your site
 *
 * @author  PyroCMS Development Team
 * @package PyroCMS\Core\Modules\Carte\Widgets
 */
class Widget_Archive extends Widgets
{

	public $author = 'Phil Sturgeon';

	public $website = 'http://philsturgeon.co.uk/';

	public $version = '1.0.0';

	public $title = array(
		'en' => 'Archive',
		'br' => 'Arquivo do Carte',
            'fa' => 'آرشیو',
		'pt' => 'Arquivo do Carte',
		'el' => 'Αρχείο Ιστολογίου',
		'fr' => 'Archives du Carte',
		'ru' => 'Архив',
		'id' => 'Archive',
	);

	public $description = array(
		'en' => 'Display a list of old months with links to posts in those months',
		'br' => 'Mostra uma lista navegação cronológica contendo o índice dos artigos publicados mensalmente',
            'fa'=> 'نمایش لیست ماه های گذشته به همراه لینک به پست های مربوطه',
		'pt' => 'Mostra uma lista navegação cronológica contendo o índice dos artigos publicados mensalmente',
		'el' => 'Προβάλλει μια λίστα μηνών και συνδέσμους σε αναρτήσεις που έγιναν σε κάθε από αυτούς',
		'fr' => 'Permet d\'afficher une liste des mois passés avec des liens vers les posts relatifs à ces mois',
		'ru' => 'Выводит список по месяцам со ссылками на записи в этих месяцах',
		'id' => 'Menampilkan daftar bulan beserta tautan post di setiap bulannya',
	);

	public function run($options)
	{
		$this->load->model('carte/carte_m');
		$this->lang->load('carte/carte');

		return array(
			'archive_months' => $this->carte_m->get_archive_months()
		);
	}

}
