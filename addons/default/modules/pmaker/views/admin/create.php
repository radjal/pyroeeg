
<section class="title">
        <h4><?php echo lang('pm:create_slider');?></h4>
</section>

<section class="item">
    <div class="content">
        <?php echo form_open(uri_string(), 'id="page-form"'); ?>
        <div class="tabs">
            <ul class="tab-menu">
                    <li><a href="#pm-general"><span><?php echo lang('pm:general_label');?></span></a></li>
                    <li><a href="#pm-settings"><span><?php echo lang('pm:settings_label');?></span></a></li>
            </ul>
            
            <!-- General tab -->
            <div class="form_inputs" id="pm-general">
                <fieldset>
                    <ul>
                        <li>
                            <label for="title"><?php echo lang('global:title');?></label>
                            <?php echo form_input('title', '' , 'id="title" maxlength="60"'); ?>
                            <span class="required-icon tooltip"><?php echo lang('required_label'); ?></span>
                        </li>
                        <li class="even">
                                <label for="slug"><?php echo lang('global:slug'); ?></label>
                                <?php echo form_input('slug', '', 'class="new-area-slug"'); ?>
                                <span class="required-icon tooltip"><?php echo lang('required_label'); ?></span>
                        </li>
                    </ul>
                </fieldset>
            </div>
            
            <!-- Settings tab -->
            <div class="form_inputs" id="pm-settings">
                <fieldset>
                    <ul>
                        <li>
				<label for="jquery"><?php echo lang('pm:jquery_label'); ?></label>
				<?php echo form_dropdown('jquery', array('No' => lang('pm:no_label'), 'yes' => lang('pm:yes_label')),'No') ?>
			</li>
                        
                    </ul>
                </fieldset>
            </div>

                
        </div>
        <div class="buttons float-right padding-top">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
	</div>

	<?php echo form_close(); ?>
    </div> 
</section>

