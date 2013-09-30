<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package sidebar
 * @since sidebar 1.0
 */
?>

	</div><!-- #main .site-main -->

</div><!-- #page .hfeed .site -->

<form method="get" id="searchform" class="search-overlay" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
	<a href="#" class="close-item" title="">×</a>
	<label for="s" class="assistive-text"><?php _e( 'Search', 'sidebar' ); ?></label>
	<input type="text" class="field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" id="s" placeholder="<?php esc_attr_e( 'Type something here &hellip;', 'sidebar' ); ?>" />
	<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'sidebar' ); ?>" />
</form>


<?php 
wp_footer(); 
?>

</body>
</html>