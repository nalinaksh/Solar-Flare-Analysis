<?php 

#Original Author: 
#	Nalinaksh Gaur
#	email: ng294@njit.edu
#Description:
#	php script to download the user queried GOES flux data to the AFS machine	

set_time_limit(0);

    $website = $_POST["Server"];
    $sday = $_POST["SDay"];
    $eday = $_POST["EDay"];

    if($website == "http://satdat.ngdc.noaa.gov")
    {
        
      exec('mkdir downloads');

      for($day = $sday ; $day <= $eday; $day++)
      {
          if($day >=1 && $day <= 9)
              $targetFile = 'g15_xrs_2s_2014060' . $day . '_2014060' . $day . '.csv';
          else
	      $targetFile = 'g15_xrs_2s_201406' . $day . '_201406' . $day . '.csv';

	  $FTPFilePath = $website . '/' . 'sem/goes/data/new_full/2014/06/goes15/csv/' . $targetFile;

        $fh = fopen(dirname(__FILE__) . '/downloads/'.$targetFile,'w+b');
        if ($fh == FALSE){ 
           print "File not opened<br>"; 
           exit; 
        }

        #call php curl function with servername, filepath, to download the file 
	Myfunc($FTPFilePath, $targetFile, $fh);
	
      }

    }
    else
    {
        print "FTP Server not supported";
	exit;
    }

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
