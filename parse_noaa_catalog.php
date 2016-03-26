<?php

#Original Author: 
#	Nalinaksh Gaur
#	email: ng294@njit.edu
#Description:
#	php script to parse noaa catalog data stored in txt format	

#credentials
$servername = "sql.njit.edu";
$username = "ucid";
$password = "database-password";

try {
    $conn = new PDO("mysql:host=$servername;dbname=ucid", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    #echo "Connected successfully";
    }
    catch(PDOException $e)
    {
        echo "Connection failed: " . $e->getMessage();
    }


 $file = fopen('NOAA_June_2014.txt',"r");

$temp = fgets($file);
#echo $temp;

while(! feof($file))
{
    $temp = fgets($file);
    
    $parts = preg_split('/\s+/', $temp);

    #col#1 date
    $date = $parts[0];
    $date = (string)$date;

    $dd = $date[6] . $date[7];
    $M = $date[4] . $date [5];
    $Y = $date[0] . $date[1] . $date[2] . $date[3];

    #col#2 begin time
    $begin_time = $parts[1];
    if($begin_time == 'NoData')
    {
        $bt = "";
    }
    else
    {
    $begin_time = (string)$begin_time;
    $HH = $begin_time[0] . $begin_time[1];
    $MM = $begin_time[2] . $begin_time[3];

    $bt = $Y . '-' . $M . '-' . $dd . ' ' . $HH . ':' . $MM . ':' . '00';
    }

    #col#3 max time
    $max_time = $parts[2];
    $max_time = (string)$max_time;
    if ($max_time == 'NoData')
    {
         $mt = "";
    }
    else
    {
    $HH = $max_time[0] . $max_time[1];
    $MM = $max_time[2] . $max_time[3];

    $mt = $Y . '-' . $M . '-' . $dd . ' ' . $HH . ':' . $MM . ':' . '00';
    }

    #col#4 end time
    $end_time = $parts[3];
    $end_time = (string)$end_time;
    if($end_time == 'NoData')
    {
        $et = "";
    }
    else
    {
    $HH = $end_time[0] . $end_time[1];
    $MM = $end_time[2] . $end_time[3];

    $et = $Y . '-' . $M . '-' . $dd . ' ' . $HH . ':' . $MM . ':' . '00';
    }

    $region = $parts[4];
    if($region == 'NoData')
    {
      $region = 0;
    }

    $location = $parts[5];
    if($location == 'NoData')
    {
      $location = "";
    }

    $xray = $parts[6];
    if($xray == 'NoData')
    {
      $xray = ""; 
    }

    $op = $parts[7];
    if($op == 'NoData')
    {
      $op = "";
    }

    $mhz = $parts[8];
    if($mhz == 'NoData')
    {
      $mhz = "";
    }

    $cm = $parts[9];
    if($cm == 'NoData')
    {
      $cm = "";
    }

    $sweep = $parts[10];
    if($sweep == 'NoData')
    {
      $sweep = "";
    }

    echo $bt . "\t" . $mt . "\t" . $et . "\t" . $region . "\t" . $location . "\t" . $xray . "\t" . $op . "\t" . $mhz . "\t" . $cm . "\t" . $sweep . "\n";
    
    #insert data into noaa_catalog table in ucid database
    $sql = "INSERT INTO noaa_catalog VALUES('$bt', '$mt', '$et', $region, '$location', '$xray', '$op', '$mhz', '$cm', '$sweep')";
	 $stmt = $conn->prepare($sql);
	 $stmt->execute();
	 $res = $stmt->setFetchMode(PDO::FETCH_NUM);
}

$conn = null;

fclose($file);
?>
