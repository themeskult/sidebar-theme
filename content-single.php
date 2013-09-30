<?php
/**
 * @package sidebar
 * @since sidebar 1.0
 */

global $post;
?>


<?php if (get_post_thumbnail_id($post->ID)): ?>

	<?php 
	$url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
	?>		
	<div class="post-cover-image" style="background-image: url(<?php echo $url ?>)"></div>

<?php endif ?>

<!-- <h4 class="presentation"><?php echo bloginfo( 'name' ) ?> presents</h4> -->

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<header class="entry-header">

		<h1 class="entry-title"><?php the_title(); ?></h1>

		<div class="entry-meta">
			<?php sidebar_posted_on(); ?>   • 
			<?php echo get_estimated_time() ?> 
		</div><!-- .entry-meta -->

	</header><!-- .entry-header -->

	<div class="cover-image">
		
	</div>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'sidebar' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'sidebar' ) );
				if ( $categories_list && sidebar_categorized_blog() ) :
			?>
			<span class="cat-links">
				<?php printf( __( 'Posted in %1$s', 'sidebar' ), $categories_list ); ?>
			</span>
			<?php endif; // End if categories ?>

			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'sidebar' ) );
				if ( $tags_list ) :
			?>
			<span class="tags-links">
				<?php printf( __( 'Tagged %1$s', 'sidebar' ), $tags_list ); ?>
			</span>
			<?php endif; // End if $tags_list ?>
		<?php endif; // End if 'post' == get_post_type() ?>

		<?php edit_post_link( __( 'Edit', 'sidebar' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
