<?php
/*
Plugin Name: Mass Pages/Posts Creator
Plugin URI: http://www.multidots.com/
Description: Manage Wp Table
Version: 1.0
Author: dots
Author URI: http://www.multidots.com/
*/

function mpc_load_my_script() {
    wp_enqueue_script( 'jquery' );
}

add_action( 'wp_enqueue_scripts', 'mpc_load_my_script' );


add_action('admin_enqueue_scripts', 'mpc_styles');
function mpc_styles() {
    wp_register_style( 'custom_wp_admin_css', plugins_url('mass-pagesposts-creator/css/style.css'));
    wp_enqueue_style( 'custom_wp_admin_css' );
}


function mpc_pages_posts_creator(){
    add_submenu_page( 'options-general.php', 'Mass Pages/Posts Creator', 'Mass Pages/Posts Creator', 'administrator', 'mass-pages-posts-creator.php', 'mpc_create' );
}

add_action( 'admin_menu', 'mpc_pages_posts_creator' );

function mpc_create(){ 
	global $wpdb;
	$parent_pages= $wpdb->get_results( "SELECT ID, post_title FROM $wpdb->posts WHERE post_parent=0 AND post_type='page' AND post_status='publish' ORDER BY menu_order ASC" ); ?>
	<div class="wrap">
		
			<form id="createForm" method="post" class="">
				<h2>Mass Pages/Posts Creator</h2>
				<table class="form-table">	
						
					<tr class="page_prefix_tr">
						<th>
							Prefix of Pages/Posts
						</th>
						<td>
							<input type="text" class="regular-text" value="" id="page_prefix" name="page_prefix">
						</td>
					</tr>
					<tr class="page_post_tr">
						<th>
							Postfix of Pages/Posts
						</th>
						<td>
							<input type="text" class="regular-text" value="" id="page_postfix" name="page_postfix">
						</td>
					</tr>
					<tr class="pages_list_tr">
						<th>
							List of Pages/Posts</br>(Coma Seperated) <b>(*)</b>
						</th>
						<td>
							<textarea class="code" id="pages_list" cols="60" rows="5" name="pages_list"></textarea>
							<p class="description">eg. Test1, Test2, test3, test4, test5</p>
						</td>
						
					</tr>
					<tr class="pages_content_tr">
						<th>
							Content of Pages/Posts
						</th>
						<td>
							<textarea class="code" id="pages_content" cols="60" rows="5" name="pages_content"></textarea>
							<p class="description">eg. It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
						</td>
					</tr>
					<tr class="excerpt_content_tr">
						<th>
							Excerpt Content
						</th>
						<td>
							<textarea class="code" id="excerpt_content" cols="60" rows="5" name="excerpt_content"></textarea>
							<p class="description">eg. It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
						</td>
						
					</tr>
					<tr class="type_tr">
						<th>Type <b>(*)</b></th>
						<td>
							<select id="type">
								<option value="none">Select Type</option>
								<option value="page">Page</option>
								<option value="post">Post</option>
							</select>
						</td>
					</tr>
					<tr class="parent_page_id_tr">
						<th>
							Parent Pages 
						</th>
						<td>
							<select id="parent_page_id">
								<option value="">Select Page</option>    
								<?php foreach($parent_pages as $pages){ ?>
									<option value="<?php echo $pages->ID ?>"><?php echo $pages->post_title; ?></option>    
										<?php $subpages = get_pages( array( 'child_of' => $pages->ID, 'sort_column' => 'post_date', 'sort_order' => 'desc' ) ); 
											if (isset($subpages) || !empty($subpages)) {
												foreach( $subpages as $subpage ) { ?>
													<option style="margin-left: 10px;"value="<?php echo $subpage->ID; ?>"><?php echo ' -- '.$subpage->post_title; ?></option>    		
														<?php $childsubpages = get_pages( array( 'child_of' => $subpage->ID, 'sort_column' => 'post_date', 'sort_order' => 'desc' ) ); 
																if (isset($childsubpages) || !empty($childsubpages)) {
																	foreach( $childsubpages as $childsubpage ) { ?>
																		<option style="margin-left: 30px;"value="<?php echo $childsubpage->ID; ?>"><?php echo ' -- '.$childsubpage->post_title; ?></option>    		
																	<?php }
																}
												}
											}
									} ?>
							</select>
						</td>
					</tr>
					<tr class="template_name_tr">
						<th>
							Templates  
						</th>
						<td>
							<?php $templates = get_page_templates(); ?>
							<select id="template_name">
								<option value="">Select Template</option>
								<?php foreach ( $templates as $template_name => $template_filename ) { ?>
						   				<option value="<?php echo $template_filename; ?>"><?php echo $template_name; ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					
					<tr class="page_status_tr">
						<th>
							Pages/Posts Status 
						</th>
						<td>
							<select id="page_status">
								<option value="publish">Publish</option>
								<option value="pending">Pending</option>
								<option value="draft">Draft</option>
								<option value="auto-draft">Auto Draft</option>
								<option value="future">Future</option>
								<option value="private">Private</option>
								<option value="inherit">Inherit</option>
								<option value="trash">Trash</option>
							</select>
						</td>
					</tr>
					
					<tr class="comment_status_tr">
						<th>
							Pages/Posts Comment Status 
						</th>
						<td>
							<select id="comment_status">
								<option value="">Select Comment Status </option>
								<option value="open"> Open </option>
								<option value="closed"> Closed </option>
							</select>
						</td>
					</tr>
					
					<tr class="authors_tr">
						<th>
							Author
						</th>
						<td>
							<?php $authors = get_users(); ?>
							<select id="authors">
								<option value="">Select Author </option>
							    <?php foreach ($authors as $single_user) { ?>
									<option value="<?php echo  $single_user->ID; ?>"><?php echo  $single_user->user_login; ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
				</table>
				<p class="submit"><input type="button" id="btn_submit" class="button button-primary" name="btn_submit" value="Create"/></p>
			</form>	
			<div id="message"></div>
			<div id="result"></div>
	</div>
		<style type="text/css">
			
		</style>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery("#type").change(function(){
					var type = jQuery("#type").val();
					if(type == 'post'){
						jQuery(".parent_page_id_tr").hide();
						jQuery(".template_name_tr").hide();
					} else {
						jQuery(".parent_page_id_tr").show();
						jQuery(".template_name_tr").show();
					}
				});
			});
			jQuery("#btn_submit").click(function(e){
				var prefix_word = jQuery("#page_prefix").val();
				var pages_list = jQuery("#pages_list").val();
				var pages_content = jQuery("#pages_content").val();
				var parent_page_id = jQuery("#parent_page_id").val();
				var template_name = jQuery("#template_name").val();
				var type = jQuery("#type").val();
				var postfix_word = jQuery("#page_postfix").val();
				var comment_status = jQuery("#comment_status").val();
				
				var page_status = jQuery("#page_status").val();
				var authors = jQuery("#authors").val();
				var excerpt_content = jQuery("#excerpt_content").val();
				
				if(pages_list.length == 0){
		    		alert('Please enter list of Pages..');
		    		event.preventDefault();
	    			return false;
		    	}
		    	
		    	if(type == 'none'){
		    		alert('Please select the type..');
		    		event.preventDefault();
	    			return false;
		    	}
		    	
				jQuery.ajax({
		          type:'POST',
		          data:{
		          	action:'mpc_ajax_action',
		          	prefix_word: prefix_word,
		          	postfix_word: postfix_word,
		          	pages_list: pages_list,
		          	pages_content: pages_content,
	          		parent_page_id: parent_page_id,
		          	template_name: template_name,
		          	type: type,
		          	page_status: page_status,
		          	authors: authors,
		          	excerpt_content: excerpt_content,
		          	comment_status: comment_status
		          },
		          url: "admin-ajax.php",
		          dataType: 'html',
		          success: function(response) {
		          	if(response) {
		           		jQuery("#createForm").css("display","none"); 
		           		jQuery("#message").addClass('view');
		           		jQuery('html,body').animate({
				        	scrollTop: 0
					    },'slow');
		           		jQuery("#message").html('Pages/Posts Succesfully Created.. ');
		           		jQuery("#result").append(response);
		           		
		           	} else {
		           		jQuery("#message").addClass('view');
		           		jQuery("#message").html('Something goes wrong..');
		           	}
		          }
		        });
				
	        });
		 
		</script>
<?php } 

function mpc_ajax_action(){
	global $wpdb;
	$html = '';
	$prefix_word = sanitize_text_field($_POST['prefix_word']);
	$postfix_word = sanitize_text_field($_POST['postfix_word']);
	$pages_content = sanitize_text_field($_POST['pages_content']);
	$parent_page_id = $_POST['parent_page_id'];
	$template_name = sanitize_text_field($_POST['template_name']);
	$type = sanitize_text_field($_POST['type']);
	$page_status = sanitize_text_field($_POST['page_status']);
	$authors = sanitize_text_field($_POST['authors']);
	$excerpt_content = sanitize_text_field($_POST['excerpt_content']);
	$comment_status = sanitize_text_field($_POST['comment_status']);
	
	$pages_list = sanitize_text_field($_POST['pages_list']);
	$page_list = explode(",", $pages_list);
	$html .= "<table cellpadding='0' cellspacing='0' >";
	$html .= "<thead><tr><th>Page/Post Id</th><th>Page/Post Name</th><th>Page/Post Status</th><th>URL</th></tr></thead><tbody>";
	
	foreach ($page_list as $page_name) {
		$my_post = array(
			'post_title'     => $prefix_word.' '.$page_name.' '.$postfix_word,
			'post_type'      => $type,
			'post_content'   => $pages_content,
			'post_author'    => $authors,
			'post_parent'    => $parent_page_id,
			'post_status'    => $page_status,
			'post_excerpt'   => $excerpt_content,
			'comment_status' => $comment_status
		);
		
		$last_insert_id = wp_insert_post($my_post);
		
		$url = get_permalink($last_insert_id);
		
		$html .= "<tr>";
		
		$html .= "<td> $last_insert_id</td> <td>".esc_html($page_name)." </td> <td class='status'> Ok </td><td> <a href='".esc_url($url)."' target='_blank'>".esc_url($url)."</a> </td>";	
		$html .= "</tr>";	
		add_post_meta( $last_insert_id , '_wp_page_template', $template_name);
		
	}
	$html .= "</tbody><table>";
	echo $html;
	wp_die(); 
}

add_action( 'wp_ajax_mpc_ajax_action', 'mpc_ajax_action' );
add_action( 'wp_ajax_nopriv`_mpc_ajax_action', 'mpc_ajax_action' );