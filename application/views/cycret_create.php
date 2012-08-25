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
*		Displays form for initiating a message
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
?>

<h2>Create a new Cycret</h2>


<?php echo validation_errors(); ?>

<?php echo form_open('cycret/create') ?>
	<label for="senderName">sender name</label> 
	<input type="input" name="senderName" value="<?php echo set_value('senderName'); ?>" /><br />
	
	<label for="senderEmail">sender email</label> 
	<input type="input" name="senderEmail" value="<?php echo set_value('senderEmail'); ?>" /><br />
	
	<label for="recipientName">recipient name</label> 
	<input type="input" name="recipientName" value="<?php echo set_value('recipientName'); ?>" /><br />
	
	<label for="recipientEmail">recipient email</label> 
	<input type="input" name="recipientEmail" value="<?php echo set_value('recipientEmail'); ?>" /><br />

	<label for="burnType">Burn date:</label>
	<?php $burntime = array(
                  '3'   		=> '24 hours from now',
                  '4'   		=> '48 hours from now',
                  '5'   		=> '72 hours from now',
                  '2'   		=> 'Burn after reading',
                  '1'	 		=> 'Never'
                );


	echo form_dropdown('burnType', $burntime);
	?>
	
	<input type="submit" name="submit" value="Create cycret" /> 
</form>

