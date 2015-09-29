<!-- form.php --><section class="title">
	<h4><?php echo sprintf(lang('commandes:reduction_create_title'), $rcode->id) ?></h4>
</section>

	<section class="item">
	<div class="content">
	<?php echo form_open($this->uri->uri_string(), 'class="form_inputs"') ?>

		<ul class="fields">

		<?php echo form_hidden('id', $rcode->id) ?>
			
			<li>
				<label for="is_active">is_active :</label><br />
				<?php //echo form_input(array('name'=>'is_active', 'value' => $rcode->is_active)) ?>
				<?php echo form_dropdown('is_active', array(0 =>"Désactivée", 1 => "Activée"), (int) $rcode->is_active ) ?>
			</li>
			
			<li>
				<label for="use_once">use_once :</label><br />
				<?php //echo form_input(array('name'=>'use_once', 'value' => $rcode->use_once)) ?>
				<?php echo form_dropdown('use_once', array(0 =>"Réduction additionable", 1 => "Réduction unique"), (int) $rcode->use_once ) ?>
			</li>

			<li>
				<label for="title">title:</label><br />
				<?php echo form_input(array('name'=>'title', 'value' => $rcode->title)) ?>
			</li>
                        
			<li>
				<label for="code">code:</label><br />
				<?php echo form_input(array('name'=>'code', 'value' => $rcode->code)) ?>
			</li>
                        
			<li>
				<label for="date">Date :</label><br />
				<?php echo form_input(array('name'=>'date', 'value' => $rcode->date)) ?>
			</li>
			
			<li>
				<label for="heure_livraison">Montant de la réduction :</label><br />
				<?php echo form_input(array('name'=>'price', 'value' => $rcode->price)) ?>
			</li>
          
			<li>
				<label for="percent">Pourcentage de la réduction :</label><br />
				<?php echo form_input(array('name'=>'percent', 'value' => $rcode->percent)) ?>
			</li>
          
			<li>
				<label for="rule">Règle (eval PHP, doit retourner vrai ou faux) :</label><br />
				<?php echo form_input(array('name'=>'rule', 'value' => $rcode->rule)) ?>
			</li>
          
		</ul>

		<div class="buttons float-right padding-top">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )) ?>
		</div>

	<?php echo form_close() ?>
	</div>
</section>