<!-- Post -->
<div class="login">
        
    <form class="form-horinzontal" method="post" action="" id="login_form">

		<div class="control-group">
        	<label class="control-label" for='inputEmail'><?php _e('Email', 'kslang'); ?></label>
        	<div class="controls">
				<input id='inputEmail' type='text' name='login_email' placeholder="Email" />
            </div>
        </div>
        
        <div class="control-group">
        	<label class="control-label" for='inputPassword'><?php _e('Password', 'kslang'); ?></label>
           	<div class="controls">
            	<input id='inputPassword' type='password' name='login_password' placeholder="Password" />
			</div>
    	</div>
		    
    
         <div class="control-group">
            <div class="controls">
				<input id='form_action' type='hidden' name='action' value='login'/>

                <input id='form_submit' type='submit' name='submit' value='<?php _e('Login', 'kslang'); ?>' />
				<!--<button class="btn" type='submit' name='submit'><?php // _e('Login', 'kslang'); ?></button> -->

                <?php if(isset($response)) echo "<h4 class='alert'>" . $response . "</h4>"; ?>
    		</div>
        </div>
        <!-- hidden input for basic spam protection -->
        <div class='hide'>
            <label for='spamCheck'>Do not fill out this field</label>
            <input id="spamCheck" name='spam_check' type='text' value='' />
        </div>
                        
    </form>

    <!-- login form ends here-->	
        
    <br />
    <br />
    
</div>	
<!-- Post ends here -->