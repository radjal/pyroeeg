<section class="title">
	<h4><?php echo lang('order:order'); ?></h4>
</section>

<section class="item">
<div class="content">

	<?php if ($orders['total'] > 0): ?>
	
		<table class="table" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo lang('order:question'); ?></th>
					<th><?php echo lang('order:answer'); ?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($orders['entries'] as $order): ?>
				<tr>
					<td><?php echo $order['question']; ?></td>
					<td><?php echo $order['answer']; ?></td>
					<td class="actions"><?php echo anchor('admin/order/edit/' . $order['id'], lang('global:edit'), 'class="button edit"'); ?>
                                            <?php echo anchor('admin/order/delete/' . $order['id'], lang('global:delete'), array('class' => 'confirm button delete')); ?>
                                        </td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
		<?php echo $orders['pagination']; ?>
		
	<?php else: ?>
		<div class="no_data"><?php echo lang('order:no_orders'); ?></div>
	<?php endif;?>
	
</div>
</section>