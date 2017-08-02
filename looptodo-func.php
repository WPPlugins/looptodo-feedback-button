<?php
/*
Plugin Name: LoopTodo Feedback Button
Plugin URI: http://www.loopto.do
Description: Capture, manage, and reply to customer feedback captured from your website or web application. Manage the feedback in your Loop at <a href="http://my.loopto.do">http://my.loopto.do</a>.
Version: 0.9.2014.05.21
Author: LoopTodo
Author URI: http://www.loopto.do
License: GPL2
*/
?><?php
/*  Copyright 2012, 2013  James Mortensen, LoopTodo  (email : info@loopto.do)

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
?><?php include 'looptodo-admin.php';?><?php

define('__LINK__', trailingslashit( str_replace("home/jem/workspace/", "", '/'.PLUGINDIR.'/'. dirname( plugin_basename(__FILE__) ))) );


/*===========================================
	Setup the basic hooks for installing and
	removing the plug-in
 ===========================================*/

/* Runs when plugin is activated */
register_activation_hook(__LINK__,'looptodo_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'looptodo_remove' );
 
/**
 * 
 * Insert files on the HTML page to include the feedback form and feedback button.
 * 
 */
function looptodo_insert_embed_code() {

	wp_deregister_style( 'loop_feedback_style' );
    wp_register_style( 'loop_feedback_style', __LINK__.'wp-looptodo-feedback-embed.css');
    wp_enqueue_style( 'loop_feedback_style' );
	
	wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
    wp_enqueue_script( 'jquery' );
	
	wp_deregister_script( 'loop_feedback' );
    wp_register_script( 'loop_feedback', __LINK__.'wp-looptodo-feedback-embed.js');
    wp_enqueue_script( 'loop_feedback' );
    
    // embed the javascript file that makes the AJAX request
    //wp_enqueue_script( 'loop_feedback', __LINK__ . 'wp-looptodo-feedback-embed.js', array( 'jquery' ) );
 
    // declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
    wp_localize_script( 'loop_feedback', 'Looptodo_MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
        
}    

/**
 * 
 * Ajax handler to retreive the loop key from the database to fetch the form from the Loop server.
 */
function looptodo_ajax_get_loopkey() {
	
	//echo 'work dammit';
	$response = 
        json_encode( 
            array( 'looptodo_loopkey' =>  get_option('looptodo_loopkey'),
            'looptodo_domain' =>  get_option('looptodo_domain') ) 
        );
	
    header('Content-type: application/json');
    echo $response;
        
	die();	
}

//echo PLUGINDIR.'/'. dirname( plugin_basename(__FILE__) );
//echo "<br/>link = " . __LINK__;
		
add_action('wp_enqueue_scripts', 'looptodo_insert_embed_code');

add_action('wp_ajax_fetch-key', 'looptodo_ajax_get_loopkey');
add_action('wp_ajax_nopriv_fetch-key', 'looptodo_ajax_get_loopkey');

?>
