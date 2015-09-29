<!--reductions.php--><?php if ( ! empty($commandes)): ?>

	<table border="0" class="table-list" cellspacing="0">
		<thead>
			<tr>
				<th width="20"><?php echo 'N°'; ?></th>
				<th>Title</th>
				<th>code</th>
				<th>price</th>
				<th>percent</th>
				<th>rule</th>
				<th>date</th>
                                <th></th>
			</tr>
		</thead>
	
		<tfoot>
			<tr>
				<td colspan="8">
					<div class="inner"><?php $this->load->view('admin/partials/pagination') ?></div>
				</td>
			</tr>
		</tfoot>
	
		<tbody>
			<?php foreach ($commandes as $commande): ?>
				<tr>
					<td><?php echo $commande->id ?></td>
					<td>
					<?php echo $commande->title ?>
					</td>
				
					<td>
					<?php echo $commande->code ?>
					</td>
					
					<td>
					<?php echo $commande->price ?>
					</td>
				
					<td>
                                        <?php echo $commande->percent ?>
                                        </td>
				
					<td>
                                        <?php echo $commande->rule ?>
                                        </td>
				
					<td>
                                        <?php echo $commande->date ?>
                                        </td>
					
					<td class="align-center buttons buttons-small">		
						<?php echo anchor('admin/commandes/edit_reduction/'.$commande->id, lang('global:edit'), 'class="button edit"') ?>
						<?php echo anchor('admin/commandes/del_reduction/'.$commande->id, lang('global:delete'), array('class'=>'confirm button delete')) ?>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	
<?php else: ?>

	<div class="no_data"><?php echo lang('commandes:no_reductions') ?></div>

<?php endif ?>
