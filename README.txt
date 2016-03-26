Original Author: 
	Nalinaksh Gaur
	email: ng294@njit.edu
Description:
	Readme file

--> Test script to download files directly to browser:
    1. Copy both index.html and action_page.php into public_html dir 
    2. Access and downloads files directly on web browser

--> To download GOES Flux data files:
    1. Copy both goes15.html and action_page_goes15.php into public_html dir 
    2. Rename goes15.html as index.html
    3. Select start/end dates from web interface and click on Download button
    4. Files are downloaded into the newly created "downloads" directory


--> To download RHESSI file from command line:
    1. run the following script from command line 
       php -f action_page_rhessi.php

--> To download hek files from command line:
    1. run the following script from command line 
       php -f action_page_hek_v2.php
