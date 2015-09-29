<!--index.php--><section class="title">
	<h4><?php echo lang('commandes:title') ?></h4>
</section>

<section class="item">
	<div class="content">
		
	<?php echo $this->load->view('admin/partials/recap_filters') ?>

	<?php echo form_open('admin/commandes/action');?>
	
		<?php echo form_hidden('redirect', uri_string()) ?>
	
		<div id="filter-stage">
		
			<?php echo $this->load->view('admin/tables/recap') ?>
		
		</div>

		<div class="table_action_buttons">
				<?php //$this->load->view('admin/partials/buttons', array('buttons' => array('delete'))) ?>
		</div>

	<?php echo form_close();?>
	
	</div>
</section>