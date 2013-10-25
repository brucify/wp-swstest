<?php

add_filter( 'manage_sws_ticket_posts_columns' , 'wpSwstest_add_sws_ticket_columns' );
add_action( 'manage_sws_ticket_posts_custom_column' , 'wpSwstest_populate_sws_ticket_columns', 10, 2 );

/*
 * MODEL
 */
function wpSwstest_register_ticket () {
	
	$labels = array (
		'name' => 'Tickets',
		'singular_name' => 'Ticket',
		'add_new' => 'Add New Ticket',
		'add_new_item' => 'Add New Ticket',
		'edit_item' => 'Edit Ticket',
		'new_item' => 'New Ticket',
		'all_items' => 'All Tickets',
		'view_item' => 'View Ticket',
		'search_items' => 'Search Tickets',
		'not_found' => 'No tickets found',
		'not_found_in_trash' => 'No tickets found in Trash',
		'parent_item_colon' => '',
		'menu_name' => 'Tickets',
	);
	
	$args = array(
		'labels' => $labels,
		'public' => true,
	);
	
	register_post_type( 'sws_ticket', $args );
	
	
}


/*
 * VIEW
 */
function wpSwstest_add_sws_ticket_columns ($columns) {
	
    unset($columns['postid']);
    //unset($columns['ssid']);
	
	return array_merge($columns, 
			array('sws_ticket_candidate_name' => __('Candidate Name'),
                    'sws_ticket_candidate_email' =>__( 'Candidate Email'),
					'sws_ticket_option' =>__('Ticket Option'),
					'sws_ticket_token' =>__('Ticket Token'),
					'sws_ticket_status' =>__('Ticket Status')

			)
	);
					
}

function wpSwstest_populate_sws_ticket_columns ( $column, $sws_ticket_id ) {
	
	if ($column == 'sws_ticket_candidate_name') {
		
		$candidate_name = esc_html(
			get_post_meta( $sws_ticket_id, 'sws_ticket_candidate_name', true)
		);
		
		echo $candidate_name;
		
	} elseif ($column == 'sws_ticket_candidate_email') {
		
		$candidate_email = esc_html(
			get_post_meta( $sws_ticket_id, 'sws_ticket_candidate_email', true)
		);
		
		echo $candidate_email;
		
	} elseif ($column == 'sws_ticket_option') {
		
		$ticket_option = esc_html(
			get_post_meta( $sws_ticket_id, 'sws_ticket_option', true)
		);
		
		echo $ticket_option;
		
	} elseif ($column == 'sws_ticket_token') {
		
		$ticket_token = esc_html(
			get_post_meta( $sws_ticket_id, 'sws_ticket_token', true)
		);
		
		echo $ticket_token;
		
	} elseif ($column == 'sws_ticket_status') {
		
		$ticket_status = esc_html(
			get_post_meta( $sws_ticket_id, 'sws_ticket_status', true)
		);
		
		echo $ticket_status;
		
	} 
	
}