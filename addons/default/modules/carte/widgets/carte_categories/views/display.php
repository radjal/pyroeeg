<?php if (is_array($categories)): ?>
<ul>
	<?php foreach ($categories as $category): ?>
	<li>
		<?php echo anchor("carte/category/{$category->slug}", $category->title) ?>
	</li>
	<?php endforeach ?>
</ul>
<?php endif ?>
