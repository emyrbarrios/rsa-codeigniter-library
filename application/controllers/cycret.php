<?php
/**
* Name:  	CodeIgniter RSA controller
* Author:	Dirk de Man
*				dirk at dirktheman . com
*         	@dirktheman
*
* Created:  	05.10.2012
*
* Description:  CodeIgniter RSA controller for encrypting and decrypting messages
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Cycret extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cycret_model');
		$this->load->library('rsa');
		$this->load->helper('form');
	}

	public function index()
	{
		$data['cycret'] = $this->cycret_model->get_all_cycrets();
		$this->load->view('header');
		$this->load->view('cycret_view', $data);
		$this->load->view('footer');
	}


//Create new cycret

	function create()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
	
		//Form validation goes here
		$this->form_validation->set_rules('senderName', 'Sender name', 'required|trim|max_length[100]|strip_tags|callback_remove_html_entities');
		$this->form_validation->set_rules('senderEmail', 'Sender email address', 'required|trim|max_length[100]|valid_email|strip_tags|callback_remove_html_entities');
		$this->form_validation->set_rules('recipientName', 'Recipient name', 'required|trim|max_length[100]|strip_tags|callback_remove_html_entities');
		$this->form_validation->set_rules('recipientEmail', 'Recipient email address', 'required|trim|max_length[100]|valid_email|strip_tags|callback_remove_html_entities');
	
		//If form validation fails, return to form again
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('header');
			$this->load->view('cycret_create');
			$this->load->view('footer');
		}
		//If form validates...
		else
		{
			//random URL generator
			$length = 10;
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$uniqueUrl = '';    
			$i = 0;
			while ($i < $length) { 
				//$uniqueUrl .= $characters[mt_rand(0, strlen($characters))];
				$uniqueUrl .= $characters[mt_rand(0, strlen($characters) - 1)];
				$i++;
			}
			$data['uniqueUrl'] = $uniqueUrl;

			//Set and format creation date
			$createDate = date('Y-m-d H:i:s');
			$data['createDate'] = $createDate;

			//Filter input for XSS before we send it to the database
			//This way legit users won't have to deal with this process when filling in the form
			$senderEmail = $this->security->xss_clean($this->input->post('senderEmail'));
			$senderName = $this->security->xss_clean($this->input->post('senderName'));
			$recipientEmail = $this->security->xss_clean($this->input->post('recipientEmail'));
			$recipientName = $this->security->xss_clean($this->input->post('recipientName'));
			$burnType = $this->security->xss_clean($this->input->post('burnType'));

			//push to data array
			$data['senderEmail'] = $senderEmail;
			$data['senderName'] = $senderName;
			$data['recipientEmail'] = $recipientEmail;
			$data['recipientName'] = $recipientName;
			$data['burnType'] = $burnType;

			//Define the burntype
			switch ($burnType) {
					case 1: 
						//Never burn
						$burnDate = date_create();
						break;
					case 2: 
						//Burn after reading
						$burnDate = date_create();
						break;
					case 3: 
						//Burn after 24 hours
						$burnDate = date_create();
						date_modify($burnDate, '+1 day');
						break;
					case 4: 
						//Burn after 48 hours
						$burnDate = date_create();
						date_modify($burnDate, '+2 days');
						break;
					case 5: 
						//Burn after 72 hours
						$burnDate = date_create();
						date_modify($burnDate, '+3 days');
						break;
					}

			//Set and format burn date 
			$burnDate = date_format($burnDate, 'Y-m-d H:i:s');
			$data['burnDate'] = $burnDate;
			
			//Set status to 1
			$data['status'] = '1';

			//Send the entire data array to our model
			$this->cycret_model->set_cycret($data);
			
			//Send email invitation to recipient
			$html_email = $this->load->view('email_initiate_to', $data, true);
			$this->load->library('email');
			$config['mailtype'] = 'html';
			$config['validate'] = FALSE;
			$this->email->initialize($config);
			$this->email->from($senderEmail, $senderName);
			$this->email->to($recipientEmail); 

			$this->email->subject($senderName. ' wants to send you a secure message through Cycret');
			$this->email->message($html_email); 
			$this->email->send();

			//Load confirmation message
			$this->load->view('header');
			$this->load->view('cycret_success_create', $data);
			$this->load->view('footer');
			}
		}


//Bob visits URL, chooses yes or no.
//On yes, send public key to Alice, display private key to Bob 
//sends Alice msg that she can enter msg and key, update status to 2
//On no, delete everything and notify Alice that Bob declined

	function initiate() 
	{
		//Break up the url to an array and check if it contains url
		$uri = $this->uri->uri_to_assoc();

		if (array_key_exists('url', $uri)) 
		{
			//UniqueUrl functions as our index throughout the system
			$uniqueUrl = $uri['url'];

			//Check if the url exists in our database
			$counter = $this->cycret_model->cycret_exists($uniqueUrl); 
			

			//If it exists the system should return 1
			if ($counter == 1) 
			{
				//Check if status is 1, otherwise the system would return a new url and keys 
				$query = $this->cycret_model->get_cycret($uniqueUrl); 
				$status = $query->status;

				if ($status == 1)
				{
					//Check if uri array contains accept
					if (array_key_exists('accept', $uri)) 
					{
						//Accept is yes
						if ($uri['accept'] == 'yes') 
						{
							//Generate the modulo, public and private keys
							$keys = $this->rsa->generate_keys();
							$modulo = $keys[0];
							$publicKey = $keys[1];
							$privateKey = $keys[2];

							$keypair = str_split($privateKey,4);
							$keypair1 = $keypair[0];
							$keypair2 = $keypair[1];

							$status = "2";

        	 			//Update the database with the keys
							//For security reasons the private key isn't stored in the databse.
							$this->load->model('cycret_model');
							$this->cycret_model->updateCycret($uniqueUrl, $status, $modulo, $publicKey, $privateKey);

							$query = $this->cycret_model->get_cycret($uniqueUrl); 
							$recipientName = $query->recipientName;
							$recipientEmail = $query->recipientEmail;
							$senderName = $query->senderName;
							$senderEmail = $query->senderEmail;
							$publicKey = $query->publicKey;

							$data = array('uniqueUrl' => $uniqueUrl, 'senderName'=> $senderName, 'senderEmail' => $senderEmail, 'publicKey'=> $publicKey );
							$publickey_email = $this->load->view('email_send_publickey', $data, true);

							//Email public key to Alice
							$this->load->library('email');
							$config['mailtype'] = 'html';
							$this->email->initialize($config);
							$this->email->from($recipientEmail, 'Cycret');
							$this->email->to($recipientEmail); 
							$this->email->subject($recipientName . ' accepted, please enter your message');
							$message = 'Dear '.$senderName.', Your public key is' .$publicKey;
							$this->email->message($publickey_email); 
							$this->email->send();

							//Show private key to Bob
							
							//$data = array('modulo' => $modulo, 'publicKey' => $publicKey, 'privateKey' => $privateKey);
							$data = array('modulo' => $modulo, 'publicKey' => $publicKey, 'keypair1' => $keypair1, 'keypair2' => $keypair2);
							$this->load->view('header');
							$this->load->view('cycret_accept', $data);
							$this->load->view('footer');
						} 
						//Accept is no
						elseif ($uri['accept'] == 'no') 
						{
							$query = $this->cycret_model->get_cycret($uniqueUrl); 
							$recipientName = $query->recipientName;
							$recipientEmail = $query->recipientEmail;
							$senderName = $query->senderName;
							$senderEmail = $query->senderEmail;

							$data = array('senderName' => $senderName, 'senderEmail' => $senderEmail, 'recipientName' => $recipientName, 'recipientEmail' => $recipientEmail);

							//Email Alice that Bob declined
							$html_email = $this->load->view('email_declined', $data, true);
							$this->load->library('email');
							$config['mailtype'] = 'html';
							$config['validate'] = FALSE;
							$this->email->initialize($config);
							$this->email->from($recipientEmail, 'Cycret');
							$this->email->to($senderEmail); 

							$this->email->subject($recipientName . 'declined your message');
							$this->email->message($html_email); 
							$this->email->send();

							//Delete everything from database
							$this->cycret_model->burn($uniqueUrl);

							//Display message to Bob
							$message['error_message'] = "You chose to decline the message. The sender has been notified.";
							$this->load->view('header');
							$this->load->view('cycret_error', $message);
							$this->load->view('footer');
						} else {
							//Display error message when accept is not yes or no
							$message['error_message'] = "The URL you entered is not valid. Please check the email we've sent you.";
							$this->load->view('header');
							$this->load->view('cycret_error', $message);
							$this->load->view('footer');
						}
					} else {
						//No accept in URL, show form where user can enter URL
						$query = $this->cycret_model->get_cycret($uniqueUrl); 

						$recipientName = $query->recipientName;
						$recipientEmail = $query->recipientEmail;
						$senderName = $query->senderName;
						$senderEmail = $query->senderEmail;
						$publicKey = $query->publicKey;
						
						$data = array('uniqueUrl' => $uniqueUrl, 'senderName'=> $senderName, 'senderEmail' => $senderEmail, 'recipientName' => $recipientName, 'publicKey'=> $publicKey );
						$this->load->view('header');
						$this->load->view('cycret_initiate_accept', $data);
						$this->load->view('footer');
					}
				} else {
					//Display error message when message is already accepted
					$message['error_message'] = "The message was already accepted and can't be accepted or declined anymore.";
					$this->load->view('header');
					$this->load->view('cycret_error', $message);
					$this->load->view('footer');
				}			
			} else {
				//Show form where user can enter URL
				$this->load->view('header');
				$this->load->view('cycret_initiate_url');
				$this->load->view('footer');
			}			
		} else {
			//Display error message when URL is invalid
			$message['error_message'] = "The URL you entered is not valid. Please check the email we've sent you.";
			$this->load->view('header');
			$this->load->view('cycret_error', $message);
			$this->load->view('header');
		}	
	}


//Alice enters message and encryption key, sends Bob msg that message is ready for decryption, update status to 3
//85 characters max, including spaces

	function encrypt() 
	{
		//If form was submitted...
		if ($this->input->post('submit')) 
		{
	 		$message = $this->security->xss_clean($this->input->post('message'));
	 		$status = '3';
			$uniqueUrl = $this->security->xss_clean($this->input->post('uniqueUrl'));

			//Get keys from database
			$keys = $this->cycret_model->get_keys($uniqueUrl);

			$n = $keys->modulo;
			$e = $keys->publicKey;
			$d = $keys->privateKey; 
			
			//Add some random characters for short messages.
			//This way all encoded messages are the same length making breaking them more difficult in case of database security breach
			for ($i=32;$i<127;$i++) $message.=chr($i);
			
			//Encodes the message through the rsa_encrypt method
			$encoded = $this->rsa->rsa_encrypt ($message,  $e,  $n);
			
			//Update database with encrypted message
   			$this->cycret_model->updateMessage($uniqueUrl, $encoded);

			$query = $this->cycret_model->get_cycret($uniqueUrl); 

			$recipientName = $query->recipientName;
			$recipientEmail = $query->recipientEmail;
			$senderName = $query->senderName;
			$senderEmail = $query->senderEmail;
			$message = $query->message;

			$data = array('uniqueUrl' => $uniqueUrl, 'senderName' => $senderName, 'senderEmail' => $senderEmail, 'recipientName'=> $recipientName, 'recipientEmail'=> $recipientEmail, 'message'=> $message);
			
			//Email Bob that his encrypted message is ready
			$html_email = $this->load->view('email_encrypted', $data, true);
			$this->load->library('email');
			$config['mailtype'] = 'html';
			$config['validate'] = FALSE;
			$this->email->initialize($config);
			$this->email->from($senderEmail, $senderName);
			$this->email->to($recipientEmail); 

			$this->email->subject('Please decrypt your message at Cycret');
			$this->email->message($html_email); 
			$this->email->send();   			

			$this->load->view('header');
			$this->load->view('cycret_insert_success', $data);
			$this->load->view('footer');
			
 		} else {
			//Break up the url to an array and check if it contains url
			$uri = $this->uri->uri_to_assoc();

			if (array_key_exists('url', $uri)) 
			{
				$uniqueUrl = $uri['url'];

				//Check if URL exists in database 
				$counter = $this->cycret_model->cycret_exists($uniqueUrl); 

				//If it exists the system should return 1
				if ($counter == 1) 
				{	
					$query = $this->cycret_model->get_cycret($uniqueUrl); 
					$status = $query->status;

					//Check if status is 2, otherwise user could encrypt a message without keys or reuse the same keys for a new message 
					//Reusing the keys would bypass the initiate-accept procedure resulting in a less secure system
					if ($status == 2) 
					{
						//Gather variables and send to view for inserting and encrypting message
						$recipientName = $query->recipientName;
						$senderName = $query->senderName;

						$data = array('uniqueUrl' => $uniqueUrl, 'senderName' => $senderName, 'recipientName'=> $recipientName);
						$this->load->view('header');
   						$this->load->view('cycret_insert_msg', $data);
						$this->load->view('footer');

					} else {
						//Display error message when status isn't 2
						$message['error_message'] = "You can't encrypt a message at this point.";
						$this->load->view('header');
						$this->load->view('cycret_error', $message);
						$this->load->view('footer');
					}
				} else {
					//Display error message when URL is invalid
					$message['error_message'] = "The URL you entered is not valid. Please check the email we've sent you.";
					$this->load->view('header');
					$this->load->view('cycret_error', $message);
					$this->load->view('footer');
				}
			} else {
				//Show form where user can enter URL
				$this->load->view('header');
				$this->load->view('cycret_initiate_url');
				$this->load->view('footer');
			}
 		}
	}


//Bob enters URL and his private key to decrypt the message

	function decrypt() 
	{
		//Check if form was submitted
		if ($this->input->post('submit')) 
		{
			$uniqueUrl = $this->security->xss_clean($this->input->post('uniqueUrl'));
			$privateKey1 = $this->security->xss_clean($this->input->post('privateKey1'));
			$privateKey2 = $this->security->xss_clean($this->input->post('privateKey2'));
			$d = $privateKey1.$privateKey2;

			//Get variables from database for later use
			$query = $this->cycret_model->get_cycret($uniqueUrl); 
			$encoded = $query->message;
			$n = $query->modulo;
			$e = $query->publicKey;
			$burnType = $query->burnType;
			$recipientName = $query->recipientName;
			$recipientEmail = $query->recipientEmail;
			$senderName = $query->senderName;
			$senderEmail = $query->senderEmail;
			$status = '4';

			//Update the status to 4
			$this->cycret_model->updateStatus($uniqueUrl, $status);

			//Use the rsa_decrypt method for decoding the message
			$decoded = $this->rsa->rsa_decrypt($encoded, $d, $n);

			//Put everything in an array for later use
			$data = array('uniqueUrl' => $uniqueUrl, 'senderName' => $senderName, 'senderEmail' => $senderEmail, 'recipientName'=> $recipientName, 'recipientEmail'=> $recipientEmail, 'decoded'=> $decoded);

			//Email stuff
			$html_email = $this->load->view('email_decrypted', $data, true);
			$this->load->library('email');
			$config['mailtype'] = 'html';
			$config['validate'] = FALSE;
			$this->email->initialize($config);
			$this->email->from($recipientEmail, 'Cycret');
			$this->email->to($senderEmail); 

			$this->email->subject('Your message was decrypted');
			$this->email->message($html_email); 
			$this->email->send();

			//Display the decoded message
			$this->load->view('header');
			$this->load->view('cycret_decoded', $data);
			$this->load->view('footer');

			if ($burnType == 2)
			{
				//Burn after reading
				$this->cycret_model->burn($uniqueUrl);
			}

		} else {
			//Break up the url to an array and check if it contains url
			$uri = $this->uri->uri_to_assoc();

			if (array_key_exists('url', $uri)) 
			{
				$uniqueUrl = $uri['url'];

				//Check if URL exists in database  
				$counter = $this->cycret_model->cycret_exists($uniqueUrl); 

				//If it exists the system should return 1
				if ($counter == 1) 
				{
					$query = $this->cycret_model->get_cycret($uniqueUrl); 
					$status = $query->status;

					//Check if status is 3, otherwise user could attempt to decrypt a message prematurely
					if ($status >= 3) 
					{
						//Gather variables and show form where user can enter the public key
						$recipientName = $query->recipientName;
						$modulo = $query->modulo;
						$publicKey = $query->publicKey;
						$privateKey = $query->privateKey;
						$message = $query->message;

   					$data = array('uniqueUrl' => $uniqueUrl, 'message'=> $message, 'publicKey' => $publicKey, 'modulo'=> $modulo, 'privateKey'=> $privateKey);
						$this->load->view('header');
						$this->load->view('cycret_decrypt_msg', $data);
						$this->load->view('footer');
				} else {
					//Display error message when status isn't 3
					$message['error_message'] = "You can't decrypt a message at this point.";
					$this->load->view('header');
					$this->load->view('cycret_error', $message);	
					$this->load->view('footer');
				}

				} else {
					//Display error message when URL is invalid
					$message['error_message'] = "The URL you entered is not valid. Please check the email we've sent you.";
					$this->load->view('header');
					$this->load->view('cycret_error', $message);
					$this->load->view('footer');
				}
			} else {
				//Show form where user can enter URL
				$this->load->view('header');
				$this->load->view('cycret_initiate_url');
				$this->load->view('footer');
			}
		}

	}


//Burn function to delete message after reading, a set period of time, inmediately or never

	function burn() 
	{
		//Break up the url to an array and check if it contains url
		$uri = $this->uri->uri_to_assoc();

		if (array_key_exists('url', $uri)) 
		{

			$uniqueUrl = $uri['url'];

			//Check if URL exists in database
			$counter = $this->cycret_model->cycret_exists($uniqueUrl); 

			//If it exists the system should return 1
			if ($counter == 1) 
			{
				//Is the user certain that he or she wants to delete the message?
				if (array_key_exists('certain', $uri)) 
				{
					if ($uri['certain'] == 'yes') 
					{
						//Delete message and show confirmation
						$this->cycret_model->burn($uniqueUrl);
						$message['error_message'] = "Message burned succesfully, no traces left.";
						$this->load->view('cycret_error', $message);
						
					} elseif ($uri['certain'] == 'no') 
					{
						//Display message when user wasn't sure
						$message['error_message'] = "Canceled burning the message";
						$this->load->view('cycret_error', $message);
					} else 
					{
						//Display error message when URL is invalid
						$message['error_message'] = "The URL you entered is not valid. Please check the email we've sent you.";
						$this->load->view('cycret_error', $message);
					}
				} else {
					$data = array('uniqueUrl' => $uniqueUrl);
					$this->load->view('header');
					$this->load->view('cycret_burn', $data);
					$this->load->view('footer');
				}
			} else {
				//Display error message when URL is invalid
				$message['error_message'] = "The URL you entered is not valid. Please check the email we've sent you.";
				$this->load->view('header');
				$this->load->view('cycret_error', $message);
				$this->load->view('footer');
			}
		} else {
			//Show form where user can enter URL
			$this->load->view('header');
			$this->load->view('cycret_initiate_url');
			$this->load->view('footer');
		}
	}


//URL function for users who can't access their email but remember their unique URl
//Upon submission, user is being redirected to the proper function depending on the status

	function url() 
	{
		if ($this->input->post('submit')) 
		{
			$this->form_validation->set_rules('uniqueUrl', 'URL', 'required');

			//If form validation fails, return to form again
			if ($this->form_validation->run() == FALSE)
			{
				$this->load->view('header');
				$this->load->view('cycret_url');
				$this->load->view('footer');
			}
			//If form validates...
			else
			{
				$uniqueUrl = $this->security->xss_clean($this->input->post('uniqueUrl'));
				
				//Check if URL exists in database
				$counter = $this->cycret_model->cycret_exists($uniqueUrl);

				//If it exists the system should return 1
				if ($counter == 1 ) 
				{
					$query = $this->cycret_model->get_cycret($uniqueUrl); 
					$status = $query->status;

					switch ($status) 
					{
						case 0: 
							redirect('/cycret/create', 'refresh');
							break;
						case 1: 
							redirect('/cycret/initiate/url/'.$uniqueUrl, 'refresh');
							break;
						case 2: 
							redirect('/cycret/encrypt/url/'.$uniqueUrl, 'refresh');
							break;
						case 3: 
							redirect('/cycret/decrypt/url/'.$uniqueUrl, 'refresh');
							break;
						case 4: 
							redirect('/cycret/decrypt/url/'.$uniqueUrl, 'refresh');
							break;
					}
					
				} else {
					//Display error message when URL is invalid
					$message['error_message'] = "The URL you entered is not valid. Please check the email we've sent you.";
					$this->load->view('header');
					$this->load->view('cycret_error', $message);
					$this->load->view('footer');
				}
			} 
		} else {
			//Show form
			$this->load->view('header');
			$this->load->view('cycret_url');
			$this->load->view('footer');
		}
	}

}

/**
* Copyright (c) Dirk de Man, 2012
*
* 			***DISCLAIMER***
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
*/
