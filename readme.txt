* Name:  	CodeIgniter RSA readme file
* Author:	Dirk de Man
*				dirk at dirktheman . com
*         	@dirktheman
*
* Created:  	05.10.2012

TABLE OF CONTENTS:
1. What is it?
2. Installation
3. License


###########  1. WHAT IS IT?  ###########
This CodeIgniter libary enables users to establish secure communications with each other. It uses the
RSA algorithm and a two-pass security protocol. The message, once decrypted, can be deleted inmediately so that
there are no traces left. 

Let's suppose Alice wants to send Bob a secure message. This is how it works:

Step 1: 
Alice creates a unique URL and sends Bob an invitation. A random URL is generated.
Step 2:
Bob visits the URL, chooses Accept or Decline. 
On accept, a decryption key is displayed to Bob.
Alice receives a message that she can encrypt her message.
On decline, everything is deleted and Alice is notified that Bob didn't accept her message.
Step 3:
Alice visits the unique URL, encrypts her message. Bob receives notification that his message is ready for decryption.
Step 4:
Bob visits URL, decrypts the message, message is burnt automatically after a set period of time, immediately or never.


###########  2. INSTALLATION  ###########
1. Upload the files in the folders to the corresponding folders in your CodeIgniter application
2. Run the SQL file to create a table in your database
3. Apply your own styles/layout to the view files

###########  3. LICENSE  ###########
Copyright (c) Dirk de Man, 2012

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.