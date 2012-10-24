<?php

/**
 * BuddyPress - Groups Directory
 *
 * @package logicalbones
 * @since logicalbones 1.0
 */

get_header( 'thatcamp' ); ?>

	<?php do_action( 'bp_before_directory_groups_page' ); ?>

	
	<div id="primary" class="main-content">
	<div id="content" role="main">

		<?php do_action( 'bp_before_directory_groups' ); ?>
		
		<form action="" method="post" id="groups-directory-form" class="dir-form">

			<h3><?php _e( 'Groups Directory', 'thatcamp' ); ?><?php if ( is_user_logged_in() && bp_user_can_create_groups() ) : ?> &nbsp;<a class="button" href="<?php echo trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/create' ); ?>"><?php _e( 'Create a Group', 'thatcamp' ); ?></a><?php endif; ?></h3>

			<?php do_action( 'bp_before_directory_groups_content' ); ?>
			<!--
			<div id="group-dir-search" class="dir-search" role="search">

				<?php bp_directory_groups_search_form(); ?>

			</div>--><!-- #group-dir-search -->
		
			<?php do_action( 'template_notices' ); ?>
			<!--
			<div class="item-list-tabs" role="navigation">
				<ul>
					<li class="selected" id="groups-all"><a href="<?php echo trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() ); ?>"><?php printf( __( 'All Groups <span>%s</span>', 'thatcamp' ), bp_get_total_group_count() ); ?></a></li>

					<?php if ( is_user_logged_in() && bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) : ?>

						<li id="groups-personal"><a href="<?php echo trailingslashit( bp_loggedin_user_domain() . bp_get_groups_slug() . '/my-groups' ); ?>"><?php printf( __( 'My Groups <span>%s</span>', 'thatcamp' ), bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ); ?></a></li>

					<?php endif; ?>

					<?php do_action( 'bp_groups_directory_group_filter' ); ?>

				</ul>
			</div>

			<div class="item-list-tabs" id="subnav" role="navigation">
				<ul>

					<?php do_action( 'bp_groups_directory_group_types' ); ?>

					<li id="groups-order-select" class="last filter">

						<label for="groups-order-by"><?php _e( 'Order By:', 'thatcamp' ); ?></label>
						<select id="groups-order-by">
							<option value="active"><?php _e( 'Last Active', 'thatcamp' ); ?></option>
							<option value="popular"><?php _e( 'Most Members', 'thatcamp' ); ?></option>
							<option value="newest"><?php _e( 'Newly Created', 'thatcamp' ); ?></option>
							<option value="alphabetical"><?php _e( 'Alphabetical', 'thatcamp' ); ?></option>

							<?php do_action( 'bp_groups_directory_order_options' ); ?>

						</select>
					</li>
				</ul>
			</div>
			-->
			<div id="groups-dir-list" class="groups dir-list">

				<?php get_template_part( 'groups/groups', 'loop'); ?>

			</div>

			<?php do_action( 'bp_directory_groups_content' ); ?>

			<?php wp_nonce_field( 'directory_groups', '_wpnonce-groups-filter' ); ?>

			<?php do_action( 'bp_after_directory_groups_content' ); ?>

		</form>

		<?php do_action( 'bp_after_directory_groups' ); ?>

		</div>
	</div>

	<?php do_action( 'bp_after_directory_groups_page' ); ?>

<?php get_sidebar( 'buddypress' ); ?>
<?php get_footer( 'thatcamp' ); ?>

