<?php 

#Original Author: 
#	Nalinaksh Gaur
#	email: ng294@njit.edu
#Description:
#	php script to download the user queried satdat data to the browser	

set_time_limit(0);

    $website = $_POST["FTPServer"];
    $syear = $_POST["SYear"];
    $eyear = $_POST["EYear"];

    if($website == "ftp://ftp.swpc.noaa.gov")
    {
        
      exec('mkdir downloads');

      for($year = $syear ; $year <= $eyear; $year++)
      {
        if($year >= "1966" && $year <= "1995")
        {
          $targetFile = $year . '_RSGA.tar.gz';
	  $FTPFilePath = $website . '/' . 'pub/warehouse/' . $targetFile;
        } else if($year >= "1996" && $year <= "2015")
        {
          $targetFile = $year . '_SGAS.tar.gz';
	  $FTPFilePath = $website . '/' . 'pub/warehouse/' . $year . '/' . $targetFile;
        }

        $fh = fopen(dirname(__FILE__) . '/downloads/'.$targetFile,'w+b');
        if ($fh == FALSE){ 
           print "File not opened<br>"; 
           exit; 
        }
        #call php curl function with servername, filepath, to download the file 
	Myfunc($FTPFilePath, $targetFile, $fh);
	
      }

      #create a tar archieve of all downloaded files and send it to browser

      exec('tar cvzf downloads.tar.gz downloads');
    
    $fileName = 'downloads.tar.gz';
    $filePath = dirname(__FILE__) . '/' . $fileName;
        if(file_exists($filePath)) {
	   #$fileName = basename($filePath);
	   $fileSize = filesize($filePath);

				            // Output headers.
           header("Cache-Control: private");
           header("Content-Type: application/x-tar");
           header("Content-Length: ".$fileSize);
           header("Content-Disposition: attachment; filename=".$fileName);

           // Output file.
           readfile ($filePath);
           fclose($filePath);
           unlink($filePath);
	   exec('rm -rf downloads');
           exit();
        }
       else {
           die('Theprovided file path is not valid.');
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
