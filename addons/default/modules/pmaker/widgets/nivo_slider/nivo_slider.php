<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 *Nivo slider widget for PyroCMS
 * 
 * Intended for use on cms pages. Usage : 
 * on a CMS page add:
 * 
 * 		{{ widgets:area slug="NAME_OF_AREA" }} 
 * 
 * 'name_of_area' is the name of the widget area you created in the  admin 
 * control panel
 * 
 * @author		Youhan
 * @package		PMaker slider module
 */
class Widget_nivo_slider extends Widgets {

    public $title = array(
            'en' => 'Nivo slider',
    );
    public $description = array(
            'en' => 'Nivo slider widget for PMaker module',
    );
    public $author = 'Youhan';
    public $website = '';
    public $version = '1.0';
    public $fields = array(
       
        array('field' => 'slider', 'lable' => 'Choose Slider'),
        //array('field' => 'orientation', 'lable' => 'orientation'),
        array('field' => 'slider_theme', 'lable' => 'Slider Theme'),
        array('field' => 'transition_effect', 'lable' => 'Transition Effect'),
        array('field' => 'slices', 'lable' => 'Slices'),
        array('field' => 'box', 'lable' => 'Box (Cols x Rows)'),
        array('field' => 'animation_speed', 'lable' => 'Animation Speed'),
        array('field' => 'thumbnail', 'lable' => 'Enable Thumbnail Navigation'),
        array('field' => 'thumbnail_size', 'lable' => 'Thumbnail Size	'),
        array('field' => 'pause_time', 'lable' => 'Pause Time'),
        array('field' => 'start_slide', 'lable' => 'Start Slide'),
        array('field' => 'direction_navigation', 'lable' => 'Enable Direction Navigation'),
        array('field' => 'control_navigation', 'lable' => 'Enable Control Navigation'),
        array('field' => 'hover', 'lable' => 'Pause the Slider on Hover'),
    );

    public function form($options) {
        
            
            !empty($options['slider']) OR $options['slider'] = 0;
            //!empty($options['orientation']) OR $options['orientation'] = 'v';
            !empty($options['slider_theme']) OR $options['slider_theme'] = 'default';
            !empty($options['transition_effect']) OR $options['transition_effect'] = 'random';
            !empty($options['slices']) OR $options['slices'] = '15';
            !empty($options['box_col']) OR $options['box_col'] = '8';
            !empty($options['box_row']) OR $options['box_row'] = '4';
            !empty($options['animation_speed']) OR $options['animation_speed'] = '500';
            !empty($options['enable_thumbnail']) OR $options['enable_thumbnail'] = 'false';
            !empty($options['thumbnail_size_width']) OR $options['thumbnail_size_width'] = '70';
            !empty($options['pause_time']) OR $options['pause_time'] = '3000';
            !empty($options['start_slide']) OR $options['start_slide'] = '0';
            !empty($options['direction_navigation']) OR $options['direction_navigation'] = 'true' ;
            !empty($options['control_navigation']) OR $options['control_navigation'] = 'false';
            !empty($options['hover']) OR $options['hover'] = 'true';
            !empty($options['random_start']) OR $options['random_start'] = 'false';
            

                        
            //prepare sliders options
            class_exists('Pmslider_m') OR $this->load->model('pmaker/pmslider_m');
            $no_slider[0] = "-- No Slider --";
            $pm_sliders = $this->pmslider_m->list_sliders();
            $slider_options = $no_slider + $pm_sliders;
          
            $transitions=array(
                'sliceDown'=>'sliceDown',
                'sliceDownLeft'=>'sliceDownLeft',
                'sliceUp'=>'sliceUp',
                'sliceUpLeft'=>'sliceUpLeft',
                'sliceUpDown'=>'sliceUpDown',
                'sliceUpDownLeft'=>'sliceUpDownLeft',
                'fold'=>'fold',
                'fade'=>'fade',
                'random'=>'random',
                'slideInRight'=>'slideInRight',
                'slideInLeft'=>'slideInLeft',
                'boxRandom'=>'boxRandom',
                'boxRain'=>'boxRandom',
                'boxRainReverse'=> 'boxRainReverse',
                'boxRainGrow'=>'boxRainGrow',
                'boxRainGrowReverse'=>'boxRainGrowReverse'
            );            
            return array(
                'options' => $options,
                'slider_options' => $slider_options,
                'transitions'=>$transitions,
            );
    }

    public function run($options) {
            
        //load library
        class_exists('Pmslider_m') OR $this->load->model('pmaker/pmslider_m');
        class_exists('Pmslide_m') OR $this->load->model('pmaker/pmslide_m');
        //get slider
        $slider = $this->pmslider_m->get_by('id',$options['slider']);
        
        //get slides
        $slides = $this->pmslide_m->get_slides($options['slider']);
        
        // add path to widget's assets
        // MODIFY THIS PATH IF YOU'D LIKE TO KEEP THE MODULE ELSEWHERE
        //Here we assume you will put the module either at shared addons path or site addons path
		$siteref= 'addons/'.SITE_REF.'/modules/pmaker/widgets/nivo_slider/assets/js/jquery.nivo.slider.pack.js';
		if(file_exists($siteref))
		{
			Asset::add_path('nivo-slider', 'addons/'.SITE_REF.'/modules/pmaker/widgets/nivo_slider/assets/');
		}
		else
		{
			Asset::add_path('nivo-slider', SHARED_ADDONPATH.'modules/pmaker/widgets/nivo_slider/assets/');
		}


        
        Asset::css('nivo-slider::nivo-slider.css', false, 'nivo');
        Asset::css('nivo-slider::default/default.css',false,'theme');
        
        
        if($slider->jquery == "Yes")
        {
                Asset::js('nivo-slider::jquery.min.js', false, 'topofthepage');    
                Asset::js('nivo-slider::jquery.nivo.slider.pack.js', false, 'nivo');
        }
        else
        {
                Asset::js('nivo-slider::jquery.nivo.slider.pack.js', false, 'nivo');

        }
        echo Asset::render_js('topofthepage');

        return array(
            'options' =>$options,
            'slider'  => $slider,
            'slides'  => $slides
        );
    }

}
