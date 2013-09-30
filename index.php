<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package sidebar
 * @since sidebar 1.0
 */

get_header(); 
?>
<?php get_sidebar(); ?>


		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>


					<?php get_template_part( 'content', 'single' ); ?>


					<?php
					$withcomments = 1; 
						// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || '0' != get_comments_number())
							comments_template( '', true );
					?>

					<?php break; ?>

				<?php endwhile; // end of the loop. ?>


			<?php else : ?>

				<?php get_template_part( 'no-results', 'index' ); ?>

			<?php endif; ?>

			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->

<?php get_footer(); ?>