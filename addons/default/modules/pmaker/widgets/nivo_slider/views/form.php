<ol>
    <fieldset>
        <legend>Slider Settings</legend>
        <li class="<?php echo alternator('even', ''); ?>">
		<label>Choose Slider:</label>
		<?php echo form_dropdown('slider', $slider_options, $options['slider']); ?>
	</li>
    </fieldset>
        
    <fieldset>
        <legend>Effects Settings</legend>
        
        <!--li class="<?php #echo alternator('even', ''); ?>">
		<label>orientation:</label>
		<?php #echo form_input('orientation', $options['orientation']); ?>
                <br/>(v)ertical, (h)orizontal or (r)andom
	</li-->
        <li class="<?php echo alternator('even', ''); ?>">
		<label>Slider theme:</label>
		<?php echo form_dropdown('slider_theme',array('default'=>'default','dark'=>'dark','light'=>'light','bar'=>'bar'), $options['slider_theme']); ?>
              
	</li>
             
        <li class="<?php echo alternator('even', ''); ?>">
		<label>Transition Effect:</label>
		<?php echo form_dropdown('transition_effect',$transitions, $options['transition_effect']); ?>
                
               
	</li>
        
        <li class="<?php echo alternator('even', ''); ?>">
		<label>Slices:</label>
		<?php echo form_input('slices', $options['slices']); ?>
                <br/>The number of slices to use in the "Slice" transitions (eg 15)
	</li>
        
        <li class="<?php echo alternator('even', ''); ?>">
		<label>Box (Cols x Rows):</label><br/>
		<?php echo form_input('box_col', $options['box_col']); ?>
                <label>x</label>
		<?php echo form_input('box_row', $options['box_row']); ?>
                <br/>The number of columns and rows to use in the "Box" transitions (eg 8 x 4)
	</li>
        
        <li class="<?php echo alternator('even', ''); ?>">
		<label>Animation Speed:</label>
		<?php echo form_input('animation_speed', $options['animation_speed']); ?>
                <br/>The speed of the transition animation in milliseconds (eg 500)

	</li>
        
        <li class="<?php echo alternator('even', ''); ?>">
		<label>Enable Thumbnail Navigation:</label>
		<?php echo form_dropdown('enable_thumbnail', array('false'=>'No','true'=>'Yes'),$options['enable_thumbnail']); ?>
                <br/><strong>Note: </strong>Control Navigation must be enabled to show thumbs
	</li>
        
        <li class="<?php echo alternator('even', ''); ?>">
		<label>Thumbnail Size:</label><br/>
		<?php echo form_input('thumbnail_size_width', $options['thumbnail_size_width']); ?>
                <br/>The width of the thumbnails.Height will be set automatically

	</li>
        
        <li class="<?php echo alternator('even', ''); ?>">
		<label>Pause Time:</label>
		<?php echo form_input('pause_time', $options['pause_time']); ?>
                <br/>The amount of time to show each slide in milliseconds (eg 3000)

	</li>
        
        <li class="<?php echo alternator('even', ''); ?>">
		<label>Start Slide:</label>
		<?php echo form_input('start_slide', $options['start_slide']); ?>
                <br/>Set which slide the slider starts from (zero based index: usually 0)

	</li>
        <li class="<?php echo alternator('even', ''); ?>">
		<label>Random start:</label>
		<?php echo form_dropdown('random_start',array('false'=>'No','true'=>'Yes'), $options['random_start']); ?>
                <br/> Overrides Start Slide value

	</li>
        
        <li class="<?php echo alternator('even', ''); ?>">
		<label>Enable Direction Navigation:</label>
		<?php echo form_dropdown('direction_navigation',array('false'=>'No','true'=>'Yes'), $options['direction_navigation']); ?>
                <br/>Prev & Next arrows
	</li>
        
        <li class="<?php echo alternator('even', ''); ?>">
		<label>Enable Control Navigation:</label>
		<?php echo form_dropdown('control_navigation',array('false'=>'No','true'=>'Yes'), $options['control_navigation']); ?>
                <br/>eg 1,2,3...
	</li>
        
        
        <li class="<?php echo alternator('even', ''); ?>">
		<label>Pause the Slider on Hover:</label>
		<?php echo form_dropdown('hover',array('false'=>'No','true'=>'Yes'), $options['hover']); ?>
        </li>

    </fieldset>

</ol>