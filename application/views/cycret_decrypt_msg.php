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
*		Displays form for decrypting a message
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
?>


<SCRIPT TYPE="text/javascript">
<!--
var downStrokeField;
function autojump(fieldName,nextFieldName,fakeMaxLength)
{
var myForm=document.forms[document.forms.length - 1];
var myField=myForm.elements[fieldName];
myField.nextField=myForm.elements[nextFieldName];

if (myField.maxLength == null)
   myField.maxLength=fakeMaxLength;

myField.onkeydown=autojump_keyDown;
myField.onkeyup=autojump_keyUp;
}

function autojump_keyDown()
{
this.beforeLength=this.value.length;
downStrokeField=this;
}

function autojump_keyUp()
{
if (
   (this == downStrokeField) && 
   (this.value.length > this.beforeLength) && 
   (this.value.length >= this.maxLength)
   )
   this.nextField.focus();
downStrokeField=null;
}
//-->
</SCRIPT>




<?php $this->load->helper('form'); ?>

<?php echo form_open('cycret/decrypt/'.$uniqueUrl); ?>

<h3>Insert keypair here, <?php echo $uniqueUrl; ?></h3>
<input type="hidden" name="uniqueUrl" value="<?php echo $uniqueUrl; ?>"></br>
<input type="hidden" name="modulo" value="<?php echo $modulo; ?>"></br>

Keypair: <input type="text" name="privateKey1" maxlength=4><input type="text" name="privateKey2"></br>
<?php echo form_submit('submit', 'Submit'); ?>

<?php echo form_close(); ?>

<SCRIPT TYPE="text/javascript">
<!--
autojump('privateKey1', 'privateKey2', 4);
//-->
</SCRIPT>
