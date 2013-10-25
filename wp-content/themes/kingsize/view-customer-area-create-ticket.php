	<?php 
                    if (isset($_SESSION['customer_id'])) {  //do something if logged in
					
						include("navigation_tabs.php");
						
						$customer_id = $_SESSION['customer_id'];
						get_navigation_tabs('create-ticket');
						$available_tests = $membership->get_available_tests($customer_id); // Get tests from SQL
						/*$available_tests = get_posts(
							array(
								'post_type' => 'sws_test'
							)
						);*/ // Gey tests from WP
                    ?>
                        
                        <div id="customer_area_body">
                        
                        	<?php 
								// message panel
								if(isset($result_create_ticket)) {
									
									?>
									<div class="message_box info_box"><p class="message_box_sign info_box_sign">Info</p>
										<p><?php 
										$test_url = get_site_url() . "/swstest/?action=enter_token&ticket_token=" . $result_create_ticket; // add-on has to be called "swstest" in order to work
										if ($result_create_ticket != -1) 
                                           	echo '<a href=' , $test_url , '>Click this link to start test.</a>';
										else 
											echo "Error when creating ticket."; ?></p>
									</div> 
							<?php
								}
							?>
                                
                                <form method="post" action="" id="candidate_info_form">
                                
									<!-- Select tesst -->
                                    <div class="control-group" id="div_select_test">
                                    	<script type="text/javascript" src="<?php bloginfo( 'template_url' );?>/js/listbox.js"></script>

                                        <h3>Select a Test</h3>
                                        <?php // get tests ?>

										<table style="border: 0px;">
                                            <tr style="border: 0px;">
                                                <th style="border: 0px;"><label class="control-label" for="inputError">Available Tests</label></th>
                                                <th style="border: 0px;"></th>
                                                <th style="border: 0px;"><label class="control-label" for="inputError">Selected Tests</label></th>
                                            </tr>
                                            <tr style="border: 0px;">
                                                <td style="border: 0px;">
                                                    <select id="available_tests" name="available_tests_id[]" size=10 style="height: 210px; width: 250px;" multiple="multiple" onfocus="onFocus('div_select_test', 'span_select_test')">
                                                    <?php 
													/*foreach ($available_tests as $available_test) {
														echo 
														"<option value=". $available_test['test_id'] ." ondblclick='showDescription('". $available_test['test_id'] ."')'>".
															$available_test['test_name'] . 
														"</option>";	
													}*/
													
													// SQL tests
													
													for ($i = 0; $i < sizeof($available_tests); $i++) { // Get tests from SQL
														$out = "<option value=". $available_tests[$i]->ID;
														
														if ($i == 0) 
															$out .= " selected";
														
														$out .= ' onmouseover="showDescription('. $i .')">'.
														//  ondblclick="listbox_moveacross(this, \'available_tests\', \'selected_tests\')">'. 
																$available_tests[$i]->post_title . 
															"</option>";	
															
														echo $out;	
													} 
													
									
													/*
													for ($i = 0; $i < sizeof($available_tests); $i++) { // Get tests from WP
														$out = "<option value=". $available_tests[$i]->ID;
														
														if ($i == 0) 
															$out .= " selected";
														
														$out .= ' onmouseover="showDescription('. $i .')">'.
														//  ondblclick="listbox_moveacross(this, \'available_tests\', \'selected_tests\')">'. 
																$available_tests[$i]->post_title . 
															"</option>";	
															
														echo $out;	
													} */
													
													?>
                                                    
                                                    </select>
                                                </td>
                                                <td style="border: 0px;">
                                                	<table style="border: 0px;">
                                                        <tr style="border: 0px;"><a class="btn" href="#" onclick="listbox_moveacross('selected_tests', 'available_tests'); onFocus('div_select_test', 'span_select_test')"><i class="icon-chevron-left"></i></a></tr>

                                                        <tr style="border: 0px;"><a class="btn" href="#" onclick="listbox_moveacross('available_tests', 'selected_tests'); onFocus('div_select_test', 'span_select_test')"><i class="icon-chevron-right"></i></a></tr>
                                                    </table>
                                                </td>
                                                <td style="border: 0px;">
                                                    <select id="selected_tests" name="selected_tests_id[]" size=10 style="height: 210px; width: 250px;" multiple="multiple">
                                                    </select>
                                                    
                                                </td>
                                            </tr>
                                            <tr>
                                            <td style="border: 0px;"></td>
                                            <td style="border: 0px;"></td>
                                            <td style="border: 0px;"><span class="help-inline" id="span_select_test"></span></td>
                                            </tr>
                                        </table>
                                        

                                    </div>
                                    <!-- select test ends here -->
                                    
                                    <!-- test description -->
                                    <div id="test_description" class="hidden">
                                    <h3>Test Description</h3>
                                    <script>
										var JSONAvailableTests = <?php 
											
											$available_tests_array = array();
											
											for( $i = 0; $i < sizeof( $available_tests ); $i++ ) {
												
												$test_types = get_the_terms( $available_tests[$i]->ID, 'sws_test_type' );

												$price = esc_html( get_post_meta( $available_tests[$i]->ID, 'sws_test_price', true) );
												
												$time_limit = esc_html( get_post_meta( $available_tests[$i]->ID, 'sws_test_time_limit', true) );
												
												array_push( $available_tests_array, 
													array(
														'test_name' => $available_tests[$i]->post_title,
														'test_id' => $available_tests[$i]->ID,
														'test_type' => $test_types[0]->name,
														'price' => $price,
														'time_limit' => $time_limit,
														'test_description' => $available_tests[$i]->post_content
													)
												);
												
											}
										
											echo json_encode($available_tests_array); 
											//echo json_encode($available_tests);
										?>; 
										function showDescription(i) {
											//document.getElementById("dl_test_name").innerHTML = "Test Name";
											document.getElementById("dd_test_name").innerHTML = JSONAvailableTests[i].test_name;
											
											//document.getElementById("dl_test_id").innerHTML = "Test ID"
											document.getElementById("dd_test_id").innerHTML = JSONAvailableTests[i].test_id;
											
											//document.getElementById("dl_test_type").innerHTML = "Test Type";
											document.getElementById("dd_test_type").innerHTML = JSONAvailableTests[i].test_type;

											//document.getElementById("dl_test_price").innerHTML = "Credit Cost";
											document.getElementById("dd_test_price").innerHTML = JSONAvailableTests[i].price;

											//document.getElementById("dl_time_limit").innerHTML = "Time Limit";
											document.getElementById("dd_time_limit").innerHTML = JSONAvailableTests[i].time_limit;
											
											//document.getElementById("dl_test_description").innerHTML = "Test Description";
											document.getElementById("dd_test_description").innerHTML = JSONAvailableTests[i].test_description;

										}
									</script>
                                     
                                     		<dl class="dl-horizontal"> 
                                                <dt id="dl_test_name">Test Name</dt>
                                                <dd id="dd_test_name"><?php echo $available_tests[0]->post_title; ?></dd>
                                                
                                                <dt id="dl_test_id">Test ID</dt>
                                                <dd id="dd_test_id"><?php echo $available_tests[0]->ID; ?></dd>
                                                
                                                <dt id="dl_test_type">Test Type</dt>
                                                <dd id="dd_test_type"><?php $test_types = get_the_terms( $available_tests[0]->ID, 'sws_test_type' );
echo $test_types[0]->name; ?></dd>
                                                
                                                <dt id="dl_test_price">Credits Cost</dt>
                                                <dd id="dd_test_price"><?php $price = esc_html( get_post_meta( $available_tests[0]->ID, 'sws_test_price', true) ); echo $price; ?></dd>
                                                
                                                <dt id="dl_time_limit">Time Limit</dt>
                                                <dd id="dd_time_limit"><?php $time_limit = esc_html( get_post_meta( $available_tests[0]->ID, 'sws_test_time_limit', true) );
echo $time_limit; ?></dd>
                                                
                                                <dt id="dl_test_description">Description</dt>
                                                <dd id="dd_test_description"><?php echo $available_tests[0]->post_content; ?></dd>
                                            </dl>
                                            
                                            <?php 
											
											/* Tests from WP

											$test_name = $available_tests[0]->post_title;
											$test_id = $available_tests[0]->ID;											
											$test_types = get_the_terms( $test_id, 'sws_test_type' );
											$test_type = $test_types[0]->name;
											$price = esc_html(
												get_post_meta( $test_id, 'sws_test_price', true)
											);
												
											$time_limit = esc_html(
												get_post_meta( $test_id, 'sws_test_time_limit', true)
											);
											$test_description = $available_tests[0]->post_content;
											
											?>
                                            
                                              <dl class="dl-horizontal">
                                                <dt id="dl_test_name">Test Name</dt>
                                                <dd id="dd_test_name"><?php echo $test_name; ?></dd>
                                                
                                                <dt id="dl_test_id">Test ID</dt>
                                                <dd id="dd_test_id"><?php echo $test_id; ?></dd>
                                                
                                                <dt id="dl_test_type">Test Type</dt>
                                                <dd id="dd_test_type"><?php echo $test_type; ?></dd>
                                                
                                                <dt id="dl_test_price">Credits Cost</dt>
                                                <dd id="dd_test_price"><?php echo $price; ?></dd>
                                                
                                                <dt id="dl_time_limit">Time Limit</dt>
                                                <dd id="dd_time_limit"><?php echo $time_limit; ?></dd>
                                                
                                                <dt id="dl_test_description">Description</dt>
                                                <dd id="dd_test_description"><?php echo $test_description; ?></dd>
                                            </dl> */ ?>
										
                                        
                                    </div> 
                                    <!-- test description ends here -->
                                    
                                    
                                    <!-- select candidate -->
                                    <div id="select_candidate">
                                        <h3>Enter Candidate Info</h3>
    
                                        <div class="control-group" id='div_candidate_name'>
                                            <label class="control-label" for="inputError"><?php _e('Candidate Name', 'kslang'); ?></label>
                                            <input class="textbox" id='input_candidate_name' type='text' name='candidate_name' placeholder="E.g. Henrik Enström" onfocus="onFocus('div_candidate_name', 'span_candidate_name')" />
                                            <span class="help-inline" id='span_candidate_name'></span>
                                        </div>
                                        
                                        <div class="control-group" id='div_candidate_email'>
                                            <label class="control-label" for="inputError"><?php _e('Candidate Email', 'kslang'); ?></label>
                                            <input class="textbox" id='input_candidate_email' type='text' name='candidate_email'placeholder="E.g. example@example.com"  onfocus="onFocus('div_candidate_email', 'span_candidate_email')" />
                                        	<span class="help-inline" id='span_candidate_email'></span>
                                        </div>  
                                        
										<label class="checkbox">
                                        	<input type="checkbox" name="send_to_candidate">Send notification to the candidate's inbox.
										</label>	
                            		</div>
                                    <!-- select candidate ends here -->
                                    
<?php /*?>                                    <input id='form_create_ticket' class="button blue" type='submit' name='create_ticket' value='<?php _e('Create a Ticket', 'kslang'); ?>' />	
<?php */?>                                  

										  <button id='form_create_ticket' type="button" class="button blue"  onclick="formSubmit()" name='create_ticket'><?php _e('Create a Ticket', 'kslang'); ?></button>	
                                                
								</form>
    							<script>
                                	function formSubmit() {
										var f = 0;
										if ( !document.getElementById("input_candidate_name").value ) {
											document.getElementById("span_candidate_name").innerHTML = "Please enter name.";
											document.getElementById("div_candidate_name").className = "control-group error"; 
											f = 1;
										}
										
										if ( !document.getElementById("input_candidate_email" ).value ) {
											document.getElementById("span_candidate_email").innerHTML = "Please enter Email.";
											document.getElementById("div_candidate_email").className = "control-group error"; 
											f = 1;
										}
										
										if ( !document.getElementById("selected_tests" ).value ) {
											document.getElementById("span_select_test").innerHTML = "Please select tests";
											document.getElementById("div_select_test").className = "control-group error"; 
											f = 1;
										}
										
										if ( f == 0 )
											document.getElementById("candidate_info_form").submit();
									}
									
									function onFocus(div, span) {
										document.getElementById(div).className = "control-group";
										document.getElementById(span).innerHTML = "";
										
									}
                                </script>
                            </div>
                    <?php 
                    } else {  // do something if logged out
						include("login.php");
					}?>