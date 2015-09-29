<!--preview.php--><div id="commande-preview">

	<p class="width-two-thirds float-left spacer-bottom-half">
		<strong><?php echo lang('commandes:posted_label') ?>:</strong> <?php echo format_date($commande->created_on)?><br/>
		<strong><?php echo lang('commandes:from_label') ?>:</strong> <?php echo $commande->user_name ?>
	</p>

	<div class="float-right spacer-right buttons buttons-small">
		<?php if ($commande->is_active): ?>
			<?php echo anchor('admin/commandes/unapprove/'.$commande->id, lang('global:unapprove'), 'class="button"') ?>
		<?php else:?>
			<?php echo anchor('admin/commandes/approve/'.$commande->id, lang('global:approve'), 'class="button"') ?>
		<?php endif?>
		<?php echo anchor('admin/commandes/printout/'.$commande->id, lang('commandes:print_commande_label'), 'target="_blank" class="button"' )?>
		<!--<?php echo anchor('admin/commandes/delete/'.$commande->id, lang('global:delete'), 'class="button"')?>-->
	</div>

	<hr class="clear-both" />

	<p>Livraison :<br/>
            Utilisateur : <?php echo ucfirst($commande->first_name) ." ". strtoupper($commande->last_name) ." ( ". $commande->username ." )" ?>
            <br/>
            Heure : <?php echo $commande->heure_livraison ?>
            <br/>
            Paiement : <?php echo $commande->info_payment ?>
            <br/>
            Adresse : <?php echo $commande->adresse_livraison ?>
            <br/>
            Accès : <?php echo $commande->info_acces ?>
            <br/>
            Téléphone : <?php echo $commande->telephone ?>
            <br/>
            Société : <?php echo $commande->company ?>
	</p>

	<p>Commande :  <br/>
	<?php 	 
            echo $commande_html  ;
        ?></p>

	<p>Message :<br/>
	<?php echo (Settings::get('commande_markdown') and $commande->parsed != '') ? $commande->parsed : nl2br($commande->message) ?></p>

</div>