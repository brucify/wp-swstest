<?php 

add_filter( 'manage_sws_test_posts_columns' , 'wpSwstest_add_sws_test_columns' );
add_action( 'manage_sws_test_posts_custom_column' , 'wpSwstest_populate_sws_test_columns', 10, 2 );
add_action( 'wp_ajax_add_alt', 'wpSwstest_add_alt' );
add_action( 'wp_ajax_delete_alt', 'wpSwstest_delete_alt' );
add_action( 'wp_ajax_update_alt', 'wpSwstest_update_alt' );
add_action( 'wp_ajax_update_question', 'wpSwstest_update_question' );
add_action( 'wp_ajax_add_question', 'wpSwstest_add_question' );
add_action( 'wp_ajax_delete_question', 'wpSwstest_delete_question' );
add_action( 'wp_ajax_update_question_type', 'wpSwstest_update_question_type' );


/*
 * MODEL
 */
function wpSwstest_register_test () {
	
	$labels = array (
		'name' => 'Tests',
		'singular_name' => 'Test',
		'add_new' => 'Add New Test',
		'add_new_item' => 'Add New Test',
		'edit_item' => 'Edit Test',
		'new_item' => 'New Test',
		'all_items' => 'All Tests',
		'view_item' => 'View Test',
		'search_items' => 'Search Tests',
		'not_found' => 'No tests found',
		'not_found_in_trash' => 'No tests found in Trash',
		'parent_item_colon' => '',
		'menu_name' => 'Tests',
	);
	
	$args = array(
		'labels' => $labels,
		'public' => true,
		'register_meta_box_cb' => 'wpSwstest_add_sws_test_meta_box',
		'supports' => array ('title', 'editor', 'custom-fields')
	);
	
	register_post_type( 'sws_test', $args );
	
	
}

function wpSwstest_register_test_type_taxonomy () {
	
	$labels = array(
		'name' => 'Test Types',
		'singular_name' => 'Test Type',
		'add_new_item' => 'Add New Test Type',
		'new_item_name' => 'New Test Type Name',
		'update_item' => 'Update Test Type',
		'edit_item' => 'Edit Test Type',
		'all_items' => 'All Test Types',
		'search_items' => 'Search Test Types',
		'not_found' => 'No tests found',
		'not_found_in_trash' => 'No tests found in Trash',
		'parent_item' => 'Parent Test Type',
		'parent_item_colon' => 'Parent Test Type:',
		'menu_name' => 'Test Type',
	);
	
	$args = array(
		'hierarchical' => true,
		'labels' => $labels,
		'query_var' => true,
		'rewrite' => true
	);
	
	register_taxonomy( 'sws_test_type', 'sws_test', $args);

} 

/*
 * VIEW
 */
function wpSwstest_add_sws_test_columns ($columns) {
	
    unset($columns['postid']);
    //unset($columns['ssid']);
	
	return array_merge($columns, 
              array('sws_test_price' => __('Price'),
                    'sws_test_time_limit' =>__( 'Time Limit'),
					'sws_test_type' =>__('Test Type')));
					
}

function wpSwstest_populate_sws_test_columns ( $column, $sws_test_id ) {
	
	if ($column == 'sws_test_price') {
		
		$price = esc_html(
			get_post_meta( $sws_test_id, 'sws_test_price', true)
		);
		
		echo $price;
		
	} elseif ($column == 'sws_test_time_limit') {
		
		$time_limit = esc_html(
			get_post_meta( $sws_test_id, 'sws_test_time_limit', true)
		);
		
		echo $time_limit;
		
	} elseif ($column == 'sws_test_type') {
		
		$test_types = wp_get_post_terms( $sws_test_id, 'sws_test_type');
		
		if( $test_types )
			echo $test_types[0]->name;
		else
			echo 'None Assigned';
			
	}
	
}


/*
 * CONTROLLER
 */

function wpSwstest_add_sws_test_meta_box () {
	
	add_meta_box(
		'wpSwstest_test_detail_meta_box', //id
		'Test Details', // title
		'wpSwstest_inner_test_detail_box', // callback,
		'sws_test', // post_type
		'normal', // context
		'high' // priority
	);
	
	add_meta_box(
		'wpSwstest_questions_meta_box', //id
		'Test Details', // title
		'wpSwstest_inner_questions_box', // callback,
		'sws_test', // post_type
		'normal', // context
		'high' // priority
	);
}

function wpSwstest_inner_test_detail_box ($sws_test) {
	
	$sws_test_price = esc_html( 
		
		get_post_meta ( 
			$sws_test->ID, // post_id
			'sws_test_price', // key
			true // single
		)
	);
	
	$sws_test_time_limit = esc_html( 
		
		get_post_meta ( 
			$sws_test->ID, // post_id
			'sws_test_time_limit', // key
			true // single
		)
	);
	
	?>
    <table style="width: 100%;">
    	<tr>
        	<td style="width: 20%;">Test Price</td>
            <td><input style="width: 100%;" type="text" name="sws_test_price" value="<?php echo $sws_test_price; ?>"/></td>
		</tr>
        <tr>
        	<td style="width: 20%;">Time Limit</td>
            <td><input style="width: 100%;" type="text" name="sws_test_time_limit" value="<?php echo $sws_test_time_limit; ?>"/></td>
        </tr>
    
    </table>
    <?php
	
}

function wpSwstest_inner_questions_box ($sws_test) {
	?>
    <div id=sws_quetions>
    		<script type="text/javascript" src="<?php echo plugins_url(); ?>/wp-swstest/js/sws_test.js"></script>

    <?php
	$question_ids = get_post_meta($sws_test->ID, 'sws_question_id', false);	// !
	
	for ( $i = 1; $i <= sizeof($question_ids); $i++ ) {
		
		$question = get_post($question_ids[$i - 1]); // !
		wpSwstest_a_question($i, $question, $sws_test);
	
	}
	
	wpSwstest_enter_new_question($sws_test);
	?>

    </div>
    <?php
}


function wpSwstest_save_sws_test ( $sws_test_id, $sws_test ) {
	
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
	if ($sws_test->post_type == 'sws_test') {
		
		if ( isset($_POST['sws_test_price']) && $_POST['sws_test_price'] != '' ) {
			
			add_post_meta (			
				$sws_test_id, // post_id
				'sws_test_price', // meta_key
				$_POST['sws_test_price'], // meta_value
				true // unique
				
			) or 
				update_post_meta (
					$sws_test_id, // post_id
					'sws_test_price', // meta_key
					$_POST['sws_test_price'] // meta_value
				);
		
		}
		
		if ( isset($_POST['sws_test_time_limit']) && $_POST['sws_test_time_limit'] != '' ) {
	
			add_post_meta(	
				$sws_test_id, // post_id
				'sws_test_time_limit', // meta_key
				$_POST['sws_test_time_limit'], // meta_value
				true // unique
				
			) or
				update_post_meta(
					$sws_test_id, // post_id
					'sws_test_time_limit', // meta_key
					$_POST['sws_test_time_limit'] // meta_value
				);
		
		}
	
	}

}

function wpSwstest_a_question($question_index, $sws_question, $sws_test) {
		
	$sws_question_type = esc_html( 
		
		get_post_meta ( 
			$sws_question->ID, // post_id
			'sws_question_type', // key
			true // single
		)
	);
		
	/*switch( $sws_question_type ) {
		case "single_choice":
			add_single($index, $question);
			break;
			
		case "multi_choice":
			add_multi($index, $question);
			break;
		
		case "text":
			add_text($index, $question);
			break;
			
		case "order":
			add_sorting($index, $question);
			break;
	} */
	
	?>
    
        <table id="<?php echo "t", $sws_question->ID; ?>" style="width: 100%; border:thin; border-style: ridge;">
        	<thead><tr><th><?php echo "Question " . $question_index. " "; ?><a href="<?php echo get_edit_post_link($sws_question->ID);?>"><?php echo "[ID ".$sws_question->ID."]"; ?></a></th></tr></thead>
            <tbody>
                <tr><td><strong>Question Description</strong></td></tr>
                <tr>
                    <td><textarea id="<?php echo "ta", $sws_question->ID; ?>" rows="5" wrap="physical" style="width: 100%; height: 150px;"><?php echo $sws_question->post_content; ?></textarea>
                    </td>
                </tr>
                <tr>
                	<td align="right">
                    <button type="button" class="button deletemeta button-small" onclick="deleteq('<?php echo $sws_question->ID; ?>', '<?php echo "t", $sws_question->ID; ?>', '<?php echo $sws_test->ID; ?>');">Delete</button>
                    <button type="button" class="button updatemeta button-small" onclick="updateq('<?php echo $sws_question->ID; ?>', '<?php echo "ta", $sws_question->ID; ?>');">Update</button>
                    <p></p>
                    </td>
                </tr>
                
                <tr><td><strong>Question Type</strong></td></tr>
                <tr>
                    <td>
                        <select id="<?php echo "sl", $sws_question->ID; ?>" onchange="updateqtype('<?php echo $sws_question->ID; ?>', '<?php echo "sl", $sws_question->ID; ?>');" style="width: 100%;" type="text" name="sws_question_type" value="<?php echo $sws_question_type; ?>">
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
                        </select><p></p>
                    </td>
                </tr>
                
				
                <tr><td><strong>Alternatives</strong></td></tr>
                <tr><td>
                    <table>
						<?php 
						
							$question_alt_ids = get_post_meta( $sws_question->ID, "sws_question_alt_id", false);

                            $alpha_index = "A";
                                
                            for ( $alt_index = 0; $alt_index < sizeof($question_alt_ids); $alt_index++ ) {
    
                                $question_alt = get_post( $question_alt_ids[$alt_index] );
                                
                                ?>
                            <tr id="<?php echo $question_alt->ID; ?>">
                                <td class="left"><div><?php echo $alpha_index++, ". "; ?></div></td>
                                <td><div style="width: 100%;"><input id="<?php echo "alt". $question_alt->ID; ?>" type="text" value="<?php echo $question_alt->post_content; ?>" /></div></td>
                                <td>
                                    <div style="width: 135px;">
                                        <button type="button" class="button deletemeta button-small" onclick="deletealt('<?php echo $question_alt->ID; ?>', '<?php echo $sws_question->ID; ?>');">Delete</button>
                                        <button type="button" class="button updatemeta button-small" onclick="updatealt('<?php echo $question_alt->ID; ?>', '<?php echo $sws_question->ID; ?>', '<?php echo "alt". $question_alt->ID; ?>');">Update</button>
                                    </div>
                                </td>
                            </tr>
                        <?php
							}
                  		?>
                        	<tr id="<?php echo "newalt". $question_index; ?>">
                            	<td></td> 
                                <td><div><input id="<?php echo "newaltin". $question_index; ?>" type="text" value="" /></div></td> 
                                <td> 
                                    <div> 
                                        <button type="button" onclick="newalt('<?php echo "newalt". $question_index; ?>', '<?php echo "newaltin". $question_index; ?>', '<?php echo $sws_question->ID; ?>');" class="button addmeta button-small">Add</button> 
                                    </div>
                                </td>		
                			</tr>
                    </table><p></p></td>
                </tr>
        	</tbody>
        </table>
		<p></p>
    <?php
}

function wpSwstest_enter_new_question($sws_test) {
	$temp_textarea_id = "ta". "newquestion";
	$temp_select_id = "sl". "newquestion";
?>
    
        <table style="width: 100%; border:thin; border-style: ridge;">
        	<thead><tr><th>Add a question</th></tr></thead>
            <tbody>
                <tr><td><strong>Question Description</strong></td></tr>
                <tr>
                    <td><textarea id="<?php echo $temp_textarea_id; ?>" rows="5" wrap="physical" style="width: 100%; height: 150px;"></textarea>
                    </td>
                </tr>
                
                <tr><td><strong>Question Type</strong></td></tr>
                <tr>
                    <td>
                        <select style="width: 100%;" type="text" id="<?php echo $temp_select_id; ?>" value="">
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
                            
                            <option value="<?php echo $q_types[$i]; ?>">
                                    <?php echo $q_types[$i]; ?>
                            </option>
                                
                            <?php } ?>
                        </select><p></p>
                    </td>
                </tr>
                
				<tr>
                	<td align="right">
                    <button type="button" class="button updatemeta button-small" onclick="addq('<?php echo $temp_textarea_id; ?>', '<?php echo $temp_select_id; ?>', '<?php echo $sws_test->ID; ?>');">Add</button>
                    <p></p>
                    </td>
                </tr>
                
        	</tbody>
        </table>
		<p></p>
	<?php       
}

/*
 * AJAX callbacks
 */
function wpSwstest_add_alt () {
		
	if ($_POST && 
			!empty($_POST['quetionid']) && 
			!empty($_POST['alt']) ) {
				
		$question_id = $_POST['quetionid'];
		$alt = $_POST['alt'];
		//echo "bonjour qid=", $question_id, " alt=", $alt;
		
		$post_args = array(
			'post_content' => rawurldecode($alt),
			'post_title' => "Q" . $question_id . " Alt",
			'post_type' => 'sws_question_alt',
			'post_status' => 'publish'
		);
		
		$new_alt_ID = wp_insert_post( $post_args );
		
		if($new_alt_ID != 0) {
			add_post_meta (			
					$question_id, // post_id
					'sws_question_alt_id', // meta_key
					$new_alt_ID, // meta_value
					false // unique		
			);	
		}
		
		echo $new_alt_ID;
	}
	
	die();

}

function wpSwstest_delete_alt () {
	
	if ($_POST && 
			!empty($_POST['quetionid']) && 
			!empty($_POST['altid']) ) {
				
		$question_id = $_POST['quetionid'];
		$alt_id = $_POST['altid'];
		//echo "bonsoir qid=", $question_id, " altid=", $alt_id;
		
		$result = delete_post_meta(
			$question_id, // post_id
			'sws_question_alt_id', // meta_key
			$alt_id // meta_value
		);
		
		echo $result;
	}
	
	die();
}

function wpSwstest_update_alt () {
	
	if ($_POST && 
			!empty($_POST['quetionid']) && 
			!empty($_POST['altid']) && 
			!empty($_POST['alt'])) {
				
		$question_id = $_POST['quetionid'];
		$alt_id = $_POST['altid'];
		$alt = $_POST['alt'];

		//echo "bonsoir qid=", $question_id, " altid=", $alt_id, " alt=", $alt;
		$result = wp_update_post( array(
			'ID' => $alt_id,
			'post_content' => rawurldecode($alt)
		));
		
		echo $result;
	}
	
	die();
}

function wpSwstest_update_question () {
	
	if ($_POST && 
			!empty($_POST['quetionid']) && 
			!empty($_POST['q'])) {
				
		$question_id = $_POST['quetionid'];
		$question_content = $_POST['q'];

		$result = wp_update_post( array(
			'ID' => $question_id,
			'post_content' => $question_content
		));
		
		echo $result;
	}
	
	die();
}


function wpSwstest_add_question () {
	if ($_POST && 
			!empty($_POST['q']) && 
			!empty($_POST['qtype']) && 
			!empty($_POST['testid'])) {
				
		$question_type = $_POST['qtype'];
		$question_content = $_POST['q'];
		$test_id = $_POST['testid'];

		$post_args = array(
			'post_content' => $question_content,
			'post_title' => "Q",
			'post_type' => "sws_question",
			'post_status' => 'publish'
		);
		
		$new_q_ID = wp_insert_post( $post_args );
		
		if($new_q_ID != 0) {
			add_post_meta (			
					$new_q_ID, // post_id
					'sws_question_type', // meta_key
					$question_type, // meta_value
					true // unique		
			);	
			
			add_post_meta (			
					$test_id, // post_id
					'sws_question_id', // meta_key
					$new_q_ID, // meta_value
					false // unique		
			);			
		}
		
		echo $new_q_ID;
	}
	
	die();
}

function wpSwstest_update_question_type () {
	
	if ($_POST && 
			!empty($_POST['quetionid']) && 
			!empty($_POST['qtype'])) {
				
		$question_id = $_POST['quetionid'];
		$question_type = $_POST['qtype'];

		$result = update_post_meta (			
			$question_id, // post_id
			'sws_question_type', // meta_key
			$question_type // meta_value
		);		
		
		echo $result;
	}
	
	die();
}

function wpSwstest_delete_question() {
	if ($_POST && 
			!empty($_POST['quetionid']) && 
			!empty($_POST['testid']) ) {
				
		$question_id = $_POST['quetionid'];
		$test_id = $_POST['testid'];
		
		$result = delete_post_meta(
			$test_id, // post_id
			'sws_question_id', // meta_key
			$question_id // meta_value
		);
		
		if($result == true)
			$result = wp_delete_post( $question_id );
		
		if ($result != false) 
			echo true;
		else
			echo false;
	}
	
	die();
}