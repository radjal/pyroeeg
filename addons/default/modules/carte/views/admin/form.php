<!--form.php--><section class="title">
<?php if ($this->method == 'create'): ?>
	<h4><?php echo lang('carte:create_title') ?></h4>
<?php else: ?>
	<h4><?php echo sprintf(lang('carte:edit_title'), $post->title) ?></h4>
<?php endif ?>
</section>

<section class="item">
<div class="content">

<?php echo form_open_multipart() ?>

<div class="tabs">

	<ul class="tab-menu">
            <!-- custom fields tab was moved to first position -->
            <li><a href="#carte-content-tab"><span><?php echo lang('carte:content_label') ?></span></a></li>
            <li><a href="#carte-options-tab"><span><?php echo lang('carte:options_label') ?></span></a></li>
	</ul>

    
    
	<!-- Content tab -->
	<div class="form_inputs" id="carte-content-tab">
		<fieldset>
			<ul>
                                <li>
					<label for="date_carte"><?php echo lang('carte:date_carte_label') ?> <span>*</span></label>
					<div class="input"><?php echo form_input('date_carte', $post->date_carte, 'maxlength="100" class="width-20"') ?></div>
				</li>    
                                	
				<li>
					<label for="status"><?php echo lang('carte:status_label') ?></label>
					<div class="input"><?php echo form_dropdown('status', array('draft' => lang('carte:draft_label'), 'live' => lang('carte:live_label')), $post->status) ?></div>
				</li>
		


                	<?php if ($stream_fields): ?>
				<?php foreach ($stream_fields as $field) echo $this->load->view('admin/partials/streams/form_single_display', array('field' => $field), true) ?>
                	<?php endif; ?>	
	

			</ul>
		<?php echo form_hidden('preview_hash', $post->preview_hash)?>
		</fieldset>
	</div>

	<!-- Options tab -->
	<div class="form_inputs" id="carte-options-tab">
		<fieldset>
			<ul>
	
				<li>
					<label for="category_id"><?php echo lang('carte:category_label') ?></label>
					<div class="input">
					<?php echo form_dropdown('category_id', array(lang('carte:no_category_select_label')) + $categories, @$post->category_id) ?>
						[ <?php echo anchor('admin/carte/categories/create', lang('carte:new_category_label'), 'target="_blank"') ?> ]
					</div>
				</li>
	
				<?php if ( ! module_enabled('keywords')): ?>
					<?php echo form_hidden('keywords'); ?>
				<?php else: ?>
					<li>
						<label for="keywords"><?php echo lang('global:keywords') ?></label>
						<div class="input"><?php echo form_input('keywords', $post->keywords, 'id="keywords"') ?></div>
					</li>
				<?php endif; ?>
	
				<li class="date-meta">
					<label><?php echo lang('carte:date_label') ?></label>
	
					<div class="input datetime_input">
						<?php echo form_input('created_on', date('Y-m-d', $post->created_on), 'maxlength="10" id="datepicker" class="text width-20"') ?> &nbsp;
						<?php echo form_dropdown('created_on_hour', $hours, date('H', $post->created_on)) ?> :
						<?php echo form_dropdown('created_on_minute', $minutes, date('i', ltrim($post->created_on, '0'))) ?>
					</div>
				</li>
	
				<?php if ( ! module_enabled('comments')): ?>
					<?php echo form_hidden('comments_enabled', 'no'); ?>
				<?php else: ?>
					<li>
						<label for="comments_enabled"><?php echo lang('carte:comments_enabled_label');?></label>
						<div class="input">
							<?php echo form_dropdown('comments_enabled', array(
								'no' => lang('global:no'),
								'1 day' => lang('global:duration:1-day'),
								'1 week' => lang('global:duration:1-week'),
								'2 weeks' => lang('global:duration:2-weeks'),
								'1 month' => lang('global:duration:1-month'),
								'3 months' => lang('global:duration:3-months'),
								'always' => lang('global:duration:always'),
							), $post->comments_enabled ? $post->comments_enabled : '3 months') ?>
						</div>
					</li>
				<?php endif; ?>
			</ul>
		</fieldset>
	</div>

</div>

<input type="hidden" name="row_edit_id" value="<?php if ($this->method != 'create'): echo $post->id; endif; ?>" />

<div class="buttons">
	<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel'))) ?>
</div>

<?php echo form_close() ?>
<script type="text/javascript">
// some custom functions
function date2title(dateVal) {
    if(dateVal)   {
  //  $.datepicker.formatDate( 'DD-MM-yy', dateVal );
        alert(dateVal);
    }
}

var pigLatin = function(str) {
  return replace(/(\w*)([aeiou]\w*)/g, "$2$1ay");
}


function title2date(dateString) {
    if(dateString)   {
     // $('input[name="slug"]').val(dateString);
      //$( 'input[name="slug"]' ).slugify('input[name="title"]', replace(/(\w*)([aeiou]\w*)/g, "$2$1ay"));
        //generate_slug('title', 'slug');
       // alert('title2date \n ' + dateString);
        
    }
}

$( "#title" ).datepicker({ dateFormat: 'DD dd MM yy' });
$( 'input[name="date_carte"]' ).datepicker({ dateFormat: 'yy-mm-dd' });


//$( "#title" ).datepicker.setDefaults(alert('x'));
</script>
</div>
</section>