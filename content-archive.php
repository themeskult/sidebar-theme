<?php
/**
 * @package sidebar
 * @since sidebar 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	

	<header class="entry-header">

		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'sidebar' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

		<div class="entry-meta">
			<?php sidebar_posted_on(); ?>
		</div><!-- .entry-meta -->

	</header><!-- .entry-header -->

</article><!-- #post-<?php the_ID(); ?> -->
