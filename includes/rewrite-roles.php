<?php
/**
 * handle custom page
 * do flush if changing rule, then reload the admin page
 *
 * @package BuddyForms
 * @since 0.3 beta
 */

add_action( 'init', 'buddyforms_attached_page_rewrite_rules' );
/**
 * @param bool $flush_rewrite_rules
 */
function buddyforms_attached_page_rewrite_rules( $flush_rewrite_rules = false ) {
	global $buddyforms;

	if ( ! $buddyforms ) {
		return;
	}

	foreach ( $buddyforms as $key => $buddyform ) {
		if ( isset( $buddyform['attached_page'] ) ) {
			$post_data = get_post( $buddyform['attached_page'], ARRAY_A ); // todo: remove this query and make the post_name available in the $buddyforms
			add_rewrite_rule( $post_data['post_name'] . '/create/([^/]+)/([^/]+)/?', 'index.php?pagename=' . $post_data['post_name'] . '&bf_action=create&bf_form_slug=$matches[1]&bf_parent_post_id=$matches[2]', 'top' );
			add_rewrite_rule( $post_data['post_name'] . '/create/([^/]+)/?', 'index.php?pagename=' . $post_data['post_name'] . '&bf_action=create&bf_form_slug=$matches[1]', 'top' );
			add_rewrite_rule( $post_data['post_name'] . '/view/([^/]+)/?', 'index.php?pagename=' . $post_data['post_name'] . '&bf_action=view&bf_form_slug=$matches[1]', 'top' );
			add_rewrite_rule( $post_data['post_name'] . '/edit/([^/]+)/([^/]+)/?', 'index.php?pagename=' . $post_data['post_name'] . '&bf_action=edit&bf_form_slug=$matches[1]&bf_post_id=$matches[2]', 'top' );
			add_rewrite_rule( $post_data['post_name'] . '/revision/([^/]+)/([^/]+)/([^/]+)/?', 'index.php?pagename=' . $post_data['post_name'] . '&bf_action=revision&bf_form_slug=$matches[1]&bf_post_id=$matches[2]&bf_rev_id=$matches[3]', 'top' );
		}
	}
	if ( $flush_rewrite_rules ) {
		flush_rewrite_rules();
	}

	do_action( 'buddyforms_after_attache_page_rewrite_rules', $flush_rewrite_rules );
}

/**
 * add the query vars
 *
 * @package BuddyForms
 * @since 0.3 beta
 */
add_filter( 'query_vars', 'buddyforms_attached_page_query_vars' );
/**
 * @param $query_vars
 *
 * @return array
 */
function buddyforms_attached_page_query_vars( $query_vars ) {

	$query_vars[] = 'bf_action';
	$query_vars[] = 'bf_form_slug';
	$query_vars[] = 'bf_post_id';
	$query_vars[] = 'bf_parent_post_id';
	$query_vars[] = 'bf_rev_id';

	return $query_vars;
}

/**
 * rewrite the url of the edit-this-post link in the frontend
 *
 * @package BuddyForms
 * @since 0.3 beta
 */
add_filter( 'get_edit_post_link', 'buddyforms_my_edit_post_link', 1, 3 );
/**
 * @param $url
 * @param $post_ID
 *
 * @return string
 */
function buddyforms_my_edit_post_link( $url, $post_ID ) {
	global $buddyforms, $current_user;

	if ( is_admin() ) {
		return $url;
	}

	if ( ! isset( $buddyforms ) ) {
		return $url;
	}

	$the_post         = get_post( $post_ID );
	$post_type        = get_post_type( $the_post );
	$form_slug        = get_post_meta( $post_ID, '_bf_form_slug', true );
	$posttype_default = get_option( 'buddyforms_posttypes_default' );

	if ( ! $form_slug && isset( $posttype_default[ $post_type ] ) || $form_slug == 'none' && isset( $posttype_default[ $post_type ] ) ) {
		$form_slug = $posttype_default[ $post_type ];
	}

	if ( $form_slug == 'none' ) {
		return $url;
	}

	if ( $the_post->post_author != $current_user->ID ) // @todo this needs to be modified for admins and collaborative content creation
	{
		return $url;
	}

	if ( isset( $buddyforms[ $form_slug ]['edit_link'] ) && $buddyforms[ $form_slug ]['edit_link'] == 'none' ) {
		return $url;
	}

	if ( isset( $buddyforms[ $form_slug ]['edit_link'] ) && $buddyforms[ $form_slug ]['edit_link'] == 'my-posts-list' ) {
		return $url;
	}

	if ( isset( $buddyforms[ $form_slug ] ) && $buddyforms[ $form_slug ]['post_type'] == $post_type ) {

		$permalink = get_permalink( $buddyforms[ $form_slug ]['attached_page'] );
		$url       = $permalink . 'edit/' . $form_slug . '/' . $post_ID;

		return $url;
	}

	return $url;
}

/**
 * Retrieve edit posts link for post.
 *
 * Can be used within the WordPress loop or outside of it. Can be used with
 * pages, posts, attachments, and revisions.
 *
 * @since 2.3.0
 *
 * @param int $id Optional. Post ID.
 * @param string $context Optional, defaults to display. How to write the '&', defaults to '&amp;'.
 *
 * @return string The edit post link for the given post.
 */
function buddyforms_get_edit_post_link( $id = 0, $context = 'display' ) {
	if ( ! $post = get_post( $id ) ) {
		return;
	}

	if ( 'revision' === $post->post_type ) {
		$action = '';
	} elseif ( 'display' == $context ) {
		$action = '&amp;action=edit';
	} else {
		$action = '&action=edit';
	}

	$post_type_object = get_post_type_object( $post->post_type );
	if ( ! $post_type_object ) {
		return;
	}


	/**
	 * Filter the post edit link.
	 *
	 * @since 2.3.0
	 *
	 * @param string $link The edit link.
	 * @param int $post_id Post ID.
	 * @param string $context The link context. If set to 'display' then ampersands
	 *                        are encoded.
	 */
	return apply_filters( 'get_edit_post_link', admin_url( sprintf( $post_type_object->_edit_link . $action, $post->ID ) ), $post->ID, $context );
}
