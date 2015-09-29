<?php if ($commandes): ?>
	
	<?php foreach ($commandes as $item): ?>
		
		<div class="commande">
			<div class="image">
				<?php echo gravatar($item->user_email, 60) ?>
			</div>
			<div class="details">
				<div class="name">
					<?php echo $item->user_name ?>
				</div>
				<div class="date">
					<p><?php echo format_date($item->created_on) ?></p>
				</div>
				<div class="content">
					<?php if (Settings::get('commande_markdown') and $item->parsed): ?>
						<?php echo $item->parsed ?>
					<?php else: ?>
						<p><?php echo nl2br($item->message) ?></p>
					<?php endif ?>
				</div>
			</div>
		</div><!-- close .commande -->
	<?php endforeach ?>
	
<?php else: ?>
	<p><?php echo lang('commandes:no_commandes') ?></p>
<?php endif ?>