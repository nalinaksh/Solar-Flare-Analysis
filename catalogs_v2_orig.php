<?php

  ini_set('memory_limit', '-1');
  set_time_limit(0);

  $sday = $_POST["SDay"];
  $eday = $_POST["EDay"];
  $shour = $_POST["SHours"];
  $smin = $_POST["SMin"];
  $ssec = $_POST["SSec"];
  $ehour = $_POST["EHours"];
  $emin = $_POST["EMin"];
  $esec = $_POST["ESec"];

  $start_date = '2014-06-' . $sday . ' ' . $shour . ':' . $smin . ':' . $ssec;
  $end_date = '2014-06-' . $eday . ' ' . $ehour . ':' . $emin . ':' . $esec;

   #echo "<table style='border: solid 1px black;'>";
   #echo "<tr><th>SectionNo</th><th>CourseNo</th><th>CourseName</th><th>Weekday</th><th>Time</th></tr>";

#credentials
$servername = "sql.njit.edu";
$username = "ng294";
$password = "D2KL8QD3M";

  function toRadians($angle)
  {
    return $angle * (pi()/180);
  }

$sun = 960;

try {
    $conn = new PDO("mysql:host=$servername;dbname=ng294", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    #echo "Connected successfully";
    }
    catch(PDOException $e)
    {
        echo "Connection failed: " . $e->getMessage();
    }

         $sql = "SELECT xray as class, begin_time as st, max_time as mt, end_time as et, region as AR, location as coor 
	         FROM noaa_catalog 
		 WHERE begin_time >= '$start_date'
		 AND 
		 end_time <= '$end_date'"; 

	 $stmt = $conn->prepare($sql);
	 $stmt->execute();
	 $res = $stmt->setFetchMode(PDO::FETCH_NUM);
	 echo "<!DOCTYPE html>";
	 echo "<html>";
	 echo "<head>";
	 
	 echo '<style>';
	 echo '#wrapper1{ }';
	 echo 'table{ width:100%; border:1px solid black; }';
	 echo 'th{ width:12.5%; border:1px solid black;}';
	 #echo 'td{ width:250px; text-align: center; border:1px solid black;}';
	 echo 'td{ width:12.5%; text-align: center; border:1px solid black;}';
         echo 'thead>tr{ position:relative; display:block;}';
	 echo 'tbody{ display:block; height:200px; overflow:auto;}';
	 echo '</style>';

	 echo '<style>';
	 echo '#wrapper2{ }';
	 echo 'table{ width:250px; border:1px solid black; }';
	 echo 'th{ width:250px; border:1px solid black;}';
	 #echo 'td{ width:250px; text-align: center; border:1px solid black;}';
	 echo 'td{ width:250px; text-align: center; white-space:nowrap; border:1px solid black;}';
         echo 'thead>tr{ position:relative; display:block;}';
	 echo 'tbody{ display:block; height:200px; overflow:auto;}';
	 echo '</style>';
	 
	 echo '<style>';
	 echo '#wrapper3{ }';
	 echo 'table{ width:250px; border:1px solid black; }';
	 echo 'th{ width:250px; border:1px solid black;}';
	 #echo 'td{ width:250px; text-align: center; border:1px solid black;}';
	 echo 'td{ width:250px; text-align: center; white-space:nowrap; border:1px solid black;}';
         echo 'thead>tr{ position:relative; display:block;}';
	 echo 'tbody{ display:block; height:200px; overflow:auto;}';
	 echo '</style>';
	 
	 echo "</head>";
	 echo "<body background=WBG.jpg>";
	 echo "<h3> NOAA Catalog </h3>";
	 echo '<form action="plotdata_goes15_v2.php" method="post">';
	 #echo '<div style="height:100px;overflow:auto;">';
	 echo '<div id="wrapper1">';
	 echo "<table>";
	 echo "<thead>";
	 echo '<tr>';
	 echo '<th>GOES Class</th>';
	 echo '<th>Start</th>';
	 echo '<th>Peak</th>';
	 echo '<th>End</th>';
	 echo '<th>Active Region</th>';
	 echo '<th>Coordinates</th>';
	 echo '<th>X</th>';
	 echo '<th>Y</th>';
	 echo '</tr>';
	 echo "</thead>";
	 echo "<tbody>";
	 while($row = $stmt->fetch()) {
	   $x = '';
	   $y = '';
	   if($row[5] != '')
	   {
             $arr = str_split($row[5]);
	     $dir1 = $arr[0];
	     $alpha = $arr[1] . $arr[2];
	     $alpha = toRadians($alpha); 
	     $dir2 = $arr[3];
	     $beta = $arr[4] . $arr[5];
	     $beta = toRadians($beta);
	     
	     if($dir1 == "N")
	     {
	       $y = $sun * sin($alpha);
	     }
	     else if($dir1 == "S")
	     {
	       $y = 0 -  $sun * sin($alpha);
	     }

	     if($dir2 == "W")
	     {
	       $x = $sun * cos($alpha) * sin($beta);
	     }
	     else if($dir2 == "E")
	     {
	       $x = 0 -  $sun * cos($alpha) * sin($beta);
	     }
	   }
	   else
	   {
	       $x = '';
	       $y = '';
	   }


	   $event_value = $row[1] . ' ' . $row[3];
	   $temp = explode(" ", $event_value);
	   $event_value = $temp[0] . '_' . $temp[1] . '_' . $temp[2] . '_' . $temp[3] . '_' . $row[4] . '_' . $x . '_' . $y;
	   #echo $event_value;
	   #$echo 'br';
	   #$event_name = $row[0] . "\t" . $row[1] . "\t" . $row[2] . "\t" . $row[3] . "\t" . $row[4] . "\t" . $row[5];
	   
	   echo '<tr><td><input type="radio" name="group1" value=' . $event_value . '> ' . $row[0] . '';
	   echo "</td>";
	   echo "<td> $row[1]";
	   echo "</td>";
	   echo "<td> $row[2]";
	   echo "</td>";
	   echo "<td> $row[3]";
	   echo "</td>";
	   echo "<td> $row[4]";
	   echo "</td>";
	   echo "<td> $row[5]";
	   echo "</td>";
	   if($x != '')
	   {
	     $X = round($x,2);
	   }
	   else
	   {
	     $X = '';
	   }
	   echo "<td> $X";
	   echo "</td>";
	   if($y != '')
	   {
	     $Y = round($y,2);
	   }
	   else
	   {
	     $Y = '';
	   }
	   echo "<td> $Y";
	   echo "</td>";
	   echo "</tr>";
	   #echo '<br>';
	 }
	 echo "</tbody>";
	 echo "</table>";
	 echo '</div>';
         echo '<input type = "submit" value="Plotdata">';
	 echo '</form>'; 
         
	 $sql = "SELECT FLARE, StartTime, PeakTime, EndTime, AR, XPos, YPos 
	         FROM rhessi 
		 WHERE StartTime >= '$start_date'
		 AND 
		 EndTime <= '$end_date'"; 

	 $stmt = $conn->prepare($sql);
	 $stmt->execute();
	 $res = $stmt->setFetchMode(PDO::FETCH_NUM);

	 echo "<h3> RHESSI Catalog </h3>";
	 echo '<form action="plotdata_goes15_v2.php" method="post">';
	 #echo '<div style="height:100px;overflow:auto;">';
	 echo '<div id="wrapper2">';
	 echo "<table>";
	 echo "<thead>";
	 #echo '<col width="100">';
	 #echo '<col width="150">';
	 #echo '<col width="150">';
	 #echo '<col width="150">';
	 #echo '<col width="50">';
	 #echo '<col width="50">';
	 #echo '<col width="50">';
	 echo "<tr>";
	 echo "<th>FLARE#</th>";
	 echo "<th>Start</th>";
	 echo "<th>Peak</th>";
	 echo "<th>End</th>";
	 echo "<th>AR</th>";
	 echo "<th>XPos</th>";
	 echo "<th>YPos</th>";
	 echo "</tr>";
	 echo "<thead>";

	 echo "<tbody>";
	 while($row = $stmt->fetch()) {
	   $event_value = $row[1] . ' ' . $row[3];
	   $temp = explode(" ", $event_value);
	   $event_value = $temp[0] . '_' . $temp[1] . '_' . $temp[2] . '_' . $temp[3] . '_' . $row[4] . '_' . $row[5] . '_' . $row[6];
	   #echo $event_value;
	   #$echo 'br';
	   #$event_name = $row[0] . "\t" . $row[1] . "\t" . $row[2] . "\t" . $row[3] . "\t" . $row[4] . "\t" . $row[5];
	   echo '<tr><td><input type="radio" name="group1" value=' . $event_value . '> ' . $row[0] . '';
	   echo "</td>";
	   echo "<td> $row[1]";
	   echo "</td>";
	   echo "<td> $row[2]";
	   echo "</td>";
	   echo "<td> $row[3]";
	   echo "</td>";
	   echo "<td> $row[4]";
	   echo "</td>";
	   echo "<td> $row[5]";
	   echo "</td>";
	   echo "<td> $row[6]";
	   echo "</td>";
	   echo "</tr>";
	   #echo '<br>';
	 }
         echo "</tbody>";
	 echo "</table>";
         echo '</div>';
	 echo '<input type = "submit" value="Plotdata">';
	 echo '</form>'; 


	 $sql = "SELECT obs_observatory, obs_instrument, obs_channelid, event_starttime, event_peaktime, event_endtime, ar_noaanum, hpc_x, hpc_y, noposition 
	         FROM hek2
		 WHERE event_starttime >= '$start_date'
		 AND 
		 event_endtime <= '$end_date'"; 

	 $stmt = $conn->prepare($sql);
	 $stmt->execute();
	 $res = $stmt->setFetchMode(PDO::FETCH_NUM);

	 echo "<h3> HEK Register Catalog </h3>";
	 echo '<form action="plotdata_goes15_v2.php" method="post">';
	 #echo '<div style="height:100px;overflow:auto;">';
	 echo '<div id="wrapper3">';
	 echo "<table>";
	 echo "<thead>";
	 #echo '<col width="100">';
	 #echo '<col width="180">';
	 #echo '<col width="150">';
	 #echo '<col width="150">';
	 #echo '<col width="50">';
	 #echo '<col width="50">';
	 #echo '<col width="50">';
	 echo "<tr>";
	 #echo "<th>FLARE</th>";
	 echo "<th>obs_observatory</th>";
	 echo "<th>obs_instrument</th>";
	 echo "<th>obs_channelid</th>";
	 echo "<th>Start</th>";
	 echo "<th>Peak</th>";
	 echo "<th>End</th>";
	 echo "<th>ar_noaanum</th>";
	 echo "<th>hpc_x</th>";
	 echo "<th>hpc_y</th>";
	 echo "</tr>";
         echo "</thead>";
	 echo "<tbody>";
	 while($row = $stmt->fetch()) {
	   $event_value = $row[3] . ' ' . $row[5];
	   $temp = explode(" ", $event_value);
	   if($row[9] == 0)
	   {
	     $hpc_x = round($row[7], 2);
	     $hpc_y = round($row[8], 2);
	   }
	   else
	   {
	     $hpc_x = '';
	     $hpc_y = '';
	   }
	   $event_value = $temp[0] . '_' . $temp[1] . '_' . $temp[2] . '_' . $temp[3] . '_' . $row[6] . '_' . $hpc_x . '_' . $hpc_y;
	   #echo $event_value;
	   #$echo 'br';
	   #$event_name = $row[0] . "\t" . $row[1] . "\t" . $row[2] . "\t" . $row[3] . "\t" . $row[4] . "\t" . $row[5];
	   echo '<tr><td><input type="radio" name="group1" value=' . $event_value . '> ' . $row[0] . '';
	   echo "</td>";
	   echo "<td> $row[1]";
	   echo "</td>";
	   echo "<td> $row[2]";
	   echo "</td>";
	   echo "<td> $row[3]";
	   echo "</td>";
	   echo "<td> $row[4]";
	   echo "</td>";
	   echo "<td> $row[5]";
	   echo "</td>";
	   echo "<td> $row[6]";
	   echo "</td>";
	   echo "<td> $hpc_x";
	   echo "</td>";
	   echo "<td> $hpc_y";
	   echo "</td>";
	   echo "</tr>";
	 }
	 echo "</tbody>";
	 echo "</table>";
         echo '</div>';
	 echo '<input type = "submit" value="Plotdata">';
	 echo '</form>'; 
         echo "</body>";
	 echo "</html>";
$conn = null;

?>
