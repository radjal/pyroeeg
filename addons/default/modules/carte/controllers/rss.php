<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @author Radja Lomas
 * @package PyroCMS\Core\Modules\carte\Controllers
 */
class Rss extends Public_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('carte_m');
		$this->load->helper('xml');
		$this->load->helper('date');
		$this->lang->load('carte');
	}

	public function index()
	{
		$posts = $this->pyrocache->model('carte_m', 'get_many_by', array(array(
			'status' => 'live',
			'limit' => Settings::get('rss_feed_items'))
		), Settings::get('rss_cache'));

		$this->output->set_content_type('application/rss+xml');
		echo $this->load->view('rss', $this->_build_feed($posts, $this->lang->line('carte:rss_name_suffix')), true);
	}

	public function category($slug = '')
	{
		$this->load->model('carte_categories_m');

		if ( ! $category = $this->carte_categories_m->get_by('slug', $slug))
		{
			redirect('carte/rss/all.rss');
		}

		$posts = $this->pyrocache->model('carte_m', 'get_many_by', array(array(
			'status' => 'live',
			'category' => $slug,
			'limit' => Settings::get('rss_feed_items'))
		), Settings::get('rss_cache'));

		$this->output->set_content_type('application/rss+xml');
		echo $this->load->view('rss', $this->_build_feed($posts, $category->title.$this->lang->line('carte:rss_category_suffix')), true);
	}

	public function _build_feed($posts = array(), $suffix = '')
	{
		$data = new stdClass();
		$data->rss = new stdClass();

		$data->rss->encoding = $this->config->item('charset');
		$data->rss->feed_name = Settings::get('site_name').' '.$suffix;
		$data->rss->feed_url = base_url();
		$data->rss->page_description = sprintf($this->lang->line('carte:rss_posts_title'), Settings::get('site_name'));
		$data->rss->page_language = 'en-gb';
		$data->rss->creator_email = Settings::get('contact_email');

		if ( ! empty($posts))
		{
			foreach ($posts as $row)
			{
				//$row->created_on = human_to_unix($row->created_on);
				$row->link = site_url('carte/'.date('Y/m', $row->created_on).'/'.$row->slug);
				$row->created_on = date(DATE_RSS, $row->created_on);

				$intro = (isset($row->intro)) ? $row->intro : $row->body;

				$item = array(
					//'author' => $row->author,
					'title' => xml_convert($row->title),
					'link' => $row->link,
					'guid' => $row->link,
					'description' => $intro,
					'date' => $row->created_on,
					'category' => $row->category_title
				);
				$data->rss->items[] = (object)$item;
			}
		}

		return $data;
	}
}