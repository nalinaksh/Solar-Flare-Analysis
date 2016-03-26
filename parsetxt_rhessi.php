<?php

#Original Author: 
#	Nalinaksh Gaur
#	email: ng294@njit.edu
#Description:
#	php script to parse rhessi catalog data stored in txt format	

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

    function func($M) {
        if ($M == "Jan")
	  $month = "01";
	else if($M == "Feb")
	  $month = "02";
	else if($M == "Mar")
	  $month = "03";
	else if($M == "Apr")
	  $month = "04";
	else if($M == "May")
	  $month = "05";
	else if($M == "Jun")
	  $month = "06";
	else if($M == "Jul")
	  $month = "07";
	else if($M == "Aug")
	  $month = "08";
	else if ($M == "Sep")
	  $month = "09";
	else if ($M == "Oct")
	  $month = "10";
	else if ($M == "Nov")
	  $month = "11";
	else if ($M == "Dec")
	  $month = "12";

        return $month;
    }

#open file and skips to from where actual data starts, marker "data:"
$file = fopen("hessi_flare_list_201406.txt","r");

while(! feof($file))
{
  $temp = fgets($file);
  
  if (strpos($temp,'Counts') !== false)
   {
     echo $temp;
     break;
   }
}


$temp = fgets($file);
#echo $temp;

while(! feof($file))
{
    $temp = fgets($file);
    
    if (trim($temp) == '' || strpos($temp, 'Notes') !== false)
    {
      exit(0);
    }
    
    $parts = preg_split('/\s+/', $temp);
    #$echo $parts[1] . "\t" . $parts[2] . "\t" . $parts[3] . "\t" . $parts[4] . "\t" . $parts[5] . "\t" . $parts[6] . "\t" . $parts[7] . "\t" . $parts[8] . "\t" . $parts[9] . "\t" .
    #$parts[10] . "\t" . $parts[11] . "\t" . $parts[12] . "\t" . $parts[13] . "\t" . $parts[14] . "\n";


    $var = explode("-", $parts[2]);
    $dd = $var[0];
    $M = $var[1];
    $Y = $var[2];

    $MM = func($M);
    $Day = $Y . "-" . $MM . "-" . $dd;


    $Flare = $parts[1];
    $StartTime = $Day . " " . $parts[3];
    $PeakTime = $Day . " " . $parts[4];
    $EndTime = $Day . " " . $parts[5];
    $Duration = $parts[6];
    $Peak = $parts[7];
    $TotalCounts = $parts[8];
    #$e = explode("-", $parts[9]);
    #$Energy = $e[0] 
    $XPos = $parts[10];
    $YPos = $parts[11];
    $Radial = $parts[12];
    $AR = $parts[13];

    #echo $Flare . "\t" . $StartTime . "\t" . $PeakTime . "\t" . $EndTime . "\t" . $Duration . "\t" . $Peak . "\t" . $TotalCounts . "\t" . $XPos . "\t" . $YPos . "\t" . $Radial . "\t" . $AR . "\n";
    
    #insert data into goes15 table in ucid database
    $sql = "INSERT INTO rhessi VALUES($Flare, '$StartTime', '$PeakTime', '$EndTime', $Duration, $Peak, $TotalCounts, $XPos, $YPos, $Radial, $AR)";
    $conn->exec($sql);
}

#$sql = "SELECT PeakTime FROM rhessi";
#$result = $conn->query($sql);
#echo $result;
$conn = null;

fclose($file);
?>
