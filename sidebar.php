<div id="sidebar" class="nano">
	<ul class="sidebar-posts content">
	<?php if (is_front_page()){$is_front_page = 1;} ?>
	<?php if ( have_posts() ) : ?>

		<?php $post_id = get_the_ID() ?>

		<?php query_posts('posts_per_page=10000000') ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<li class="<?php if ($post_id == get_the_ID()): ?> current-post <?php endif ?>">
				<a href="<?php echo the_permalink() ?>">
					<span class="title"><?php echo the_title() ?></span>
					<span class="date"><?php echo get_the_date() ?></span>
					<span class="words">  &#8226; <?php echo str_word_count(get_the_content()) ?> words </span>
				</a>
			</li>

		<?php endwhile; ?>

	<?php else : ?>

		<li>No posts.</li>
		
	<?php endif; ?>	
	</ul>


</div>