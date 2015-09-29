<section class="title">
    <h4><?php echo lang('pm:sliders_label') ?></h4>
</section>
<section class="item">
    <div class="content">
        <?php if(!empty($sliders)): ?>
	<?php echo form_open('admin/pmaker/action', 'class="crud"') ?>
	<table border="0" class="table-list faq-list">
        <thead>
                <tr>
                        <th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
                        <th><?php echo lang('global:title') ?></th>
                        <th><?php echo lang('global:slug') ?></th>
                        <th class="width-5"><?php echo lang('pm:creation_date_label') ?></th>
                        <th class="width-10"><span><?php echo lang('pm:actions_label');?></span></th>
                </tr>
        </thead>
        <tfoot>
                <tr>
                        <td colspan="5">
                                <div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
                        </td>
                </tr>
        </tfoot>
        <tbody>
            <?php foreach($sliders as $f): ?>
            <tr>
                <td class="action-to"><?php echo form_checkbox('action_to[]', $f->id) ?></td>
                <td><?php echo $f->title; ?></td>
                <td><?php echo $f->slug; ?></td>
                <td><?php echo format_date($f->creation_date); ?></td>
                <td class="buttons buttons-small">
                    <?php echo anchor('admin/pmaker/settings/'.$f->id, lang('pm:settings_label'), 'rel="ajax" class="btn orange edit button"'); ?>
                    <?php echo anchor('admin/pmaker/slides/'.$f->id, lang('pm:slide_label'), 'rel="ajax" class="btn blue edit button"'); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
	</table>
	<div class="table_action_buttons">
        <?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
	</div>
	<?php echo form_close(); ?>
<?php else: ?>
    <div class="no_data">
        <?php echo lang('pm:no_slider');?>
    </div>
<?php endif; ?>
    </div>

</section>
