<!--commandes.php--><?php if ( ! empty($commandes)): //die(print_r($commandes)) ?>

	<table border="0" class="table-list" cellspacing="0">
		<thead>
			<tr>
				<th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')) ?></th>
				<th width="20"><?php echo 'N°'; ?></th>
				<th><?php echo lang('commandes:heure_livraison_label') ?> / <?php echo lang('commandes:address_label') ?></th>
				<th><?php echo lang('commandes:commande_module_label') ?></th>
				<th><?php echo lang('global:author') ?></th>
				<th><?php echo lang('commandes_active.date_label') ?></th>
				<th></th>
			</tr>
		</thead>
	
		<tfoot>
			<tr>
				<td colspan="7">
					<div class="inner"><?php $this->load->view('admin/partials/pagination') ?></div>
				</td>
			</tr>
		</tfoot>
	
		<tbody>
			<?php foreach ($commandes as $commande): ?>
				<tr>
					<td><?php echo form_checkbox('action_to[]', $commande->id) ?></td>
					<td><?php echo $commande->id ?></td>
					<td>
						<a href="<?php echo site_url('admin/commandes/preview/'.$commande->id) ?>" rel="modal" target="_blank">
						<?php echo $commande->adresse_livraison ; ?> / <?php echo $commande->heure_livraison ; ?> 
						</a>
					</td>
				
					<td>
						<?php echo anchor($commande->cp_uri ? $commande->cp_uri : $commande->uri, $commande->entry_title ? $commande->entry_title : '#'.$commande->entry_id) ?>
					</td>
					
					<td>
						<?php if ($commande->user_id > 0): ?>
							<?php echo anchor('admin/users/edit/'.$commande->user_id, user_displayname($commande->user_id, false)) ?>
						<?php else: ?>
							<?php echo mailto($commande->user_email, $commande->user_name) ?>
						<?php endif ?>
					</td>
				
					<td><?php echo format_date($commande->created_on, "%a %d %b %Y") ?></td>
					
					<td class="align-center buttons buttons-small">
						<?php if ($this->settings->moderate_commandes): ?>
							<?php if ($commande->is_active): ?>
								<?php echo anchor('admin/commandes/unapprove/'.$commande->id, lang('buttons:deactivate'), 'class="button deactivate"') ?>
							<?php else: ?>
								<?php echo anchor('admin/commandes/approve/'.$commande->id, lang('buttons:activate'), 'class="button activate"') ?>
							<?php endif ?>
						<?php endif ?>
					
						<?php echo anchor('admin/commandes/edit/'.$commande->id, lang('global:edit'), 'class="button edit"') ?>
						<?php echo anchor('admin/commandes/delete/'.$commande->id, lang('global:delete'), array('class'=>'confirm button delete')) ?>
						<?php  //echo anchor('admin/commandes/report/'.$commande->id, 'Report', array('class'=>'button edit')) ?>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	
<?php else: ?>

	<div class="no_data"><?php echo lang('commandes:no_commandes') ?></div>

<?php endif ?>
