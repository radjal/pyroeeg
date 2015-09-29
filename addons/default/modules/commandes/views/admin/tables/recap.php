<!--recap.php--><?php if ( ! empty($commandes)): //die(print_r($commandes)) ?>

	<table border="0" class="table-list" cellspacing="0">
		<thead>
			<tr>
				<th width="10"><a href="admin/commandes/recap?sortby=cid&<?php echo $reversesortorder ?>">N°</a></th>
				<th>Carte</th>
				<th>User Id</th>
				<th>Pseudo</th>
				<th>Prénom Nom</th>
				<th>Email</th>
				<th>Heure</th>
				<th>Adresse</th>
				<th>Paiement</th>
				<th>Accès</th>
				<th>Téléphone</th>
				<th>Société</th>
				<th>Total HT</th>
				<th>Total TTC</th>
            </tr>
		</thead>
	
		<tfoot>
			<tr>
				<td colspan="14">
					<div class="inner"><?php $this->load->view('admin/partials/pagination') ?></div>
				</td>
			</tr>
		</tfoot>
	
		<tbody>
			<?php foreach ($commandes as $commande): ?>
				<tr>
					<td><?php echo $commande->cid ?></td>
					<td><?php echo $commande->entry_title ?></td>
					<td><?php echo $commande->user_id ?></td>
					<td><?php echo $commande->display_name ?></td>				
					<td><?php echo $commande->first_name ?> <?php echo $commande->last_name ?></td>				
					<td><?php echo $commande->email ?></td>
					<td><?php echo $commande->heure_livraison ?></td>				
					<td><?php echo $commande->adresse_livraison ?></td>					
					<td><?php echo $commande->info_payment ?></td>				
					<td><?php echo $commande->info_acces ?></td>					
					<td><?php echo $commande->telephone ?></td>
					<td><?php echo $commande->company ?></td>
					<td><?php echo $commande->total_taxfree ?></td>
					<td><?php echo $commande->total ?></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	
<?php else: ?>

	<div class="no_data"><?php echo lang('commandes:no_commandes') ?></div>

<?php endif ?>
