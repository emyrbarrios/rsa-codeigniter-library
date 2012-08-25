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
*		Displays when recipient accepted message, private key
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
?>

<strong>Important: this is the only time your decryption key is displayed. <br />
It is not stored in the database. If you forget it you will not be able to decrypt the message.</strong><br />


Decryption key: <?php echo $keypair1; ?> <?php echo $keypair2; ?>


