<?php
/**
* Name:  	CodeIgniter RSA view
* Author:	Dirk de Man
*		dirk_de_man at yahoo . com
*         	@dirktheman
*
* Created:  	05.10.2012
*
* Description:  CodeIgniter RSA view
*		Displays form for encrypting a message
*		Includes a Twitter-like counter for counting the number of characters left
*		The .js is included at the bottom of this page but it would be better to include it in a separate file
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function(){	
		//default usage
		$("#message").charCount();
		//custom usage
	});
</script>


</head>
<body>

<?php $this->load->helper('form'); ?>
 
<h1>Insert message here, <?php echo $uniqueUrl;?></h1>
 
<?php echo form_open('cycret/encrypt/'.$uniqueUrl); ?>
	<input type="hidden" name="uniqueUrl" value="<?php echo $uniqueUrl;?>"><br />
	<div>
		<span style="float:left;">Insert message</span>
		<span class="charsLeft" style="float:right; color:#ccc; font-size:22px; text-align:right;"></span>
	</div>
        <?php //echo form_textarea('message'); ?>
	<div><textarea name="message" id="message"></textarea></div>
	<br />
        <?php echo form_submit('submit', 'Submit'); ?>
<?php echo form_close(); ?>




<script type="text/javascript">
/*
 * 	Character Count Plugin - jQuery plugin
 * 	Dynamic character count for text areas and input fields
 *	written by Alen Grakalic	
 *	http://cssglobe.com/post/7161/jquery-plugin-simplest-twitterlike-dynamic-character-count-for-textareas
 *
 *	Copyright (c) 2009 Alen Grakalic (http://cssglobe.com)
 *	Dual licensed under the MIT (MIT-LICENSE.txt)
 *	and GPL (GPL-LICENSE.txt) licenses.
 *
 *	Built for jQuery library
 *	http://jquery.com
 *
 */
 
(function($) {

	$.fn.charCount = function(options){
	  
		// default configuration properties
		var defaults = {	
			allowed: 87,		
			warning: 20,
			css: 'counter',
			counterElement: 'span',
			cssWarning: 'warning',
			cssExceeded: 'exceeded',
			counterText: ''
		}; 
			
		var options = $.extend(defaults, options); 
		
		function calculate(obj){
			var count = $(obj).val().length;
			var available = options.allowed - count;
			if(available <= options.warning && available >= 0){
				$(obj).next().addClass(options.cssWarning);
			} else {
				$(obj).next().removeClass(options.cssWarning);
			}
			if(available < 0){
				$(obj).next().addClass(options.cssExceeded);
			} else {
				$(obj).next().removeClass(options.cssExceeded);
			}
			$(obj).next().html(options.counterText + available);
		};
				
		this.each(function() {  			
			$(this).after('<'+ options.counterElement +' class="' + options.css + '">'+ options.counterText +'</'+ options.counterElement +'>');
			calculate(this);
			$(this).keyup(function(){calculate(this)});
			$(this).change(function(){calculate(this)});
		});
	  
	};

})(jQuery);
</script>