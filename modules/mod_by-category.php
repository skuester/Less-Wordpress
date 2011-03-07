<?php 
$cats = get_categories(array(
	'order_by'=> 'name',
	'order' => 'ASC'
	));
?>
<ul class="text top-space article-list">
<?php foreach ($cats as $c): ?>
	<li><a href="<?php echo get_category_link($c->term_id); ?>" title="<?php echo $c->name; ?>"><?php echo $c->name; ?></a></li>
<?php endforeach; ?>
</ul>