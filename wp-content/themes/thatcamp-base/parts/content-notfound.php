<?php
/**
 * Single post view
 *
 * @package thatcampbase
 * @since thatcampbase 1.0
 */
?>
<article id="post-0" class="post no-results not-found">
	<header class="post-header">
		<h2 class="post-title"><?php _e( 'Nothing Found', 'thatcampbase'); ?></h2>
	</header>
	<div class="post-body">
		<?php if ( is_home() ) { ?>
			<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'thatcampbase' ), admin_url( 'post-new.php' ) ); ?></p>
			<?php } elseif ( is_search() ) { ?>
			<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'thatcampbase' ); ?></p>
			<?php get_search_form(); ?>
			<?php } else { ?>
			<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'thatcampbase' ); ?></p>
			<?php get_search_form(); ?>
		<?php } ?>
	</div>
</article>
