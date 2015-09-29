<ul class="navigation">
	<?php foreach($carte_widget as $post_widget): ?>
		<li><?php echo anchor('carte/'.date('Y/m', $post_widget->created_on) .'/'.$post_widget->slug, $post_widget->title) ?></li>
	<?php endforeach ?>
</ul>