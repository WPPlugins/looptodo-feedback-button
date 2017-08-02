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

jQuery(document).ready(function() {
	
	jQuery.get(
		    // see tip #1 for how we declare global javascript variables
			Looptodo_MyAjax.ajaxurl,
		    {
		        // here we declare the parameters to send along with the request
		        // this means the following action hooks will be fired:
		        // wp_ajax_nopriv_myajax-submit and wp_ajax_myajax-submit
		        action : 'fetch-key'
		 
		    },
		    function( response ) {
		        		        
		        wp_looptodo_load_feedback_form(response.looptodo_domain, response.looptodo_loopkey);
		    }
		);
	
});


var wp_looptodo_load_feedback_form = (function($) {
    return function(looptodo_domain, looptodo_loopKey) {
	
	// embed the LoopTodo feedback code on the page
	$('body').append(
		'<a href="#" class="wp_looptodo_feedback_button" id="looptodo_feedback_btn">'+ 
			'<img src="http://'+looptodo_domain+'/images/loop_feedback_btn.png?v=0-9-2012-6-21" />'+
		 '</a> '+
	     '<div style="display: none;" id="looptodo_wrapper">'+ 
  		     '<div class="looptodo_feedback_inner"> '+
			    ' <span id="looptodo_close_btn"> '+
			   '      <img src="http://'+looptodo_domain+'/form/images/close_btn.png" />'+ 
			  '   </span> '+
			 '    <iframe id="looptodo_frame" src="http://'+looptodo_domain+'/loop-form.html?key='+looptodo_loopKey+'" frameborder="0" scrolling="no"></iframe>'+ 
			' </div> '+
	     '</div>');
	    
	// click handler - open the feedback form
	$('.wp_looptodo_feedback_button').click(function() {
		$('#looptodo_wrapper').show();
		$('#looptodo_wrapper').attr("style","display:block; width: 100%; height: 100%; background: url(http://"+looptodo_domain+"/form/images/white_overlay.png); position: fixed; top: -8%;z-index:20000;");
	});
	
	// click handler - close the feedback form
	$('#looptodo_close_btn').click(function() {
		$('#looptodo_wrapper').hide();
	});
	
	// TODO: pull from the wp database
	var loopData = { buttonPosition: "right" };

	// position the feedback button as per settings
	if(loopData.buttonPosition == "left") {
		$('.wp_looptodo_feedback_button').attr("style","position:fixed; left:0px; top:25%; display:block;z-index:20000;");		
	} else if(loopData.buttonPosition == "top") {
		$('.wp_looptodo_feedback_button').attr("style","position:fixed; left:75%; top:0%; display:block;z-index:20000;");
	} else if(loopData.buttonPosition == "bottom") {
		$('.wp_looptodo_feedback_button').attr("style","position:fixed; left:75%; bottom:0px; display:block;z-index:20000;");				
	} else {
		$('.wp_looptodo_feedback_button').attr("style","position:fixed; right:0px; top:25%; display:block;z-index:20000;");
	}
    }
})(jQuery);	
