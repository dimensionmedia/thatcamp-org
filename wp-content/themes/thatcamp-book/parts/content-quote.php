<?php
/**
 * Content display for posts - default template
 *
 * @package bookcamp
 * @since bookcamp 1.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-body">
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'bookcamp') ); ?>
	</div>
	<footer class="post-meta">
		<div class="post-edit">
			<?php edit_post_link( __( 'Edit &rarr;', 'bookcamp'), ' <span class="edit-link">', '</span> | ' ); ?>
			<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'bookcamp'), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php _e( 'Permalink', 'bookcamp'); ?></a>
		</div>
	</footer>
</article>