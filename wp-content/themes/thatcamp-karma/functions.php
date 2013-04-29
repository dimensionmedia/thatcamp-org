<?php
/**
 * Functions and definitions for thatcamp
 *
 * @package thatcamp
 * @since thatcamp 1.0
 */


// Check to see if BuddyPress is active, otherwise load default WordPress theme
if ( ! function_exists( 'bp_is_active' ) ) {
	switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
	return;
}


// Content width set
if ( ! isset( $content_width ) ) {
	$content_width = 640;
}

// Don't sohw the admin bar
add_filter('show_admin_bar', '__return_false');


/**
 * Sets up Theme
 *
 * Performs various theme set ups and links in files.
 *
 * Adds in:
 * - Ajax.php for BuddyPress
 * - Template Functions - a functions file for commenting and other theme related functions
 * - BuddyPress Functions - a functions file modified from BP Default theme
 *
 * Sets up:
 * - Langauges and textdomain for thatcamp
 * - Feed links
 * - Editor Style
 * - Post Thumbnails
 * - Post formats - aside, image, link, quote all supported
 * - BuddyPress buttons - add friend, messsage (private and public), join, topic, blog, visit
 *
 * Registers the following Menus:
 * - Top (In Header)
 * - Bottom (In Footer)
 * - Middle (Responsive menu)
 * - Documents (Document template pages only)
 *
 * @since thatcamp (1.0)
 */

add_action( 'after_setup_theme', 'thatcamp_build' );
if ( ! function_exists( 'thatcamp_build' ) ) :
function thatcamp_build() {

	// incs incoming
	require( get_template_directory() . '/assets/scripts/ajax.php' );

	// template contents and structure functions
	require( get_template_directory() . '/functions/template-functions.php' );

	// Language set up
	load_theme_textdomain('thatcamp', get_template_directory() . '/languages/');

	add_theme_support( 'thatcamp' );

	// Add RSS feed links
	add_theme_support('automatic-feed-links');

	// Enable support for post thumbnails
	add_theme_support('post-thumbnails');

	// Register navigation menus
	register_nav_menu('top', __('Top', 'thatcamp'));
	register_nav_menu('bottom', __('Bottom', 'thatcamp'));
	register_nav_menu('middle', __('Middle', 'thatcamp'));
	register_nav_menu('documents', __('Documents', 'thatcamp'));

	// Add post format support
	add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote' ) );

	if ( !is_admin() ) {
		// Register buttons for the relevant component templates
		// Friends button
		if ( bp_is_active( 'friends' ) )
			add_action( 'bp_member_header_actions',    'bp_add_friend_button',           5 );

		// Activity button
		if ( bp_is_active( 'activity' ) )
			add_action( 'bp_member_header_actions',    'bp_send_public_message_button',  20 );

		// Messages button
		if ( bp_is_active( 'messages' ) )
			add_action( 'bp_member_header_actions',    'bp_send_private_message_button', 20 );

		// Group buttons
		if ( bp_is_active( 'groups' ) ) {
			add_action( 'bp_group_header_actions',     'bp_group_join_button',           5 );
			add_action( 'bp_group_header_actions',     'bp_group_new_topic_button',      20 );
			add_action( 'bp_directory_groups_actions', 'bp_group_join_button' );
		}

		// Blog button
		if ( bp_is_active( 'blogs' ) )
			add_action( 'bp_directory_blogs_actions',  'bp_blogs_visit_blog_button' );
	}

	require( get_template_directory() . '/functions/buddypress-functions.php' );
}
endif;


/**
 * Loads all scripts/stylesheets used in theme
 *
 * Loads the following scripts and stylesheets:
 * - Normalise.css : resets all CSS
 * - Print.css : a minimal print stylesheet
 * - Style.css : this is generated from the files in assets/less - the theme uses LESS and auto generates the style.css
 * - Gridscript.css : this is for the avatar grid on the home page
 * - Font-awesome.css : This is an icon font that can be reused throughout the site
 * - Grid script files : transit.min.js, gridrotator.js - this is for the avatar grid on the home page
 * - Custom.js : Custom grid scripting and also custom scripting for the responsive menu
 * - Modernizr : Polyfill and shim along with CSS transitions
 *
 * It sets up:
 * - Threaded comments
 * - Favourites and other scripts for BuddyPress to work
 *
 * @since thatcamp (1.0)
 */

if ( ! function_exists( 'thatcamp_load_scripts' ) ) :
function thatcamp_load_scripts() {

	wp_enqueue_style( 'normalise',  get_template_directory_uri() . '/assets/css/normalise.css', array());

	/* bare bones print styles */
	wp_enqueue_style( 'print',  get_template_directory_uri() . '/assets/css/print.css', array());

	wp_enqueue_style( 'gridscript',  get_template_directory_uri() . '/assets/css/gridscript.css', array());

	wp_enqueue_style( 'style', get_stylesheet_uri() );

	/* font awesome is rolled into Logical Bones */
	wp_enqueue_style( 'font-awesome',  get_template_directory_uri() . '/assets/css/font-awesome.css', array(), '2.0');

	wp_enqueue_script('modernizr', get_template_directory_uri() . '/assets/scripts/modernizr-2.6.2-min.js', array("jquery"), '2.0');

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Enqueue the global JS - Ajax will not work without it
	wp_enqueue_script( 'dtheme-ajax-js', get_template_directory_uri() . '/assets/scripts/global.js', array( 'jquery' ), bp_get_version() );

	// Add words that we need to use in JS to the end of the page so they can be translated and still used.
	$params = array(
		'my_favs'           => __( 'My Favorites', 'thatcamp' ),
		'accepted'          => __( 'Accepted', 'thatcamp' ),
		'rejected'          => __( 'Rejected', 'thatcamp' ),
		'show_all_comments' => __( 'Show all comments for this thread', 'thatcamp' ),
		'show_all'          => __( 'Show all', 'thatcamp' ),
		'comments'          => __( 'comments', 'thatcamp' ),
		'close'             => __( 'Close', 'thatcamp' ),
		'view'              => __( 'View', 'thatcamp' ),
		'mark_as_fav'	    => __( 'Favorite', 'thatcamp' ),
		'remove_fav'	    => __( 'Remove Favorite', 'thatcamp' )
	);
	wp_localize_script( 'dtheme-ajax-js', 'BP_DTheme', $params );
	wp_enqueue_script('transit', get_template_directory_uri() . '/assets/scripts/jquery.transit.min.js', array("jquery"), '2.0');
	wp_enqueue_script('gridrotator', get_template_directory_uri() . '/assets/scripts/jquery.gridrotator.js', array("jquery"), '2.0');

	wp_enqueue_script('custom', get_template_directory_uri() . '/assets/scripts/custom.js', array("jquery"), '2.0');

}
endif;
add_action( 'wp_enqueue_scripts', 'thatcamp_load_scripts' );


/**
 * Sets up the widget areas
 *
 * The following widget areas are available:
 * - Sidebar (Default Sidebar)
 * - Home
 * - Stream
 * - Activity
 * - Twitter (Widget area just for Twitter Widget)
 *
 *
 * @since thatcamp (1.0)
 */
if ( ! function_exists( 'thatcamp_widgets_init' ) ) :
function thatcamp_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar', 'thatcamp'),
			'id'            => 'sidebar',
			'description'   => 'Sidebar',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
       		'before_title' => '<h3 class="widgettitle">',
       		'after_title' => '</h3>'
			)
	);

	register_sidebar(
		array(
			'name'          => __( 'Twitter Button', 'thatcamp'),
			'id'            => 'sidebar-twitter',
			'description'   => 'Sidebar twitter',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
       		'before_title' => '<h3 class="widgettitle">',
       		'after_title' => '</h3>'
			)
	);


	require( get_stylesheet_directory() . '/functions/widgets.php' );
}
endif;
add_action( 'widgets_init', 'thatcamp_widgets_init' );

/**
 * Site Search
 *
 * Drop down filterable search.
 *
 * @since thatcamp (1.0)
 */
remove_action( 'bp_init', 'bp_core_action_search_site', 7 );
function thatcamp_action_search_site() {
	if ( ! bp_is_current_component( bp_get_search_slug() ) )
		return;

	if ( empty( $_POST['search-terms'] ) ) {
		$redirect = wp_get_referer() ? wp_get_referer() : bp_get_root_domain();
		bp_core_redirect( $redirect );
		return;
	}

	$search_terms = stripslashes( $_POST['search-terms'] );
	$search_which = !empty( $_POST['search-which'] ) ? $_POST['search-which'] : '';
	$query_string = '/?s=';

	$slug = '';
	$var  = '/?s=';

	// If posts aren't displayed on the front page, find the post page's slug.
	if ( 'page' == get_option( 'show_on_front' ) ) {
		$page = get_post( get_option( 'page_for_posts' ) );

		if ( !is_wp_error( $page ) && !empty( $page->post_name ) ) {
			$slug = $page->post_name;
			$var  = '?s=';
		}
	}

	$redirect = home_url( $slug . $query_string . urlencode( $search_terms ) );

	bp_core_redirect( $redirect );
}
add_action( 'bp_init', 'thatcamp_action_search_site', 7 );

/**
 * The People page features nested forms, which are breaking the ability to
 * show a members search term as a URL GET parameter. Also, we need to have
 * a custom param key, to bypass BP's native member search
 */
function thatcamp_action_search_members() {
	if ( ! bp_is_members_component() ) {
		return;
	}

	if ( empty( $_POST['msearch'] ) ) {
		return;
	}

	bp_core_redirect( add_query_arg( 'msearch', urlencode( $_REQUEST['msearch'] ), trailingslashit( bp_get_root_domain() . '/' . bp_get_members_root_slug() ) ) );
}
add_action( 'bp_init', 'thatcamp_action_search_members', 7 );

/**
 * Filter the ajax_querystring to make the custom member search work
 */
function thatcamp_search_querystring( $qs ) {
	global $bp, $wpdb;

	if ( bp_is_members_component() && ! empty( $_GET['msearch'] ) ) {
		$search_terms = like_escape( urldecode( $_GET['msearch'] ) );

		// Find a list of matching members to pass to the 'includes' param
		// Search against: user_nicename as well as the thatcamp profile fields
		$tc_fields = function_exists( 'thatcamp_registrations_fields' ) ? thatcamp_registrations_fields( 'all' ) : array();
		$tc_fields_keys = array();
		foreach ( $tc_fields as $tc_field ) {
			if ( ! empty( $tc_field['public'] ) ) {
				$tc_fields_keys[] = "'" . $tc_field['id'] . "'";
			}
		}

		// Two separate searches, because, hey, why not
		$user_nicename_matches = $wpdb->get_col( "SELECT ID FROM $wpdb->users WHERE user_nicename LIKE '%" . $search_terms . "%'" );
		$user_meta_matches     = $wpdb->get_col( "SELECT DISTINCT user_id FROM $wpdb->usermeta WHERE meta_key IN (" . implode( ',', $tc_fields_keys ) . ") AND meta_value LIKE '%" . $search_terms . "%'" );

		// Merge and sanitize
		$user_ids = wp_parse_id_list( array_unique( array_merge( $user_nicename_matches, $user_meta_matches ) ) );

		// Convert to a query arg
		if ( ! empty( $qs ) ) {
			$qs .= '&';
		}

		$qs .= 'include=' . implode( ',', $user_ids );
	}

	return $qs;
}
add_filter( 'bp_ajax_querystring', 'thatcamp_search_querystring', 999 );

/**
 * Filter the ajax_querystring on member activity pages
 *
 * This allows the custom nav items to work
 */
function thatcamp_activity_querystring( $qs ) {
	if ( bp_is_user() && bp_is_activity_component() ) {

		$filter = '';
		switch ( thatcamp_activity_type() ) {
			case 'blog_posts' :
				$filter = 'action=new_blog_post&type=new_blog_post';
				break;

			case 'blog_comments' :
				$filter = 'action=new_blog_comment&type=new_blog_comment';
				break;

			case 'forums' :
				$filter = 'action=bbp_topic_create,bbp_reply_create&type=bbp_topic_create,bbp_reply_create';
				break;

			case 'favorites' :
				$filter = 'scope=favorites';
				break;
		}

		if ( ! empty( $qs ) ) {
			$qs .= '&';
		}

		$qs .= $filter;
	}

	return $qs;
}
add_filter( 'bp_ajax_querystring', 'thatcamp_activity_querystring', 999 );

// Gets the blog ID
function thatcamp_proceedings_blog_id() {
	global $wpdb;
	return $wpdb->get_var( "SELECT blog_id FROM $wpdb->blogs WHERE domain LIKE 'proceedings.%'" );
}

/**
 * Fixes directory titles
 */
function thatcamp_filter_title( $full_title, $title, $sep, $sep_location ) {
	if ( bp_is_groups_component() && bp_is_directory() ) {
		return 'THATCamps Directory | THATCamp';
	} else if ( bp_is_members_component() && bp_is_directory() ) {
		return 'People Directory | THATCamp';
	} else if ( bp_is_activity_component() && bp_is_directory() ) {
		return 'THATCamp Activity | THATCamp';
	} else if ( bp_displayed_user_id() ) {

		if ( bp_is_user_profile() ) {
			return str_replace( 'Extended Profiles', 'Profile', $title );
		} else if ( bp_is_user_activity() ) {
			$atype = thatcamp_activity_type();

			switch ( $atype ) {
				case 'blog_posts' :
					$tag = 'Blog Posts';
					break;
				case 'blog_comments' :
					$tag = 'Blog Comments';
					break;
				case 'forums' :
					$tag = 'Forum Posts';
					break;

				default :
					return str_replace( ' Streams', '', $title );
					break;
			}

			return str_replace( ' Streams', '', $full_title ) . ' ' . $tag;
		} else {
			return $title;
		}
	} else {
		return $full_title;
	}
}
add_filter( 'bp_modify_page_title', 'thatcamp_filter_title', 10, 4 );

/**
 * Don't let bbPress filter user profile titles
 */
function thatcamp_prevent_bbpress_title_filter() {
	if ( bp_displayed_user_id() ) {
		remove_filter( 'wp_title', 'bbp_title', 10, 3 );
	}
}
add_action( 'bp_actions', 'thatcamp_prevent_bbpress_title_filter' );

/**
 * Modify the user nav before it gets rendered, so we remove redundant items
 */
function thatcamp_mod_user_nav() {
	global $bp;

	if ( bp_is_active( 'xprofile' ) ) {
		$bp->bp_nav['profile']['name'] = 'About';
		$bp->bp_nav['profile']['position'] = 5;
	}

	if ( bp_is_active( 'blogs' ) ) {
		bp_core_remove_nav_item( 'blogs' );
	}

	if ( bp_is_active( 'messages' ) ) {
		bp_core_remove_nav_item( 'messages' );
	}

	if ( bp_is_active( 'settings' ) && is_user_logged_in() ) {
		bp_core_remove_nav_item( 'settings' );
	}

        // There's a baffling bug in BuddyPress that makes this necessary
        // I blame the Buddybar
        if ( is_user_logged_in() ) {
                $activity_base = trailingslashit( bp_displayed_user_domain() . bp_get_activity_slug() );
        } else {
                $activity_base = bp_get_activity_slug() . '/';
        }

	$bp->bp_nav['blogs'] = array(
		'name'                    => 'Blog Posts',
		'slug'                    => 'blogs',
		'link'                    => add_query_arg( 'a_type', 'blog_posts', $activity_base ),
		'css_id'                  => 'blogs',
		'show_for_displayed_user' => true,
		'position'                => 70,
		'screen_function'         => 'bp_activity_screen_my_activity',
	);

	$bp->bp_nav['comments'] = array(
		'name'                    => 'Blog Comments',
		'slug'                    => 'comments',
		'link'                    => add_query_arg( 'a_type', 'blog_comments', $activity_base ),
		'css_id'                  => 'comments',
		'show_for_displayed_user' => true,
		'position'                => 73,
		'screen_function'         => 'bp_activity_screen_my_activity',
	);

	$bp->bp_nav['forums'] = array(
		'name'                    => 'Forum Posts',
		'slug'                    => 'forums',
		'link'                    => add_query_arg( 'a_type', 'forums', $activity_base ),
		'css_id'                  => 'forums',
		'show_for_displayed_user' => true,
		'position'                => 90,
		'screen_function'         => 'bp_activity_screen_my_activity',
	);

	$bp->bp_nav['favorites'] = array(
		'name'                    => 'Favorites',
		'slug'                    => 'favorites',
		'link'                    => add_query_arg( 'a_type', 'favorites', $activity_base ),
		'css_id'                  => 'favorites',
		'show_for_displayed_user' => false,
		'position'                => 100,
		'screen_function'         => 'bp_activity_screen_my_activity',
	);

	// Cheating: Put Camps before Friends
	if ( isset( $bp->bp_nav[ bp_get_groups_slug() ] ) ) {
		$bp->bp_nav[ bp_get_groups_slug() ]['position'] = 55;
	}

	// Correct the Edit My Profile link
	if ( isset( $bp->bp_options_nav['profile']['edit'] ) ) {
		remove_filter( 'edit_profile_url', 'bp_members_edit_profile_url', 10, 3 );
		$bp->bp_options_nav['profile']['edit']['link'] = get_edit_profile_url( get_current_user_id() );
		add_filter( 'edit_profile_url', 'bp_members_edit_profile_url', 10, 3 );
	}
}
add_action( 'bp_actions', 'thatcamp_mod_user_nav', 1 );

/**
 * Get the a_type out of the $_GET
 */
function thatcamp_activity_type() {
	return isset( $_GET['a_type'] ) ? $_GET['a_type'] : '';
}

/**
 * Wrapper function to grab user data set by the TCRegistrations plugin
 *
 * We need this wrapper in some cases because the free-form text must be
 * interpolated into a string with certain requirements (such as a Twitter
 * handle or a URL). So we provide some validation for those types.
 */
function thatcamp_get_user_data( $user_id, $key ) {
	$data = get_user_meta( $user_id, $key, true );

	if ( ! empty( $data ) ) {
		switch ( $key ) {
			case 'user_twitter' :
				// Strip leading '@'
				$data = preg_replace( '/^\@/', '', $data );

				// Make sure the user didn't enter a URL
				if ( thatcamp_validate_url( $data ) ) {
					$data = preg_replace( '|^http(s)?://twitter\.com/([a-zA-Z0-9-\.]+?)(/.*)?|', '$2', $data );
				}

				break;

			case 'user_url' :
				// Use the real WP value instead
				$user = new WP_User( $user_id );
				$data = $user->user_url;
				if ( ! thatcamp_validate_url( $data ) ) {
					// Assume that http:// was left off
					$maybe_data = 'http://' . $data;
					if ( thatcamp_validate_url( $maybe_data ) ) {
						$data = $maybe_data;
					}
				}

				break;
		}
	}

	return $data;
}

/**
 * Is this a valid URL?
 */
function thatcamp_validate_url( $string ) {
	return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $string);
}

/**
 * Get the current 'tctype' view out of $_GET and sanitize
 */
function thatcamp_directory_current_view() {
	$current_view = isset( $_GET['tctype'] ) && in_array( $_GET['tctype'], array( 'alphabetical', 'past', 'upcoming' ) ) ? $_GET['tctype'] : 'alphabetical';
	return $current_view;
}

function thatcamp_directory_selector( $view ) {
	$base_url     = bp_get_root_domain() . '/' .  bp_get_groups_root_slug();
	$current_view = thatcamp_directory_current_view();

	$html  = '<li';

	if ( $view == $current_view ) {
		$html .= ' class="current"';
	}

	$html .= '>';

	if ( $view != $current_view ) {
		$html .= '<a href="' . add_query_arg( 'tctype', $view, $base_url ) . '">';
	}

	$html .= ucwords( $view );

	if ( $view != $current_view ) {
		$html .= '</a>';
	}

	$html .= '</li>';

	echo $html;
}

/**
 * Filters group directory query strings to ensure that the proper groups show
 *
 * There are three TC views: alphabetical, past, and upcoming. This ensures
 * that the queries reflect these different views.
 *
 * Note that 'upcoming' also includes TBD THATCamps. See thatcamp_add_tbd_to_upcoming()
 */
function thatcamp_filter_group_directory( $query ) {
	global $bp, $wpdb;

	if ( bp_is_groups_component() && bp_is_directory() ) {
		$current_view = thatcamp_directory_current_view();

		if ( 'alphabetical' != $current_view ) {
			// Filter by date
			$qarray = explode( ' WHERE ', $query );

			$qarray[0] .= ", {$bp->groups->table_name_groupmeta} gmd ";

			if ( 'past' == $current_view ) {
				$qarray[1]  = " gmd.group_id = g.id AND gmd.meta_key = 'thatcamp_date' AND " . $qarray[1];
				$qarray[1] = " CONVERT(gmd.meta_value, SIGNED) < UNIX_TIMESTAMP(NOW()) AND " . $qarray[1];
				$qarray[1] = preg_replace( '/ORDER BY .*? /', 'ORDER BY CONVERT(gmd.meta_value, SIGNED) ', $qarray[1] );
				$qarray[1] = preg_replace( '/(ASC|DESC)/', 'ASC', $qarray[1] );
			} else if ( 'upcoming' == $current_view ) {
				$qarray[1]  = " gmd.group_id = g.id AND gmd.meta_key = 'thatcamp_date' AND " . $qarray[1];
				$qarray[1] = " CONVERT(gmd.meta_value, SIGNED) > UNIX_TIMESTAMP(NOW()) AND " . $qarray[1];
				$qarray[1] = preg_replace( '/ORDER BY .*? /', 'ORDER BY CONVERT(gmd.meta_value, SIGNED) ', $qarray[1] );
				$qarray[1] = preg_replace( '/(ASC|DESC)/', 'ASC', $qarray[1] );
			}

			$query = implode( ' WHERE ', $qarray );
		} else {
			$query = preg_replace( '/ORDER BY .*? /', 'ORDER BY g.name ', $query );
			$query = preg_replace( '/(ASC|DESC)/', 'ASC', $query );
		}
	}

	return $query;
}
add_filter( 'bp_groups_get_paged_groups_sql', 'thatcamp_filter_group_directory' );
add_filter( 'bp_groups_get_total_groups_sql', 'thatcamp_filter_group_directory' );

/**
 * Adds TBD THATCamps to the Upcoming directory
 *
 * TBD THATCamps have to thatcamp_date groupmeta. But we still want them to
 * appear in the Upcoming directory (where thatcamp_date is greater than now).
 * We do this by appending them, alphabetically, to the end of the standard
 * Upcoming list. But this causes all sorts of issues related to pagination,
 * so we have to do a bunch of logic to reconfigure the $groups_template
 * global. This is akin to BP's do_stickies logic for forum posts.
 */
function thatcamp_add_tbd_to_upcoming( $has_groups ) {
	global $bp, $wpdb, $groups_template;

	$current_view = thatcamp_directory_current_view();

	if ( bp_is_groups_component() && bp_is_directory() && 'upcoming' == $current_view ) {
		$tbds = $wpdb->get_col( "SELECT id FROM {$bp->groups->table_name} WHERE id NOT IN ( SELECT group_id FROM {$bp->groups->table_name_groupmeta} WHERE meta_key = 'thatcamp_date' ) ORDER BY name ASC" );

		$dated_count = $groups_template->total_group_count;
		$tbds_count  = count( $tbds );

		$page_start_num = ( ( $groups_template->pag_page - 1 ) * $groups_template->pag_num ) + 1;
		$page_end_num 	= $groups_template->pag_page * $groups_template->pag_num;

		$groups_template->total_group_count += count( $tbds );

		// 1. This page has no TBDs (haven't gotten there yet)
		if ( $dated_count >= $page_end_num ) {
			// nothing to do?
			// total counts get adjusted for pagination after new groups are added

		// 2. This page is all TBDs
		} else if ( $dated_count < $page_start_num ) {
			// Find the TBD offset
			$tbd_start_num   = $page_start_num - $dated_count;
			$tbd_fetch_count = $groups_template->pag_num;
			$tbd_fetch_ids   = array_slice( $tbds, $tbd_start_num, $tbd_fetch_count );

		// 3. This page has some TBDs
		} else {
			$tbd_fetch_count = $page_end_num - $dated_count;
			$tbd_fetch_ids   = array_slice( $tbds, 0, $tbd_fetch_count );
		}

		if ( ! empty( $tbd_fetch_ids ) ) {
			remove_filter( 'bp_groups_get_paged_groups_sql', 'thatcamp_filter_group_directory' );
			remove_filter( 'bp_groups_get_total_groups_sql', 'thatcamp_filter_group_directory' );

			$tbd_groups = groups_get_groups( array(
				'type'            => 'alphabetical',
				'include'         => $tbd_fetch_ids,
			) );

			$groups_template->groups = array_merge( $groups_template->groups, $tbd_groups['groups'] );

			add_filter( 'bp_groups_get_paged_groups_sql', 'thatcamp_filter_group_directory' );
			add_filter( 'bp_groups_get_total_groups_sql', 'thatcamp_filter_group_directory' );
		}

		if ( $tbds_count + $dated_count >= $page_end_num ) {
			$groups_template->group_count = $groups_template->pag_num;
		} else {
			$groups_template->group_count = ( $dated_count + $tbds_count ) - $page_start_num;
		}

		$groups_template->pag_links = paginate_links( array(
			'base'      => add_query_arg( array( 'grpage' => '%#%', 'num' => $groups_template->pag_num ) ),
			'format'    => '',
			'total'     => ceil( (int) $groups_template->total_group_count / (int) $groups_template->pag_num ),
			'current'   => $groups_template->pag_page,
			'prev_text' => _x( '&larr;', 'Group pagination previous text', 'buddypress' ),
			'next_text' => _x( '&rarr;', 'Group pagination next text', 'buddypress' ),
			'mid_size'  => 1
		) );

		if ( ! empty( $groups_template->groups ) ) {
			$has_groups = true;
		}
	}

	return $has_groups;
}
add_filter( 'bp_has_groups', 'thatcamp_add_tbd_to_upcoming' );