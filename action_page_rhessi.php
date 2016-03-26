<?php 

#Original Author: 
#	Nalinaksh Gaur
#	email: ng294@njit.edu
#Description:
#	php script to download the rhessi data in txt format to the AFS machine	

      $website = "http://hesperia.gsfc.nasa.gov";
        
      exec('mkdir rhessi');

	      $targetFile = 'hessi_flare_list_201406.txt';

	  $FTPFilePath = $website . '/' . 'hessidata/dbase/' . $targetFile;

        $fh = fopen(dirname(__FILE__) . '/rhessi/'.$targetFile,'w+b');
        if ($fh == FALSE){ 
           print "File not opened<br>"; 
           exit; 
        }

	Myfunc($FTPFilePath, $targetFile, $fh);
	

    #php curl function with servername, filepath, to download the file 
    function MyFunc($FTPFilePath, $targetFile, $fh)
    {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $FTPFilePath); #input
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_VERBOSE, 1);
    curl_setopt($curl, CURLOPT_FILE, $fh); #output
    curl_exec($curl);
#    echo curl_error($curl);
    curl_close($curl);

    fclose($fh);

   }
?>
