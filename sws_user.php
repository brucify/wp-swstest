<?php

add_filter( 'manage_sws_user_posts_columns' , 'wpSwstest_add_sws_user_columns' );
add_action( 'manage_sws_user_posts_custom_column' , 'wpSwstest_populate_sws_user_columns', 10, 2 );

/*
 * MODEL
 */
function wpSwstest_register_user () {
	
	$labels = array (
		'name' => 'SWS Users',
		'singular_name' => 'SWS User',
		'add_new' => 'Add New SWS User',
		'add_new_item' => 'Add New SWS User',
		'edit_item' => 'Edit SWS User',
		'new_item' => 'New SWS User',
		'all_items' => 'All SWS Users',
		'view_item' => 'View SWS User',
		'search_items' => 'Search SWS Users',
		'not_found' => 'No SWS Users found',
		'not_found_in_trash' => 'No SWS Users found in Trash',
		'parent_item_colon' => '',
		'menu_name' => 'SWS Users',
	);
	
	$args = array(
		'labels' => $labels,
		'public' => true,
	);
	
	register_post_type( 'sws_user', $args );
	
	
}


/*
 * VIEW
 */
function wpSwstest_add_sws_user_columns ($columns) {
	
    unset($columns['postid']);
    //unset($columns['ssid']);
	
	return array_merge($columns, 
			array('sws_user_name' => __('User Name'),
                    'sws_user_email' =>__( 'User Email'),
					'sws_user_credit' =>__('Credits')

			)
	);
					
}

function wpSwstest_populate_sws_user_columns ( $column, $sws_user_id ) {
	
	if ($column == 'sws_user_name') {
		
		$sws_user_name = esc_html(
			get_post_meta( $sws_user_id, 'sws_user_name', true)
		);
		
		echo $sws_user_name;
		
	} elseif ($column == 'sws_user_email') {
		
		$sws_user_email = esc_html(
			get_post_meta( $sws_user_id, 'sws_user_email', true)
		);
		
		echo $sws_user_email;
		
	} elseif ($column == 'sws_user_credit') {
		
		$sws_user_credit = esc_html(
			get_post_meta( $sws_user_id, 'sws_user_credit', true)
		);
		
		echo $sws_user_credit;
		
	} 
}