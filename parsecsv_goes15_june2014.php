<?php

#Original Author: 
#	Nalinaksh Gaur
#	email: ng294@njit.edu
#Description:
#	php script to parse GOES flux data stored in csv format	

#credentials
$servername = "sql.njit.edu";
$username = "ucid";
$password = "database-password";

try {
    $conn = new PDO("mysql:host=$servername;dbname=ucid", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
    }
   catch(PDOException $e)
   {
       echo "Connection failed: " . $e->getMessage();
   }


for($day=1; $day<=30; $day++)
{
  #open files one by one and skips to from where actual data starts, marker "data:"
  if($day >=1 && $day <= 9)
        $targetFile = 'g15_xrs_2s_2014060' . $day . '_2014060' . $day . '.csv';
  else
        $targetFile = 'g15_xrs_2s_201406' . $day . '_201406' . $day . '.csv';

 $file = fopen($targetFile,"r");

  while(! feof($file))
  {
    $temp = fgets($file);
  
    if (strpos($temp,'data:') !== false)
    {
      echo $temp;
      break;
    }
  }


  $temp = fgets($file);
  #echo $temp;

  #start parsing comma separated values
  while(! feof($file)) {
     $array = fgetcsv($file);

#    foreach($array as $i => $value) {
#        echo $array[$i] . "\n"; 
#    }

    $time_tag = explode(".",$array[0]);
    echo $time_tag[0] . "\n"; #contains time in datetime format
#    echo $time_tag[1] . "\n"; #contains the after decimal part of seconds




    $A_QUAL_FLAG = $array[1]; #A_QUAL_FLAG
#    echo $A_QUAL_FLAG . "\n";

   $A_COUNT_temp = explode("+",$array[2]);
   #echo $A_COUNT_temp[0] . "\n";
   #echo $A_COUNT_temp[1] . "\n";

   $arr = preg_split('/(?<=[0-9])(?=[a-z]+)/i',$A_COUNT_temp[0]);   
   #print_r($arr);

   $A_COUNT = $arr[0] * pow(10, $A_COUNT_temp[1]); #A_COUNT
#   echo $A_COUNT . "\n";




   $A_FLUX_temp = explode("-",$array[3]);
   #echo $A_FLUX_temp[0] . "\n";
   #echo $A_FLUX_temp[1] . "\n";

   $arr = preg_split('/(?<=[0-9])(?=[a-z]+)/i',$A_FLUX_temp[0]);   
   #print_r($arr);

   $A_FLUX = $arr[0] * pow(10, -$A_FLUX_temp[1]); #A_FLUX
#   echo $A_FLUX . "\n";




   $B_QUAL_FLAG = $array[4]; #B_QUAL_FLAG
#   echo $B_QUAL_FLAG . "\n";



   $B_COUNT_temp = explode("+",$array[5]);
   #echo $B_COUNT_temp[0] . "\n";
   #echo $B_COUNT_temp[1] . "\n";

   $arr = preg_split('/(?<=[0-9])(?=[a-z]+)/i',$B_COUNT_temp[0]);   
   #print_r($arr);

   $B_COUNT = $arr[0] * pow(10, $B_COUNT_temp[1]); #B_COUNT
 #  echo $B_COUNT . "\n";




   $B_FLUX_temp = explode("-",$array[6]);
   #echo $B_FLUX_temp[0] . "\n";
   #echo $B_FLUX_temp[1] . "\n";

   $arr = preg_split('/(?<=[0-9])(?=[a-z]+)/i',$B_FLUX_temp[0]);   
   #print_r($arr);

   $B_FLUX = $arr[0] * pow(10, -$B_FLUX_temp[1]); #B_FLUX
#   echo $B_FLUX . "\n";
   
   #insert data into goes15 table in ucid database
   $sql = "INSERT INTO goes15 VALUES ('$time_tag[0]', $A_QUAL_FLAG, $A_COUNT, $A_FLUX, $B_QUAL_FLAG, $B_COUNT, $B_FLUX)"; 
   $conn->exec($sql);
  } # end of while loop, parsed all the lines
} #end of for loop, parsed all the 30 files

$sql = "SELECT time_tag, A_FLUX, B_FLUX FROM goes15";
$result = $conn->query($sql);

$conn = null;

fclose($file);
?>
