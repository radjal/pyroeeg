<?php if ($comments): ?>
	
	<?php foreach ($comments as $item): ?>
		
		<div class="comment">
			<div class="details">

				<div class="content">
                                    <p class="comment-info">
                                        <span class="date"><?php echo format_date($item->created_on) ?></span> par 
                                        <span class="name"><?php echo strip_tags($item->user_name) ?></span> 
                                    </p>
					<?php if (Settings::get('comment_markdown') and $item->parsed): ?>
						<?php echo $item->parsed ?>
					<?php else: ?>
						<p><?php echo nl2br($item->comment) ?></p>
					<?php endif ?>
				</div>
			</div>
		</div><!-- close .comment -->
	<?php endforeach ?>
	
<?php else: ?>
	<p><?php echo lang('comments:no_comments') ?></p>
<?php endif ?>