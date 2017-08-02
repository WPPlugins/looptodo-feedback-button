<?php
/*  Copyright 2012  James Mortensen, LoopTodo  (email : info@loopto.do)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?><?php
add_option("looptodo_domain", 'my.loopto.do', '', 'yes');
	add_option("looptodo_loopkey", '', '', 'yes');

/*===========================================
	Do the work, create a database field
 ===========================================*/

function looptodo_install() {
	/* Creates new database field */
	add_option("looptodo_domain", 'my.loopto.do', '', 'yes');
	add_option("looptodo_loopkey", '', '', 'yes');
}

function looptodo_remove() {
	/* Deletes the database field */
	delete_option('looptodo_domain');
	delete_option('looptodo_loopkey');
}



/*===========================================
	Create an admin menu to me loaded 
 ===========================================*/

if ( is_admin() ){
	/* Call the html code */
	add_action('admin_menu', 'looptodo_admin_menu');

	function looptodo_admin_menu() {
		add_options_page('LoopTodo Options', 'LoopTodo', 'administrator', 'looptodo', 'looptodo_html_page');
	}
}


/*===========================================
	Settings HTML page
 ===========================================*/

function looptodo_html_page() { ?>
	<div>
		<h2>LoopTodo Settings</h2>

	
		<br />
		
		<h3>Where can I find my Loop Key?</h3>

		
		<ul style="list-style-type:circle;margin-left:20px;">
		    <li><a href="http://my.loopto.do" target="_blank">Sign into LoopTodo</a> using your Google account.</li>
		    <li>In the left 'Loop List' section, select your Loop. If this is your first time logging in, your Loop is already selected.</li>
		    <li>Click the 'Settings' link, near the top right.</li>
		    <li>In the 'Settings' panel, click the 'Loop Name' tab in the left section.</li>
		    <li>Copy and paste the Loop Key in the field below, and click 'Save Changes'</li>
		</ul>

		<form method="post" action="options.php">
			<?php wp_nonce_field('update-options'); ?>

			<table>
				<tr valign="top" align="left">
					<th width="190" scope="row">Your Loop Key</th>

					<td width="480">				
						<input 
							type="text"  
							id="looptodo_loopkey"
							name="looptodo_loopkey" 
							style="width: 310px;"
							value="<?php echo get_option('looptodo_loopkey'); ?>" />
					</td>
				</tr>
			</table>

			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options" value="looptodo_loopkey" />

			<p class="submit" style="padding-top: 0;"><input type="submit" id="submit" name="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
		</form>			 
		
		
		<br />
		
		<h3>Don't have a LoopTodo account? No problem!</h3>

		<p>
			All you need is a Google account! 
			<a href="http://www.loopto.do" target="_blank">Get started with a completely free account</a>.			
		</p>
	</div>
<?php } ?>
<?php 
add_action('admin_head', 'validate_looptodo_loopkey');

/**
 * 
 * Validate the Loop Key and give feedback to the admin.
 */
function validate_looptodo_loopkey() {
?>
<script type="text/javascript" >
jQuery(document).ready(function($) {

	var data = {
		action: 'validate_loopkey',
		whatever: 1234
	};

	if(jQuery('#looptodo_loopkey').val() == "") 
		return ;

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.get(ajaxurl, data, function(response) {
		var resp = {};
		resp.INTERNAL_SERVER_ERROR = "HTTP/1.0 500 Internal Server Error";
		resp.OK = "HTTP/1.0 200 OK";
		
		//if( jQuery('#looptodo_loopkey').val() == response.keyString) {
		if( response == resp.OK) {
			//alert('Got this from the server: ' + response.keyString);
			jQuery('#looptodo_loopkey').after('<span style="color:white;background-color:green;font-weight:bold;margin-left:10px;padding:3px;">&nbsp;&nbsp;OK!&nbsp;&nbsp;</span');
		} else {
			jQuery('#looptodo_loopkey').after('<span style="color:white;background-color:red;font-weight:bold;margin-left:10px;padding:3px;">&nbsp;&nbsp;INVALID KEY!&nbsp;&nbsp;</span');
		}

	});
	
});
</script>
<?php } ?>
<?php 


    // hook for validating the key against the Loop server from the admin page
    add_action('wp_ajax_validate_loopkey', 'looptodo_ajax_validate_loopkey_callback');
    //add_action('wp_ajax_nopriv_validate_loopkey', 'looptodo_ajax_validate_loopkey_callback');


function looptodo_ajax_validate_loopkey_callback() {

	$url = "http://".get_option('looptodo_domain')."/loop/".get_option('looptodo_loopkey')."/?startIndex=0&count=1&bucket=inbox";
	
	//print_r(get_headers($url));
	$headers = get_headers($url);
	echo $headers[0];
	
	// make a proxy request to Loop to see if the key exists
	//$json = file_get_contents("http://".get_option('looptodo_domain')."/loop/".get_option('looptodo_loopkey')."g/?startIndex=0&count=1&bucket=inbox");
	
    // response output
    //header( "Content-Type: application/json" );    
    //echo $json;
 
    // IMPORTANT: don't forget to "exit"
    die();   
}
?>