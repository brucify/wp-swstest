<?php
/**
 * @KingSize 2011
 **/
####### Theme Setting #########
global $get_options,$data;
$get_options = get_option('wm_theme_settings');
###############################
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head> <!-- Header starts here -->
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<title><?php bloginfo('name'); ?> | <?php is_home() || is_front_page() ? bloginfo('description') : wp_title(''); ?></title> <!-- Website Title of WordPress Blog -->	
	<link rel="icon" type="image/png"  href="<?php echo $data['wm_favicon_upload'];?>">
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" /> <!-- Style Sheet -->
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" /> <!-- Pingback Call -->

	<!--[if lte IE 8]>						
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/stylesIE.css" type="text/css" media="screen" />
	<![endif]-->		
	<!--[if lte IE 7]>				
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/stylesIE7.css" type="text/css" media="screen" />
	<![endif]-->

	<!-- Include custom JS -->
	<?php require(TEMPLATEPATH . '/js/custom.php'); ?>
	<!-- End Include custom JS -->

	<!-- Do Not Remove the Below -->
	<?php if(is_singular()) wp_enqueue_script('comment-reply'); ?>
	<?php if ($tpl_body_id!="slideviewer") {  wp_enqueue_script("jquery"); } ?>
	<?php wp_head(); ?>
	<!-- Do Not Remove the Above -->



	<!-- Theme setting head include wp admin -->
	<?php
	$head_include = "";
	$head_include = $data['wm_head_include'];
	echo $head_include;
	?>
	<!-- End Theme setting head include -->
	
	<!-- Gallery / Portfolio control CSS and JS-->		
	<?php 
	//if gallery Shortcode is being used 
	global $tpl_body_id;
	
	if( preg_match('/type="colorbox"(.*)/', $posts[0]->post_content, $matches) ) 	
	{
		$tpl_body_id = "colorbox";
	}
	elseif( preg_match('/type="fancybox"(.*)/', $posts[0]->post_content, $matches) ) 	
	{
		$tpl_body_id = "fancybox";
	}
	elseif( preg_match('/type="prettyphoto"(.*)/', $posts[0]->post_content, $matches) )
	{
		$tpl_body_id = "prettyphoto";
	}	
	elseif( preg_match('/type="slideviewer"(.*)/', $posts[0]->post_content, $matches) )
	{
		$tpl_body_id = "slideviewer";
	}
	elseif( preg_match('/type="galleria"(.*)/', $posts[0]->post_content, $matches) )
	{
		$tpl_body_id = "galleria";
	}
	// End if gallery shortcode being used
	
	include (TEMPLATEPATH . '/lib/gallery_template_style_js.php'); ?>		
	<!-- END Portfolio control CSS and JS-->
	
	<?php if ( $data['wm_no_rightclick_enabled'] == "1" ) {?>
	<!-- Disable Right-click -->
		<script type="text/javascript" language="javascript">
			jQuery(function($) {
				$(this).bind("contextmenu", function(e) {
					e.preventDefault();
				});
			}); 
		</script>
	<!-- END of Disable Right-click -->
	<?php } ?>

	<!-- scripts for background slider; if you want to use background slider/video be sure you have this v4-->
	<?php
	if( $data['wm_background_type'] != 'Video Background' && is_home()) {			
		include (TEMPLATEPATH . '/lib/background_slider.php'); 
	} 
	?>
	<!-- scripts for background slider end here v4-->
	
	<!-- New Opacity/Transparency Options added in v4 -->
	<?php 
	if( $data['wm_enable_opacity'] == "0.9 Opacity") { ?>
	<style>
	/*<!--- .9 --->*/
	#menu { background:  url(<?php echo get_template_directory_uri(); ?>/images/opacity/90/menu_back.png) repeat left top !important; }
	#hide_menu { background:  url(<?php echo get_template_directory_uri(); ?>/images/opacity/90/hide_menu_back.png) no-repeat left top !important; }
	#main_wrap { background:  url(<?php echo get_template_directory_uri(); ?>/images/opacity/90/content_back.png) repeat left top !important; }
	#navbar ul { opacity: 0.9 !important; }
	.menu_tooltip {background: url(<?php echo get_template_directory_uri(); ?>/images/opacity/90/tooltip.png) no-repeat center center !important;}
	</style>
	<?php } elseif( $data['wm_enable_opacity']  == "0.8 Opacity") { ?>
	<style>
	/*<!--- .8 --->*/
	#menu { background:  url(<?php echo get_template_directory_uri(); ?>/images/opacity/80/menu_back.png) repeat left top !important; }
	#hide_menu { background:  url(<?php echo get_template_directory_uri(); ?>/images/opacity/80/hide_menu_back.png) no-repeat left top !important; }
	#main_wrap { background:  url(<?php echo get_template_directory_uri(); ?>/images/opacity/80/content_back.png) repeat left top !important; }
	#navbar ul { opacity: 0.8 !important; }
	.menu_tooltip {background: url(<?php echo get_template_directory_uri(); ?>/images/opacity/80/tooltip.png) no-repeat center center !important;}

	</style>
	<?php } elseif( $data['wm_enable_opacity']  == "0.7 Opacity") { ?>
	<style>
	/*<!--- .7 --->*/
	#menu { background:  url(<?php echo get_template_directory_uri(); ?>/images/opacity/70/menu_back.png) repeat left top !important; }
	#hide_menu { background:  url(<?php echo get_template_directory_uri(); ?>/images/opacity/70/hide_menu_back.png) no-repeat left top !important; }
	#main_wrap { background:  url(<?php echo get_template_directory_uri(); ?>/images/opacity/70/content_back.png) repeat left top !important; }	
	#navbar ul { opacity: 0.7 !important; }
	.menu_tooltip {background: url(<?php echo get_template_directory_uri(); ?>/images/opacity/70/tooltip.png) no-repeat center center !important;}
	</style>
	<?php } elseif( $data['wm_enable_opacity']  == "Default") { ?>
	<style>
	/*<!--- Default --->*/
	#menu { background: url(<?php echo get_template_directory_uri(); ?>/images/menu_back.png) repeat-y top left !important; }
	#hide_menu { background: url(<?php echo get_template_directory_uri(); ?>/images/hide_menu_back.png) no-repeat bottom left !important; }
	#main_wrap { background: url(<?php echo get_template_directory_uri(); ?>/images/content_back.png) repeat-y top left !important; }
	</style>
	<?php } ?>
	<!-- End of New Opacity/Tranparency Options -->
	
	<?php if( $data['wm_custom_css'] ) { ?><style><?php echo $data['wm_custom_css'];?></style><?php } ?>
</head> 
<!-- Header ends here -->

<?php
  ####### getting the current page template set from the page custom options #######	 
  $current_page_template = get_option('current_page_template');  

 ####### Overlay handling	#######
 $body_overlay = "body_home";
 $body_overlay_home = "";

//BACKGROUND IMAGE GRID OVERLAY
if( $data['wm_grid_hide_enabled'] == "Disable Grid Overlay on All Pages")
	$show_grid = false;
else
	$show_grid = true;

if(get_post_meta($wp_query->post->ID, 'kingsize_post_grid_overlay', true ) == 'grid_disabled')
	$show_grid = false;
elseif(get_post_meta($wp_query->post->ID, 'kingsize_page_grid_overlay', true ) == 'grid_disabled')
	$show_grid = false;
elseif(get_post_meta($wp_query->post->ID, 'kingsize_portfolio_grid_overlay', true ) == 'grid_disabled')
	$show_grid = false;
else
   $show_grid = true;


  if ($data['wm_grid_hide_enabled'] == "Disable Grid Overlay on All Pages" && is_home()) { 	
	$body_overlay = "";
  }
  elseif( $data['wm_grid_hide_enabled'] == "Enable the Grid Overlay on All Pages"  && is_home()){
	  $body_overlay_home = "body_about";
	  $body_overlay = "body_about";
  }
  elseif($show_grid == true  && !is_home()){
	  $body_overlay = "body_about";
  }
  else{
	$body_overlay = "";
  }

#########   Making conditional to hide the body content on page load ######### 
 include (TEMPLATEPATH . '/lib/show_hide_body_menu.php'); 
##############################################################################
?>
<?php if(is_home()) {?>
<!--[if lte IE 7]>				
<style>
.body_home #menu_wrap
{margin: 0;}
</style>
<![endif]-->
	<?php
	if($data['wm_background_type'] == 'Video Background') { ?>
	<body <?php body_class($body_overlay_home." "."body_home video_background slider"); ?> data-spy="scroll" data-target="#nav_q">
	<?php } else { ?>
	<body <?php body_class($body_overlay_home." "."body_home slider"); ?> data-spy="scroll" data-target="#nav_q">
	<?php } ?>
<?php } else {?>
	
		<body <?php body_class($body_overlay."  ".$current_page_template);?> data-spy="scroll" data-target="#nav_q">
	
<?php 		
   } ?>

<?php
include (TEMPLATEPATH . '/lib/background_video.php'); 
?>

    <!-- Wrapper starts here -->
	<div id="wrapper">
		
	     <!-- Navigation starts here -->	 
		<div id="menu_wrap">
			
			<!-- Menu starts here -->
			<div id="menu">
		    	
				<!-- Logo starts here -->
		      	<div id="logo">   
				  <?php
				  //get custom logo
				  $theme_custom_logo = $data['wm_logo_upload'];
				  $url_logo = get_template_directory_uri();

				 if(!empty($theme_custom_logo))
				 {
				  ?>
					<h1><a href="<?php echo home_url(); ?>" class="logo_image index"><img src="<?php echo $theme_custom_logo ?>" alt="<?php bloginfo('description'); ?>" title="<?php bloginfo('name'); ?>" /></a></h1>		
				<?php
				}
				else{
				?>
				   <h1><a href="<?php echo home_url(); ?>" class="logo_image index"><img src="<?php echo $url_logo;?>/images/logo_back.png" alt="<?php bloginfo('description'); ?>" title="<?php bloginfo('name'); ?>" /></a></h1>							
				<?php
				}
				?>
		      	</div>
		      	<!-- Logo ends here -->
		      	
		      	<!-- Navbar -->
				<?php 
					wp_nav_menu( array(
					 'sort_column' =>'menu_order',
					 'container' => 'ul',
					 'theme_location' => 'header-nav',
					 'fallback_cb' => 'null',
					 'menu_id' => 'navbar',
					 'link_before' => '',
					 'link_after' => '',
					 'depth' => 0,
					 'walker' => new description_walker())
					 );
				?>
			    <!-- Navbar ends here -->			    	       
		    </div>
		    <!-- Menu ends here -->
		    
		    <!-- Hide menu arrow -->
			<?php if ( $data['wm_menu_hide_enabled'] == "1" ) {?>
		    <div id="hide_menu">   
		    	<a href="#" class="menu_visible">Hide menu</a> 
					
					<?php if ( $data['wm_menu_tooltip_enabled'] == "1" ) {?>
		        	<div class="menu_tooltip">
                        <div class="tooltip_hide"><p><?php _e('Hide the navigation', 'kslang'); ?></p></div>
						<div class="tooltip_show"><p><?php _e('Show the navigation', 'kslang'); ?></p></div>
			        </div>  
					<?php } else { ?>
					<!-- No Tool Tip -->
					<?php } ?>					
		    </div>
				<?php } else { ?>	
				<div id="hide_menu">    
				</div>
				<?php } ?>
				<!-- Hide menu arrow ends here -->
		       
		</div>
		<!-- Navigation ends here -->
	
	
<?php 
	global $data, $cnt_slide;

	//background slider options validate	

	//whether video / image slider / default
		$show_slider = false;
	if(get_post_meta($wp_query->post->ID, 'kingsize_post_slider_video_background', true ) == 'image' && $cnt_slider > 0 )
		$show_slider = true;
	elseif(get_post_meta($wp_query->post->ID, 'kingsize_page_slider_video_background', true ) == 'image' && $cnt_slider > 0 )
		$show_slider = true;
	elseif(get_post_meta($wp_query->post->ID, 'kingsize_portfolio_slider_video_background', true ) == 'image' && $cnt_slider > 0 )
		$show_slider = true;
	elseif($data['wm_background_type'] != 'Video Background' && $cnt_slider > 0 )
		$show_slider = true;
	
	//slider controller
	   $slider_controllers = false;
	if(get_post_meta($wp_query->post->ID, 'kingsize_post_slider_controllers', true ) == 'Enable Slider Controls')
		$slider_controllers = true;
	elseif(get_post_meta($wp_query->post->ID, 'kingsize_page_slider_controllers', true ) == 'Enable Slider Controls')
		$slider_controllers = true;
	elseif(get_post_meta($wp_query->post->ID, 'kingsize_portfolio_slider_controllers', true ) == 'Enable Slider Controls')
		$slider_controllers = true;
	elseif($data['wm_slider_controllers']=="Enable Slider Controls" && is_home())	
		$slider_controllers = true;
	

	if($show_slider == true) {
	?>
	
		<?php
		if($slider_controllers==true){
		?>
		 <!--Arrow Navigation-->
		 <div class="slider-details-container">
		 <a id="prevslide" class="load-item">prev</a>
		 <a id="nextslide" class="load-item">next</a>	 
		 
		 <div id="controls-wrapper" class="load-item">
			<div id="controls">
		 
		 <!-- Play button -->
			<a id="play-button"><img id="pauseplay" src="<?php echo get_bloginfo('template_url');?>/images/slider_pause.png"/></a>
		 
			<!--Slide counter-->
			<div id="slidecounter">
				<span class="slidenumber"></span> / <span class="totalslides"></span>
			</div>
			
		   </div>
		 </div>	
		 </div>
		<?php
		}
		?>	 

	<!--Slide captions displayed here-->
	<?php

	//show_title_description
	if(get_post_meta($wp_query->post->ID, 'kingsize_post_slider_contents', true ) != '' && !is_home())
		$show_title_description = get_post_meta($wp_query->post->ID, 'kingsize_post_slider_contents', true );
	elseif(get_post_meta($wp_query->post->ID, 'kingsize_page_slider_contents', true ) != '' && !is_home())
		$show_title_description = get_post_meta($wp_query->post->ID, 'kingsize_page_slider_contents', true );
	elseif(get_post_meta($wp_query->post->ID, 'kingsize_portfolio_slider_contents', true ) != '' && !is_home())
		$show_title_description = get_post_meta($wp_query->post->ID, 'kingsize_portfolio_slider_contents', true );
	elseif($data['wm_slider_contents'] != ''  && is_home())
		$show_title_description = $data['wm_slider_contents'];

	if($show_title_description == 'Display Title & Description' || $show_title_description == 'Display Title' || $show_title_description == 'Display Description'){
	?>	
	<div class="slider-details-container">
		 <div id="slidecaption"></div>
	</div>
	<?php 
	} 
	?>
	<!--END Slide captions displayed here-->

<?php 
} 
?>

<?php if(is_home()) {?>
</div>
<?php } ?>