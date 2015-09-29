
<?php 


echo Asset::render_js('nivo');
echo Asset::render_css('nivo'); 
Asset::css('nivo-slider::'.$options['slider_theme'].'/'.$options['slider_theme'].'.css',false,'theme');
echo Asset::render_css('theme'); 

?>
 
        <div class="slider-wrapper theme-<?php echo $options['slider_theme'];?>">
            <div id="slider" class="nivoSlider">
                 <?php foreach ($slides as $s):?>
                            <?php if($s->linkable==1): ?>
                                        <a href="<?php echo $s->link;?>" target="_blank">
                                            <img src="<?php echo site_url('files/large/'.$s->filename);?>" <?php echo ($s->showcaption==1) ? 'title="'.$s->html.'"' : '';?> alt='<?php echo $s->name;?>'<?php echo ($options['enable_thumbnail']=='true') ? 'data-thumb="'.site_url('files/thumb/'.$s->filename.'/'.$options['thumbnail_size_width']).'"' : '';?> />
                                        </a>
                            <?php else:?>
                                         <img src="<?php echo site_url('files/large/'.$s->filename);?>" <?php echo ($s->showcaption==1) ? 'title="'.$s->html.'"' : '';?> alt='<?php echo $s->name;?>'<?php echo ($options['enable_thumbnail']=='true') ? 'data-thumb="'.site_url('files/thumb/'.$s->filename.'/'.$options['thumbnail_size_width']).'"' : '';?> />
                            <?php endif;?>
                 <?php endforeach;?>
              </div>
        </div>
    
<script type="text/javascript">
  $(window).load(function() {
    $('#slider').nivoSlider({
        effect: '<?php echo $options['transition_effect'];?>', // Specify sets like: 'fold,fade,sliceDown'
        slices: <?php echo $options['slices'];?>, // For slice animations
        boxCols: <?php echo $options['box_col'];?>, // For box animations
        boxRows: <?php echo $options['box_row'];?>, // For box animations
        animSpeed: <?php echo $options['animation_speed'];?>, // Slide transition speed
        pauseTime: <?php echo $options['pause_time'];?>, // How long each slide will show
        startSlide: <?php echo $options['start_slide'];?>, // Set starting Slide (0 index)
        directionNav: <?php echo $options['direction_navigation'];?>, // Next & Prev navigation
        controlNav: <?php echo $options['control_navigation'];?>, // 1,2,3... navigation
        controlNavThumbs:<?php echo $options['enable_thumbnail'];?>, // Use thumbnails for Control Nav
        pauseOnHover: <?php echo $options['hover'];?>, // Stop animation while hovering
        prevText: 'Prev', // Prev directionNav text
        nextText: 'Next', // Next directionNav text
        randomStart: <?php echo $options['random_start'];?> // Start on a random slide
        
    });
});
</script>
