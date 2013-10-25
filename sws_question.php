<?php

add_filter( 'manage_sws_question_posts_columns' , 'wpSwstest_add_sws_question_columns' );
add_action( 'manage_sws_question_posts_custom_column' , 'wpSwstest_populate_sws_question_columns', 10, 2 );

/*
 * MODEL
 */
function wpSwstest_register_question () {
	
	$labels = array (
		'name' => 'Questions',
		'singular_name' => 'Question',
		'add_new' => 'Add New Question',
		'add_new_item' => 'Add New Question',
		'edit_item' => 'Edit Question',
		'new_item' => 'New Question',
		'all_items' => 'All Questions',
		'view_item' => 'View Question',
		'search_items' => 'Search Questions',
		'not_found' => 'No questions found',
		'not_found_in_trash' => 'No questions found in Trash',
		'parent_item_colon' => '',
		'menu_name' => 'Questions'
	);
	
	$args = array(
		'labels' => $labels,
		'public' => true,
		'register_meta_box_cb' => 'wpSwstest_add_sws_question_meta_box',		
		'supports' => array ('title', 'editor', 'custom-fields')
	);
	
	register_post_type( 'sws_question', $args );
	
}


/*
 * VIEW
 */
function wpSwstest_add_sws_question_columns ($columns) {
	
    unset($columns['postid']);
    //unset($columns['ssid']);
	
	return array_merge($columns, array('sws_question_type' => __('Question Type')));
					
}

function wpSwstest_populate_sws_question_columns ( $column, $sws_question_id ) {
	
	if ($column == 'sws_question_type') {
		
		$question_type = esc_html(
			get_post_meta( $sws_question_id, 'sws_question_type', true)
		);
		
		echo $question_type;
		
	} 
	
}

/*
 * CONTROLLER
 */

function wpSwstest_add_sws_question_meta_box () {
	
	add_meta_box(
		'wpSwstest_sws_question_meta_box', //id
		'Question Details', // title
		'wpSwstest_inner_sws_question_box', // callback,
		'sws_question', // post_type
		'normal', // context
		'high' // priority
	);
	
}

function wpSwstest_inner_sws_question_box ($sws_question) {
	
	$sws_question_type = esc_html( 
		
		get_post_meta ( 
			$sws_question->ID, // post_id
			'sws_question_type', // key
			true // single
		)
	);
		
	?>
    <table style="width: 100%;">
    	<tr>
        	<td style="width: 20%;">Question Type</td>
            <td>
            	<select style="width: 100%;" type="text" name="sws_question_type" value="<?php echo $sws_question_type; ?>">
            		<?php 
					$q_types = array(
						"single_choice", 
						"multi_choice",
						"order",
						"match",
						"text",
						"programming"
					);
					
					for( $i = 0; $i < sizeof($q_types); $i++ ) { 
					?>
					
                    <option value="<?php echo $q_types[$i]; ?>" 
						<?php echo selected($q_types[$i], $sws_question_type); ?>>
							<?php echo $q_types[$i]; ?>
                    </option>
                    	
					<?php } ?>
                </select>
            </td>
		</tr>
    
    </table>
    <?php
	
}

function wpSwstest_save_sws_question ( $sws_question_id, $sws_question ) {
	
	// First we need to check if the current user is authorised to do this action. 
	/*if ( 'sws_test' == $_REQUEST['post_type'] ) {
	if ( ! current_user_can( 'edit_sws_test', $sws_test_id ) )
		return;
	} else {
	if ( ! current_user_can( 'edit_sws_test', $sws_test_id ) )
		return;
	}*/
	
	//TODO security check
	// Secondly we need to check if the user intended to change this value.
	/*if ( ! isset( $_POST['myplugin_noncename'] ) || ! wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename( __FILE__ ) ) )
	  return; */ 
	
	// Thirdly we can save the value to the database
	if ($sws_question->post_type == 'sws_question') {

		if ( isset($_POST['sws_question_type']) && $_POST['sws_question_type'] != '' ) {
			

			add_post_meta (			
				$sws_question_id, // post_id
				'sws_question_type', // meta_key
				$_POST['sws_question_type'], // meta_value
				true // unique
				
			) or 
				update_post_meta (
					$sws_question_id, // post_id
					'sws_question_type', // meta_key
					$_POST['sws_question_type'] // meta_value
				);
		
		}
	
	}

}