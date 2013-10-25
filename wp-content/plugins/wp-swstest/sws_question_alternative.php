<?php
/*
 * MODEL
 */
function wpSwstest_register_question_alternative () {
	
	$labels = array (
		'name' => 'Alternatives',
		'singular_name' => 'Alternative',
		'add_new' => 'Add New Alternative',
		'add_new_item' => 'Add New Alternative',
		'edit_item' => 'Edit Alternative',
		'new_item' => 'New Alternative',
		'all_items' => 'All Alternatives',
		'view_item' => 'View Alternative',
		'search_items' => 'Search Alternatives',
		'not_found' => 'No questions found',
		'not_found_in_trash' => 'No questions found in Trash',
		'parent_item_colon' => '',
		'menu_name' => 'Question Alt.'
	);
	
	$args = array(
		'labels' => $labels,
		'public' => true,
		'supports' => array ('title', 'editor', 'custom-fields')
	);
	
	register_post_type( 'sws_question_alt', $args );
	
}