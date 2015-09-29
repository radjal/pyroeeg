<!-- filters.php --><fieldset id="filters">

	<legend><?php echo lang('global:filters') ?></legend>
	
		<?php echo form_open('') ?>
                    <?php echo form_hidden('f_module', $module_details['slug']) ?>
		<ul>
			<?php if (Settings::get('moderate_commandes')): ?>
			<li>
					<?php echo lang('commandes:status_label', 'f_active') ?>
					<?php echo form_dropdown('f_active', array(0 =>lang('commandes:inactive_title'), 1 => lang('commandes:active_title')), (int) $commandes_active) ?>
    			</li>
	
			<?php endif ?>

			<li>
				<label for="f_keywords"><?php echo lang('global:keywords') ?></label>
				<?php echo form_input('f_keywords', '') ?>
			</li>                     
                        
			<li>
                            <?php // echo lang('commandes:module_label', 'module_slug') ?>
                            <?php //echo form_dropdown('module_slug', array(0 => lang('global:select-all')) + $module_list) ?>
                        </li>
	
			<li><?php echo anchor(current_url() . '#', lang('buttons:cancel'), 'class="button cancel"') ?></li>
                        
			<li><?php //echo anchor(current_url() . '/recap', lang('commandes:recap_title'), 'class="button"') ?></li>
		</ul>
		
		<?php echo form_close() ?>
	
</fieldset>
