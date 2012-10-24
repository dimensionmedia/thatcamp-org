<?php

/**
 * BuddyPress - Users Groups
 *
 * @package logicalbones
 * @since logicalbones 1.0
 */

?>

<div class="item-list-tabs no-ajax" id="subnav" role="navigation">
	<ul>
		<?php if ( bp_is_my_profile() ) bp_get_options_nav(); ?>

		<?php if ( !bp_is_current_action( 'invites' ) ) : ?>

			<li id="groups-order-select" class="last filter">

				<label for="groups-sort-by"><?php _e( 'Order By:', 'thatcamp' ); ?></label>
				<select id="groups-sort-by">
					<option value="active"><?php _e( 'Last Active', 'thatcamp' ); ?></option>
					<option value="popular"><?php _e( 'Most Members', 'thatcamp' ); ?></option>
					<option value="newest"><?php _e( 'Newly Created', 'thatcamp' ); ?></option>
					<option value="alphabetical"><?php _e( 'Alphabetical', 'thatcamp' ); ?></option>

					<?php do_action( 'bp_member_group_order_options' ); ?>

				</select>
			</li>

		<?php endif; ?>

	</ul>
</div>

<?php

if ( bp_is_current_action( 'invites' ) ) :
	get_template_part( 'members/single/groups/invites'); 

else :
	do_action( 'bp_before_member_groups_content' ); ?>

	<div class="groups mygroups">

		<?php get_template_part( 'groups/groups', 'loop');  ?>

	</div>

	<?php do_action( 'bp_after_member_groups_content' ); ?>

<?php endif; ?>
