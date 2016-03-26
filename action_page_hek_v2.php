<?php 

#Original Author: 
#	Nalinaksh Gaur
#	email: ng294@njit.edu
#Description:
#	php script to download the user hek register data in xml format to the AFS machine	

    $website = "https://www.lmsal.com";
    for($day=1; $day<=30; $day++)
    {
        if($day >=1 && $day <= 9)
        {
	      #filepath to import file in xml format
	      $targetFile = 
	      '/hek/her?cosec=1&&&cmd=search&type=column&event_type=fl&event_region=all&event_coordsys=helioprojective&x1=-5000&x2=5000&y1=-5000&y2=5000&result_limit=120&event_starttime=2014-06-0' .
	      $day . 'T00:00:00&event_endtime=2014-06-0' . $day . 'T23:59:59';
        } 
	else
	{
	      #filepath to import file in xml format
	      $targetFile =
	      '/hek/her?cosec=1&&&cmd=search&type=column&event_type=fl&event_region=all&event_coordsys=helioprojective&x1=-5000&x2=5000&y1=-5000&y2=5000&result_limit=120&event_starttime=2014-06-' . $day
	      . 'T00:00:00&event_endtime=2014-06-' . $day . 'T23:59:59';
        }
        $FilePath = $website . $targetFile;
        $filename = 'hek_2014-06-' . $day . '.xml';
	exec('mkdir downloads');
        $fh = fopen(dirname(__FILE__) . '/downloads/'.$filename,'w+b');
        if ($fh == FALSE){ 
           print "File not opened<br>"; 
           exit; 
        }

	Myfunc($FilePath, $fh);
     }

    #php curl function with servername, filepath, to download the file 
    function MyFunc($FilePath, $fh)
    {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $FilePath); #input
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
