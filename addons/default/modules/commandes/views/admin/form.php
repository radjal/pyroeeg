<!-- form.php --><section class="title">
	<h4><?php echo sprintf(lang('commandes:edit_title'), $commande->id) ?></h4>
</section>

	<section class="item">
	<div class="content">
	<?php echo form_open($this->uri->uri_string(), 'class="form_inputs"') ?>

		<?php echo form_hidden('user_id', $commande->user_id) ?>
		<?php echo form_hidden('active', $commande->is_active) ?>

		<ul class="fields">

			<li>
				<label for="telephone"><?php echo lang('commandes:telephone_label') ?>:</label><br />
				<?php echo form_input(array('name'=>'telephone', 'value' => $commande->telephone)) ?>
			</li>

			<li>
				<label for="heure_livraison"><?php echo lang('commandes:heure_livraison_label') ?>:</label><br />
				<?php echo form_input(array('name'=>'heure_livraison', 'value' => $commande->heure_livraison)) ?>
			</li>

			<li>
				<label for="info_acces"><?php echo lang('commandes:info_acces_label') ?>:</label><br />
				<?php echo form_input(array('name'=>'info_acces', 'value' => $commande->info_acces)) ?>
			</li>

			<li>
				<label for="company"><?php echo lang('commandes:info_company_label') ?>:</label><br />
				<?php echo form_input(array('name'=>'company', 'value' => $commande->company)) ?>
			</li>

			<li>
				<label for="info_payment"><?php echo lang('commandes:form_info_paiement_label') ?>:</label><br />
				<?php echo form_input(array('name'=>'info_payment', 'value' => $commande->info_payment)) ?>
			</li>

			<li>
				<label for="adresse_livraison"><?php echo lang('commandes:address_label') ?>:</label><br />
				<?php echo form_textarea(array('name'=>'adresse_livraison', 'value' => $commande->adresse_livraison, 'rows' => 5)) ?>
			</li>

			<li>
				<label for="message"><?php echo lang('commandes:message_label') ?>:</label><br />
				<?php echo form_textarea(array('name'=>'message', 'value' => $commande->message, 'rows' => 5)) ?>
			</li>

			<li>
				<label for="commande"><?php echo lang('commandes:commande_label') ?>:</label><br />
				<?php echo form_textarea(array('name'=>'commande', 'value' => $commande->commande, 'rows' => 5)) ?>
			</li>
            
		</ul>

	<?php if ($stream_fields): ?>

	<div class="form_inputs" id="blog-custom-fields">
		<fieldset>
			<ul>

				<?php foreach ($stream_fields as $field) echo $this->load->view('admin/partials/streams/form_single_display', array('field' => $field), true) ?>

			</ul>
		</fieldset>
	</div>

	<?php endif; ?>
		<div class="buttons float-right padding-top">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )) ?>
		</div>

	<?php echo form_close() ?>
	</div>
</section>