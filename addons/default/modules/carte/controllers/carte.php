<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Public carte module controller
 *
 * @author Radja Lomas
 * @package PyroCMS\Core\Modules\carte\Controllers
 */
class carte extends Public_Controller
{
	public $stream;

	/**
	 * Every time this controller is called should:
	 * - load the carte and carte_categories models.
	 * - load the keywords library.
	 * - load the carte language file.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('carte_m');
		$this->load->model('carte_categories_m');
		$this->load->library(array('keywords/keywords'));
		$this->lang->load('carte');

		$this->load->driver('Streams');

		// We are going to get all the categories so we can
		// easily access them later when processing posts.
		$cartes = $this->db->get('carte_categories')->result_array();
		$this->categories = array();
	
		foreach ($cartes as $carte)
		{
			$this->categories[$carte['id']] = $carte;
		}

		// Get carte stream. We use this to set the template
		// stream throughout the carte module.
		$this->stream = $this->streams_m->get_stream('carte', true, 'cartes');
	}

	/**
	 * Index
	 *
	 * List out the carte posts.
	 *
	 * URIs such as `carte/page/x` also route here.
	 */
	public function index()
	{
		$this -> voirsemaine();
	}
	
	public function voirsemaine($week=0) 
	{
		// set current week and year
		$currentweek = date("W");
		$year = date("Y");
                $today = date("Y-m-d");    
		
			// set week if not already done
			if($week === 0 ) 
			{
				$week = $currentweek ;
			} 
			else
			{
				//if($currentweek > $week ) $year ++ ;
			} 
			
		$next_week_no = $week +1 ;
		$previous_week_no = $week -1 ;
                // @todo correct week if necessary
                $start_date = new DateTime();
		$start_date -> setISODate($year,$week);
		$week_begin = $start_date -> format('Y-m-d');
		$time = strtotime($week_begin, time());
		$time += 4*24*3600; // add 4 days, just for the week days 
		$week_finish = date('Y-m-d', $time);
                

                // Get the latest carte posts
		$posts = $this->streams->entries->get_entries(array(
			'stream'		=> 'carte',
			'namespace'		=> 'cartes',
			'limit'			=> Settings::get('records_per_page'),
			'where'			=> "`date_carte` >= '$week_begin' AND `date_carte` <= '$week_finish' AND `status` = 'live'",
                        'order_by'              => 'date_carte',
                        'sort'                  => 'asc',                   
		));

		// Process posts
		foreach ($posts['entries'] as &$post)
		{
			$this->_process_post($post);
		}

		// Set meta description based on post titles
		$meta = $this->_posts_metadata($posts['entries']);

		$data = array(
			'pagination' => $posts['pagination'],
			'posts' => $posts['entries'],
		);

                //die(print_r($posts))   ;
                
		$this->template
			->title("Semaine $week, du " . format_date($week_begin, "%d %b") ." au ". format_date($week_finish, "%d %B"))
			->set('week_begin', $week_begin)
			->set('week_finish', $week_finish)
			->set('next_week_no', $next_week_no)
                        ->set('previous_week_no', $previous_week_no)
			->set('week', $week)
			->set('today', $today)
			->set('year', $year)
			->set_breadcrumb(lang('carte:carte_title'))
			->set_metadata('og:title', $this->module_details['name'], 'og')
			->set_metadata('description', $this->variables->description_site)
			->set_metadata('og:type', 'carte', 'og')
			->set_metadata('og:url', current_url(), 'og')
			->set_stream($this->stream->stream_slug, $this->stream->stream_namespace)
			->set('posts', $posts['entries'])
			->set('pagination', $posts['pagination'])
			->build('cartesemaine');

	}

	/**
	 * Lists the posts in a specific category.
	 *
	 * @param string $slug The slug of the category.
	 */
	public function category($slug = '')
	{
		$slug or redirect('carte');

		// Get category data
		$category = $this->carte_categories_m->get_by('slug', $slug) OR show_404();

		// Get the carte posts
		$params = array(
			'stream'		=> 'carte',
			'namespace'		=> 'cartes',
			'limit'			=> Settings::get('records_per_page'),
			'where'			=> "`status` = 'live' AND `category_id` = '{$category->id}'",
			'paginate'		=> 'yes',
			'pag_segment'	=> 4
		);
		$posts = $this->streams->entries->get_entries($params);

		// Process posts
		foreach ($posts['entries'] as &$post)
		{
			$this->_process_post($post);
		}

		// Set meta description based on post titles
		$meta = $this->_posts_metadata($posts['entries']);

		// Build the page
		$this->template->title($this->module_details['name'], $category->title)
			->set_metadata('description', $category->title.'. '.$meta['description'])
			->set_metadata('keywords', $category->title)
			->set_breadcrumb(lang('carte:carte_title'), 'carte')
			->set_breadcrumb($category->title)
			->set('pagination', $posts['pagination'])
			->set_stream($this->stream->stream_slug, $this->stream->stream_namespace)
			->set('posts', $posts['entries'])
			->set('category', (array)$category)
			->build('posts');
	}

	/**
	 * Lists the posts in a specific year/month.
	 *
	 * @param null|string $year  The year to show the posts for.
	 * @param string      $month The month to show the posts for.
	 */
	public function archive($year = null, $month = '01')
	{
		$year or $year = date('Y');
		$month_date = new DateTime($year.'-'.$month.'-01');

		// Get the carte posts
		$params = array(
			'stream'		=> 'carte',
			'namespace'		=> 'cartes',
			'limit'			=> Settings::get('records_per_page'),
			'where'			=> "`status` = 'live'",
			'year'			=> $year,
			'month'			=> $month,
			'paginate'		=> 'yes',
			'pag_segment'	=> 5
		);
		$posts = $this->streams->entries->get_entries($params);

		$month_year = format_date($month_date->format('U'), lang('carte:archive_date_format'));

		foreach ($posts['entries'] as &$post)
		{
			$this->_process_post($post);
		}

		// Set meta description based on post titles
		$meta = $this->_posts_metadata($posts['entries']);

		$this->template
			->title($month_year, lang('carte:archive_title'), lang('carte:carte_title'))
			->set_metadata('description', $month_year.'. '.$meta['description'])
			->set_metadata('keywords', $month_year.', '.$meta['keywords'])
			->set_breadcrumb(lang('carte:carte_title'), 'carte')
			->set_breadcrumb(lang('carte:archive_title').': '.format_date($month_date->format('U'), lang('carte:archive_date_format')))
			->set('pagination', $posts['pagination'])
			->set_stream($this->stream->stream_slug, $this->stream->stream_namespace)
			->set('posts', $posts['entries'])
			->set('month_year', $month_year)
			->build('archive');
	}

	/**
	 * View a post
	 *
	 * @param string $slug The slug of the carte post.
	 */
	public function voirjour($slug = '')
	{

		
		// We need a slug to make this work.
		if ( ! $slug)
		{
               // die(strtolower(format_date(date("Y-m-d"), "%A-%d-%B-%y")));
			//redirect('carte');
                    $slug = strtolower(format_date(date("Y-m-d"), "%A-%d-%B-%Y")) ;
                    //redirect('carte/voirjour/'.$slug);
		}


		$params = array(
			'stream'		=> 'carte',
			'namespace'		=> 'cartes',
			'limit'			=> 1,
			'where'			=> "`slug` = '{$slug}'"
		);
		$data = $this->streams->entries->get_entries($params);
		$post = (isset($data['entries'][0])) ? $data['entries'][0] : null;

                if ( ! $post or ($post['status'] !== 'live' and ! $this->ion_auth->is_admin()))
		{
			redirect('carte');
		}
                 
//                        die($slug);
		$this->_single_view($post);
	}

	/**
	 * Preview a post
	 *
	 * @param string $hash the preview_hash of post
	 */
	public function preview($hash = '')
	{
		if ( ! $hash)
		{
			redirect('carte');
		}

		$params = array(
			'stream'		=> 'carte',
			'namespace'		=> 'cartes',
			'limit'			=> 1,
			'where'			=> "`preview_hash` = '{$hash}'"
		);
		$data = $this->streams->entries->get_entries($params);
		$post = (isset($data['entries'][0])) ? $data['entries'][0] : null;

		if ( ! $post)
		{
			redirect('carte');
		}

		if ($post['status'] === 'live')
		{
			redirect('carte/'.date('Y/m', $post['created_on']).'/'.$post['slug']);
		}

		// Set index nofollow to attempt to avoid search engine indexing
		$this->template->set_metadata('index', 'nofollow');

		$this->_single_view($post);
	}

	/**
	 * Tagged Posts
	 *
	 * Displays carte posts tagged with a
	 * tag (pulled from the URI)
	 *
	 * @param string $tag
	 */
	public function tagged($tag = '')
	{
		// decode encoded cyrillic characters
		$tag = rawurldecode($tag) or redirect('carte');

		// Here we need to add some custom joins into the
		// row query. This shouldn't be in the controller, but
		// we need to figure out where this sort of stuff should go.
		// Maybe the entire carte moduel should be replaced with stream
		// calls with items like this. Otherwise, this currently works.
		$this->row_m->sql['join'][] = 'JOIN '.$this->db->protect_identifiers('keywords_applied', true).' ON '.
			$this->db->protect_identifiers('keywords_applied.hash', true).' = '.
			$this->db->protect_identifiers('carte.keywords', true);

		$this->row_m->sql['join'][] = 'JOIN '.$this->db->protect_identifiers('keywords', true).' ON '.
			$this->db->protect_identifiers('keywords.id', true).' = '.
			$this->db->protect_identifiers('keywords_applied.keyword_id', true);	

		$this->row_m->sql['where'][] = $this->db->protect_identifiers('keywords.name', true)." = '".str_replace('-', ' ', $tag)."'";

		// Get the carte posts
		$params = array(
			'stream'		=> 'carte',
			'namespace'		=> 'cartes',
			'limit'			=> Settings::get('records_per_page'),
			'where'			=> "`status` = 'live'",
			'paginate'		=> 'yes',
			'pag_segment'	=> 4
		);
		$posts = $this->streams->entries->get_entries($params);

		// Process posts
		foreach ($posts['entries'] as &$post)
		{
			$this->_process_post($post);
		}

		// Set meta description based on post titles
		$meta = $this->_posts_metadata($posts['entries']);

		$name = str_replace('-', ' ', $tag);

		// Build the page
		$this->template
			->title($this->module_details['name'], lang('carte:tagged_label').': '.$name)
			->set_metadata('description', lang('carte:tagged_label').': '.$name.'. '.$meta['description'])
			->set_metadata('keywords', $name)
			->set_breadcrumb(lang('carte:carte_title'), 'carte')
			->set_breadcrumb(lang('carte:tagged_label').': '.$name)
			->set('pagination', $posts['pagination'])
			->set_stream($this->stream->stream_slug, $this->stream->stream_namespace)
			->set('posts', $posts['entries'])
			->set('tag', $tag)
			->build('posts');
	}

	/**
	 * Process Post
	 *
	 * Process data that was not part of the 
	 * initial streams call.
	 *
	 * @return 	void
	 */
	private function _process_post(&$post)
	{
		$this->load->helper('text');
                $today = date("Y-m-d") ;
                if($post['date_carte'] < $today ) 
                {
                    $post['date_passed'] = true ;
                } else {
                    $post['date_passed'] = false ;                    
                }
		/*
		// Keywords array
		$keywords = Keywords::get($post['keywords']);
                $formatted_keywords = array();
		$keywords_arr = array();

		foreach ($keywords as $key)
		{
			$formatted_keywords[] 	= array('keyword' => $key->name);
			$keywords_arr[] 		= $key->name;

		}
                
		$post['keywords'] = $formatted_keywords;
		$post['keywords_arr'] = $keywords_arr;
                 * 
                 */

		// Full URL for convenience.
		$post['url'] = site_url('carte/'.date('Y/m', $post['created_on']).'/'.$post['slug']);
	
		// What is the preview? If there is a field called intro,
		// we will use that, otherwise we will cut down the carte post itself.
		//$post['preview'] = (isset($post['intro'])) ? $post['intro'] : $post['body'];


		// Category
		if ($post['category_id'] > 0 and isset($this->categories[$post['category_id']]))
		{
			$post['category'] = $this->categories[$post['category_id']];
		}
                // die(print_r($post))   ;
        }

	/**
	 * Posts Metadata
	 *
	 * @param array $posts
	 *
	 * @return array keywords and description
	 */
	private function _posts_metadata(&$posts = array())
	{
		$keywords = array();
		$description = array();

		// Loop through posts and use titles for meta description
		if ( ! empty($posts))
		{
			foreach ($posts as &$post)
			{
				if (isset($post['category']) and ! in_array($post['category']['title'], $keywords))
				{
					$keywords[] = $post['category']['title'];
				}

				$description[] = $post['title'];
			}
		}

		return array(
			'keywords' => implode(', ', $keywords),
			'description' => implode(', ', $description)
		);
	}

	/**
	 * Single View
	 *
	 * Generate a page for viewing a single
	 * carte post.
	 *
	 * @access 	private
	 * @param 	array $post The post to view
	 * @return 	void
	 */
	private function _single_view($post)
	{

		$this->session->set_flashdata(array('referrer' => $this->uri->uri_string()));

		$this->template->set_breadcrumb(lang('carte:carte_title'), 'carte');

		if ($post['category_id'] > 0)
		{
			// Get the category. We'll just do it ourselves
			// since we need an array.
			if ($category = $this->db->limit(1)->where('id', $post['category_id'])->get('carte_categories')->row_array())
			{
				$this->template->set_breadcrumb($category['title'], 'carte/category/'.$category['slug']);

				// Set category OG metadata			
				$this->template->set_metadata('article:section', $category['title'], 'og');

				// Add to $post
				$post['category'] = $category;
			}
		}

		$this->_process_post($post);
//------------- comments
                // If comments are enabled, go fetch them all
		if (Settings::get('enable_comments'))
		{
			// Load Comments so we can work out what to do with them
			$this->load->library('comments/comments', array(
				'entry_id' => $post['id'],
				'entry_title' => $post['title'],
				'module' => 'blog',
				'singular' => 'blog:post',
				'plural' => 'blog:posts',
			));

			// Comments enabled can be 'no', 'always', or a strtotime compatable difference string, so "2 weeks"
			$this->template->set('form_display', (
				$post['comments_enabled'] === 'always' or
					($post['comments_enabled'] !== 'no' and time() < strtotime('+'.$post['comments_enabled'], $post['created_on']))
			));
		}
                
//------------- comments

		// If commandes are enabled
		if (Settings::get('enable_commandes'))
		{
			// Load commandes 
			$this->load->library('commandes/commandes', array(
				'entry_id' => $post['id'],
				'entry_title' => $post['title'],
				'module' => 'carte',
				'singular' => 'carte:post',
				'plural' => 'carte:posts',
			));
			// Commandes enabled can be 'no', 'always', or a strtotime compatable difference string, so "2 weeks"
			//$this->template->set('form_display', true);
		}

		$this->template
			->title($post['title'], lang('carte:carte_title'))
			->set_metadata('og:type', 'article', 'og')
			->set_metadata('og:url', current_url(), 'og')
			->set_metadata('og:title', $post['title'], 'og')
			->set_metadata('og:site_name', Settings::get('site_name'), 'og')
	/*		->set_metadata('og:description', $post['preview'], 'og')
			->set_metadata('article:published_time', date(DATE_ISO8601, $post['created_on']), 'og')
			->set_metadata('article:modified_time', date(DATE_ISO8601, $post['updated_on']), 'og') */
	//		->set_metadata('description', $post['preview'])
	//		->set_metadata('keywords', implode(', ', $post['keywords_arr']))
			->set_breadcrumb($post['title'])
			->set_stream($this->stream->stream_slug, $this->stream->stream_namespace)
			->set('post', array($post))
			->build('cartejour');
	}
}
