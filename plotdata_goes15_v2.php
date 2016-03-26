<?php

#Original Author: 
#	Nalinaksh Gaur
#	email: ng294@njit.edu
#Description:
#	php script to plot GOES flux data and the matching events as horizontal lines (also displayed at the bottom of plot)	

ini_set('memory_limit', '-1');
  set_time_limit(0);

  #fetch the event_value that was submitted in catalogs*.php file
  $tm = $_POST["group1"];
  #echo $tm;
  $times = explode("_", $tm);

   #credentials
   $servername = "sql.njit.edu";
   $username = "ucid";
   $password = "database-password";
   $sun = 960; #constant used to compute the x and y coordinates  
         
	 #function to determine if a match exists between the  AR of selected event and that of other event 
	 function matchAR($ar, $activeRegion)
	 {
	   if($activeRegion == 0 || $activeRegion == '' || $ar == 0 || $ar == '')
	     return 1;
	   
	   if($ar == $activeRegion)
	     return 1;
	   
	   return 0;
	 }
	
	#function to determine if the distance between the selected event and the other event is less than or equal to delta threshold
	 function matchPos($x, $y, $xCoord, $yCoord)
	 {

	   if( ($xCoord == '' &&  $yCoord == '') || ($x == '' || $y == '') || ($xCoord == 0 && $yCoord == 0) || ($x == 0 && $y == 0))
	   {
	     return 1;
	   }
	   
	   $d = sqrt( pow ( ($x - $xCoord), 2) + pow( ($y - $yCoord), 2) ); 
	   
	   if($d < 150)
	   {
	     return 1;
           }

	   return 0;
	 }

        #function to convert angle into radians
         function toRadians($angle)
         {
            return $angle * (pi()/180);
         }

        #function to compute the x coordinate
	 function computeX($sun, $dir2, $alpha, $beta)
	 {
	     if($dir2 == "W")
	     {
	       return ($sun * cos($alpha) * sin($beta));
	     }
	     else if($dir2 == "E")
	     {
	       return (0 -  $sun * cos($alpha) * sin($beta));
	     }
	 }

        #function to compute the y coordinate
	 function computeY($sun, $dir1, $alpha)
	 {
	     if($dir1 == "N")
	     {
	       return ($sun * sin($alpha));
	     }
	     else if($dir1 == "S")
	     {
	       return (0 -  $sun * sin($alpha));
	     }
	 }

   try {
         $conn = new PDO("mysql:host=$servername;dbname=ucid", $username, $password);
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	 
	 $start = $times[0] . ' ' . $times[1];
	 #echo "Start Time: " . $start;
	 #echo '<br>';
	 $end = $times[2] . ' ' . $times[3];
	 #echo "End Time: " . $end;

	 #echo '<br>';
	 #fetch selected event's start/end time, AR, x & y coordinates
	 $activeRegion = '';
	 $len = strlen($times[4]);
	 if($len == 5)
	 {
	    $arr = str_split($times[4]);
            $activeRegion = $arr[1] . $arr[2] . $arr[3] . $arr[4];
	 }
	 else
	 {
	   $activeRegion = $times[4];
	 }
	 $xCoord = $times[5]; 
	 $yCoord = $times[6];


	 $min_start = $start;
	 $max_end = $end;
	 $noaa_events = array();
	
	#query all the events from noaa_catalog lying between selected event's start & end time
	 $sql = "SELECT begin_time, end_time, region, location, xray, max_time from noaa_catalog WHERE (begin_time >= '$start' AND begin_time <= '$end') OR (end_time >= '$start' AND end_time <= '$end') OR (begin_time <
	 '$start' AND end_time > '$end')";
	 $stmt_noaa = $conn->prepare($sql);
	 $stmt_noaa->execute();
	 $res_noaa = $stmt_noaa->setFetchMode(PDO::FETCH_NUM);
	 $x = '';
	 $y = '';
	 $ncount = 0;
	 $noaa_times = array();
	 while($row = $stmt_noaa->fetch()) {
	   if($row[3] != '')
	   {
             $arr = str_split($row[3]);
	     $dir1 = $arr[0];
	     $alpha = $arr[1] . $arr[2];
	     $alpha = toRadians($alpha);
	     $dir2 = $arr[3];
	     $beta = $arr[4] . $arr[5];
	     $beta = toRadians($beta);

	     $x = computeX($sun, $dir2, $alpha, $beta);
	     $y = computeY($sun, $dir1, $alpha);
	   }
	   #compute the events matching the selected event
	   $reta = matchAR($row[2], $activeRegion);
	   $retp = matchPos($x, $y, $xCoord, $yCoord);
	   if($reta == 1 && $retp == 1)
	   {
	     # keep track of min start time and max end time among all the matching events
	     if($min_start > $row[0])
	     {
	        $min_start = $row[0];
	     }
	     if($max_end < $row[1])
	     {
	        $max_end = $row[1];
	     }

	     # save matched event info for further use
             $noaa_times[$ncount][0] = $row[0];
	     $noaa_times[$ncount][1] = $row[1];

	     $noaa_events[$ncount][0] = $row[4]; #xray
	     $noaa_events[$ncount][1] = $row[0]; #begin_time
	     $noaa_events[$ncount][2] = $row[5]; #max_time
	     $noaa_events[$ncount][3] = $row[1]; #end_time
	     $noaa_events[$ncount][4] = $row[2]; #region
	     $noaa_events[$ncount][5] = $row[3]; #location

             # number of matched events for noaa_catalog
	     $ncount++;
	   }
	  }
         
         $rhessi_events = array();
	#query all the events from rhessi lying between selected event's start & end time
	 $sql = "SELECT StartTime, EndTime, AR, XPos, YPos, FLARE, PeakTime from rhessi WHERE (StartTime >= '$start' AND StartTime <= '$end') OR (EndTime >= '$start' AND EndTime <= '$end') OR (StartTime < '$start' AND
	 EndTime > '$end')";
	 $stmt_rhessi = $conn->prepare($sql);
	 $stmt_rhessi->execute();
	 $res_rhessi = $stmt_rhessi->setFetchMode(PDO::FETCH_NUM);
	 $rcount = 0;
	 $rhessi_times = array();
	 while($row = $stmt_rhessi->fetch()) {
	   #compute the events matching the selected event
	     if(matchAR($row[2], $activeRegion) == 1 && matchPos($row[3], $row[4], $xCoord, $yCoord) == 1)
	     {
	     # keep track of min start time and max end time among all the matching events
	       if($min_start > $row[0])
	       {
	        $min_start = $row[0];
	       }
	       if($max_end < $row[1])
	       {
	        $max_end = $row[1];
	       }
	     # save matched event info for further use
	       $rhessi_times[$rcount][0] = $row[0];
	       $rhessi_times[$rcount][1] = $row[1];

	       $rhessi_events[$rcount][0] = $row[5]; #FLARE
	       $rhessi_events[$rcount][1] = $row[0]; #Start
	       $rhessi_events[$rcount][2] = $row[6]; #Peak
	       $rhessi_events[$rcount][3] = $row[1]; #End
	       $rhessi_events[$rcount][4] = $row[2]; #AR
	       $rhessi_events[$rcount][5] = $row[3]; #XPos
	       $rhessi_events[$rcount][6] = $row[4]; #YPos
             # number of matched events for rhessi
	       $rcount++;
	     }
	 }
	
	 $hek_events = array();
	#query all the events from hek2 lying between selected event's start & end time
	 $sql = "SELECT event_starttime, event_endtime, ar_noaanum, hpc_x, hpc_y, obs_instrument, obs_channelid, obs_observatory, event_peaktime, ref_1_name, ref_1_url, ref_2_name, ref_2_url, ref_3_name,
	 ref_3_url, ref_4_name, ref_4_url, ref_5_name, ref_5_url, ref_6_name, ref_6_url, ref_7_name, ref_7_url, noposition from hek2 WHERE (event_starttime >= '$start' AND event_starttime <= '$end') OR (event_endtime >= '$start' AND event_endtime
	 <= '$end') OR (event_starttime < '$start' AND event_endtime > '$end')";
	 $stmt_hek = $conn->prepare($sql);
	 $stmt_hek->execute();
	 $res_hek = $stmt_hek->setFetchMode(PDO::FETCH_NUM);
	 $ar_hek = '';
	 $hcount = 0;
	 $hek_times = array();
	 $hpc_x='';
         $hpc_y='';
	 while($row = $stmt_hek->fetch()) {
	     #if AR for hek is 5 digits long, ignore the 1st digit
	     $len = strlen($row[2]);
	     if($len == 5)
	     {
	       $arr = str_split($row[2]);
               $ar_hek = $arr[1] . $arr[2] . $arr[3] . $arr[4];
	     }
	     else
	     {
	       $ar_hek = $row[2];
	     }
	     # ignore x and y coor. if noposition parameter is true 
	     if($row[23] == 0)
	     {
	       $hpc_x = round($row[3], 2);
	       $hpc_y = round($row[4], 2);
	     }
	     else
	     {
	       $hpc_x = '';
	       $hpc_y = '';
	     }

	   #compute the events matching the selected event
	     if(matchAR($ar_hek, $activeRegion) == 1 && matchPos($hpc_x, $hpc_y, $xCoord, $yCoord) == 1)
             {
	     # keep track of min start time and max end time among all the matching events
	       if($min_start > $row[0])
	       {
	        $min_start = $row[0];
	       }
	       if($max_end < $row[1])
	       {
	        $max_end = $row[1];
	       }
	     # save matched event info for further use
	       $hek_times[$hcount][0] = $row[0];
	       $hek_times[$hcount][1] = $row[1];
	       $hek_times[$hcount][2] = $row[5];
	       $hek_times[$hcount][3] = $row[6];
               
	       $hek_events[$hcount][0] = $row[7]; # obs_observatory
	       $hek_events[$hcount][1] = $row[5]; # obs_instrument
	       $hek_events[$hcount][2] = $row[6]; # obs_channelid
	       $hek_events[$hcount][3] = $row[0]; # Start
	       $hek_events[$hcount][4] = $row[8]; # Peak
	       $hek_events[$hcount][5] = $row[1]; # End
	       $hek_events[$hcount][6] = $row[2]; # ar_noaanum
	       $hek_events[$hcount][7] = $hpc_x; # hpc_x
	       $hek_events[$hcount][8] = $hpc_y; # hpc_y
	       $hek_events[$hcount][9] = $row[9]; # ref_1_name 
	       $hek_events[$hcount][10] = $row[10]; # ref_1_url 
	       $hek_events[$hcount][11] = $row[11]; # ref_2_name 
	       $hek_events[$hcount][12] = $row[12]; # ref_2_url 
	       $hek_events[$hcount][13] = $row[13]; # ref_3_name 
	       $hek_events[$hcount][14] = $row[14]; # ref_3_url 
	       $hek_events[$hcount][15] = $row[15]; # ref_4_name 
	       $hek_events[$hcount][16] = $row[16]; # ref_4_url 
	       $hek_events[$hcount][17] = $row[17]; # ref_5_name 
	       $hek_events[$hcount][18] = $row[18]; # ref_5_url 
	       $hek_events[$hcount][19] = $row[19]; # ref_6_name 
	       $hek_events[$hcount][20] = $row[20]; # ref_6_url 
	       $hek_events[$hcount][21] = $row[21]; # ref_7_name 
	       $hek_events[$hcount][22] = $row[22]; # ref_7_url 
             # number of matched events for hek2
	       $hcount++;
	     }
	 }
         
	 #subtract 30min from min start time and add 30min to max end time
	 $currentDate = strtotime($min_start);
	 $futureDate = $currentDate-(60*30);
	 $start_date = date("Y-m-d H:i:s", $futureDate);
	 #echo $start_date;
	 #echo '<br>';
	 $currentDate = strtotime($max_end);
	 $futureDate = $currentDate+(60*30);
	 $end_date = date("Y-m-d H:i:s", $futureDate);
         #echo $end_date;
	 #echo '<br>';

	#query GOES table to get peaktime and peak flux value within the time range as compute in above step  
	 $sql = "SELECT time_tag, B_FLUX from goes15 WHERE time_tag >= '$start_date' AND time_tag <= '$end_date' GROUP BY time_tag HAVING B_FLUX >= ALL (SELECT B_FLUX FROM goes15 WHERE time_tag >=
	 '$start_date' AND time_tag <= '$end_date')";
	 $stmt = $conn->prepare($sql);
	 $stmt->execute();
	 $result = $stmt->setFetchMode(PDO::FETCH_NUM);
	 $row = $stmt->fetch();
	 $peaktime = $row[0];
	 $maxval = $row[1];


	#query GOES table to get all rows within the time range of interest  
	 $sql = "SELECT time_tag, A_FLUX, B_FLUX FROM goes15 WHERE time_tag >= '$start_date' AND time_tag <= '$end_date'";
         $result = $conn->query($sql);
	 if (! $result){
	    throw new My_Db_Exception('Database error: ' . mysql_error());
	    }

      #$rows = array();
      $table = array();
      $table['cols'] = array(
        array('label' => 'Date', 'type' => 'datetime'),
        array('label' => 'A-FLUX', 'type' => 'number'),
        array('label' => 'B-FLUX', 'type' => 'number'),
        #array('label' => 'EVENT', 'type' => 'number'),
    );

	 $i = 1;
	 $x = '';
	 $y = '';
	 $noaa_arr = array();
	 $mult = 1;
	 $num = 0;
	 while($num < $ncount) {
             #this is done to collect data for plotting horizontal lines for noaa matched events
	     $sql = "SELECT time_tag, A_FLUX, B_FLUX FROM goes15 WHERE time_tag >= '$start_date' AND time_tag <= '$end_date'";
             $result = $conn->query($sql);
	     if (! $result){
	       throw new My_Db_Exception('Database error: ' . mysql_error());
	     }
              
	       $event = 'NOAA_' . $i;
               $val = array('label' => $event, 'type' => 'number');
               array_push($table['cols'], $val);
	       $i = $i + 1;
              
	       $j = 0;
	       foreach($result as $r)
	       {
	         if($r['time_tag'] >= $noaa_times[$num][0] && $r['time_tag'] <= $noaa_times[$num][1])
	         {
		   $noaa_arr[$num][$j]  = $maxval*$mult;
	         }
	         else
	         {
		   $noaa_arr[$num][$j]  = 0.0000000000;
	         }
		 $j++;
	       }
	     $num++;
	     $x = '';
	     $y = '';
	     $mult = $mult * 0.9;
	 }


	 $i = 1;
	 $rhessi_arr = array();
         $num = 0;
             #this is done to collect data for plotting horizontal lines for rhessi matched events
	 while($num < $rcount) {
	     
	     $sql = "SELECT time_tag, A_FLUX, B_FLUX FROM goes15 WHERE time_tag >= '$start_date' AND time_tag <= '$end_date'";
             $result = $conn->query($sql);
	     if (! $result){
	       throw new My_Db_Exception('Database error: ' . mysql_error());
	     }

	       $event = 'RHESSI_' . $i;
               $val = array('label' => $event, 'type' => 'number');
               array_push($table['cols'], $val);
	       $i = $i + 1;

	       $j = 0;
	       foreach($result as $r)
	       {
	         if($r['time_tag'] >= $rhessi_times[$num][0] && $r['time_tag'] <= $rhessi_times[$num][1])
	         {
		   $rhessi_arr[$num][$j]  = $maxval * $mult;
	         }
	         else
	         {
		   $rhessi_arr[$num][$j]  = 0.0000000000;
	         }
		 $j++;
	       }
	       $num++;
	       $mult = $mult * 0.9;
	 }


	 $ar_hek = '';
	 $hek_arr = array();
	 $num = 0;
             #this is done to collect data for plotting horizontal lines for hek matched events
	 while($num < $hcount) {

	     $sql = "SELECT time_tag, A_FLUX, B_FLUX FROM goes15 WHERE time_tag >= '$start_date' AND time_tag <= '$end_date'";
             $result = $conn->query($sql);
	     if (! $result){
	       throw new My_Db_Exception('Database error: ' . mysql_error());
	     }

	       $event = 'HEK_' . $hek_times[$num][2] . '_' . $hek_times[$num][3];
               $val = array('label' => $event, 'type' => 'number');
               array_push($table['cols'], $val);

	       $j = 0;
	       foreach($result as $r)
	       {
	         if($r['time_tag'] >= $hek_times[$num][0] && $r['time_tag'] <= $hek_times[$num][1])
	         {
		   $hek_arr[$num][$j]  = $maxval * $mult;
	         }
	         else
	         {
		   $hek_arr[$num][$j]  = 0.0000000000;
	         }
		 $j++;
	       }
	       $num++;
	       $mult = $mult * 0.9;
	 }

         # find IRIS events within the time window of interest
	 $sql = "SELECT date, time, xray, comments FROM IRIS";
	 $stmt_iris = $conn->prepare($sql);
	 $stmt_iris->execute();
	 $res_iris = $stmt_iris->setFetchMode(PDO::FETCH_NUM);
	 $icount = 0;
	 $iris_times = array();
	 while($row = $stmt_iris->fetch()) {
	    $irisdate = $row[0] . ' ' . $row[1];
	    #echo $irisdate;
	    #echo '<br>';
	    if( ($irisdate < $end_date) && ($irisdate > $start_date) ) {
               $iris_times[$icount][0] = $row[0];
               $iris_times[$icount][1] = $row[1];
               $iris_times[$icount][2] = $row[2];
               $iris_times[$icount][3] = $row[3];
	    $icount++;
	    }
	 }




	 $sql = "SELECT time_tag, A_FLUX, B_FLUX FROM goes15 WHERE time_tag >= '$start_date' AND time_tag <= '$end_date'";
         $result = $conn->query($sql);
	 if (! $result){
	    throw new My_Db_Exception('Database error: ' . mysql_error());
	    }

    # create a data table for plotting the flux/matched event data
    $rows = array();
    $i = 0;
    foreach($result as $r)
    {
    	$temp = array();
	
        $n = 0;

	preg_match('/(\d{4})-(\d{2})-(\d{2})\s(\d{2}):(\d{2}):(\d{2})/', $r['time_tag'], $match);
	$year = (int) $match[1];
	$month = (int) $match[2] -1 ; // convert to zero-index to match javascript's dates
	$day = (int) $match[3];
	$hours = (int) $match[4];
	$minutes = (int) $match[5];
	$seconds = (int) $match[6];
    	
	$temp[] = array('v' => "Date($year, $month, $day, $hours, $minutes, $seconds)");
    	$temp[] = array('v' => $r['A_FLUX']);
    	$temp[] = array('v' => $r['B_FLUX']);

         while($n < $ncount )
	 {
	     $val = $noaa_arr[$n][$i];
	     $temp[] = array('v' => $val);
	     $n++;
	 }
         
	 $n = 0;
         while($n < $rcount )
	 {
	     $val = $rhessi_arr[$n][$i];
	     $temp[] = array('v' => $val);
	     $n++;
	 }
	 
	 $n = 0;
         while($n < $hcount )
	 {
	     $val = $hek_arr[$n][$i];
	     $temp[] = array('v' => $val);
	     $n++;
	 }

	$rows[] = array('c' => $temp);
	$i++;
    }

    $table['rows'] = $rows;
	 // convert data into JSON format
     $jsonTable = json_encode($table);
    }
    catch(PDOException $e)
    {
       echo "Connection failed: " . $e->getMessage();
    }
?>
    <!--use google charts to plot the data-->
    <html>
      <head>
        <!--Load the Ajax API-->
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	  <script type="text/javascript">
	      google.load('visualization', '1.1', {packages: ['corechart']});

        google.setOnLoadCallback(drawChart);
        function drawChart() {
          var data = new google.visualization.DataTable(<?=$jsonTable?>);
	  var dateFormatter = new google.visualization.DateFormat({pattern:'yyyy-MM-dd HH:mm:ss'});
	  dateFormatter.format(data, 0);
          var options = {
               title : 'Flux Data',
	       //curveType : 'function',
              //is3D: 'true',
	      hAxis : {
	          format : 'yyyy-MM-dd HH:mm:ss'
	      },
	      vAxis : {
	          format : 'scientific',
		  logScale : 'true'
	      },
              //width : 800,
              //height : 600
              width : 800,
              height : 600,
	      enableInteractivity: false
            };
          // Instantiate and draw our chart, passing in some options.
          // Do not forget to check your div ID
          var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
          chart.draw(data, options);
        }
        </script>
      </head>

      <body background="WBG.jpg">
        <!--this is the div that will hold the pie chart-->
        <div id="curve_chart"></div>
      </body>
    </html>

<?php
   # this piece of code prints the matched events data , as computed above, below the data plot 
    echo '<html>';
    echo '<head>';
    echo '<style>
    table, th, td {
        border: 1px solid black;
	border-collapse: collapse;
    }
    th, td {
	padding: 5px;
    }
    </style>';
    echo '</head>';

    echo '<body>';
    
    echo '<h3> Matched Events </h3>';
    echo '<h3>IRIS </h3>';
    echo '<table>
      <tr>
        <th>Date</th>
        <th>Time</th>		
        <th>GOES Class</th>
        <th>Comments</th>
       </tr>';

       for($i=0; $i < $icount; $i++)
       {
         echo "<tr>";
	 echo "<td>";
	 echo $iris_times[$i][0];
	 echo "</td>";
	 echo "<td>";
	 echo $iris_times[$i][1];
	 echo "</td>";
	 echo "<td>";
	 echo $iris_times[$i][2];
	 echo "</td>";
	 echo "<td>";
	 echo $iris_times[$i][3];
	 echo "</td>";
	 echo "</tr>";
       }
    echo '</table>';
    
    echo '<h3> NOAA </h3>';
    echo '<table>
      <tr>
        <th>GOES Class</th>
        <th>Start</th>		
        <th>Peak</th>
        <th>End</th>
        <th>AR</th>
        <th>Location</th>
       </tr>';
		     

    for($i=0; $i < $ncount; $i++)
    {
       echo "<tr>";
       echo "<td>";
       echo $noaa_events["$i"][0];
       echo "</td>";
       echo "<td>";
       echo $noaa_events["$i"][1];
       echo "</td>";
       echo "<td>";
       echo $noaa_events["$i"][2];
       echo "</td>";
       echo "<td>";
       echo $noaa_events["$i"][3];
       echo "</td>";
       echo "<td>";
       echo $noaa_events["$i"][4];
       echo "</td>";
       echo "<td>";
       echo $noaa_events["$i"][5];
       echo "</td>";
       echo '</tr>';
    }

    echo '</table>';

    echo '<h3> RHESSI </h3>';
    echo '<table>
      <tr>
        <th>FLARE</th>
        <th>Start</th>		
        <th>Peak</th>
        <th>End</th>
        <th>AR</th>
        <th>XPos</th>
        <th>YPos</th>
       </tr>';

    for($i=0; $i < $rcount; $i++)
    {
       echo "<tr>";
       echo "<td>";
       echo $rhessi_events["$i"][0];
       echo "</td>";
       echo "<td>";
       echo $rhessi_events["$i"][1];
       echo "</td>";
       echo "<td>";
       echo $rhessi_events["$i"][2];
       echo "</td>";
       echo "<td>";
       echo $rhessi_events["$i"][3];
       echo "</td>";
       echo "<td>";
       echo $rhessi_events["$i"][4];
       echo "</td>";
       echo "<td>";
       echo $rhessi_events["$i"][5];
       echo "</td>";
       echo "<td>";
       echo $rhessi_events["$i"][6];
       echo "</td>";
       echo '</tr>';
    }

    echo '</table>';
    
    echo '<h3> HEK </h3>';
    echo '<table>
      <tr>
        <th>obs_observatory</th>
        <th>obs_instrument</th>
        <th>obs_chennelid</th>
        <th>Start</th>		
        <th>Peak</th>
        <th>End</th>
        <th>ar_noaanum</th>
        <th>hpc_x</th>
        <th>hpc_y</th>
        <th>URLs</th>
       </tr>';

    for($i=0; $i < $hcount; $i++)
    {
       echo "<tr>";
       echo "<td>";
       echo $hek_events["$i"][0];
       echo "</td>";
       echo "<td>";
       echo $hek_events["$i"][1];
       echo "</td>";
       echo "<td>";
       echo $hek_events["$i"][2];
       echo "</td>";
       echo "<td>";
       echo $hek_events["$i"][3];
       echo "</td>";
       echo "<td>";
       echo $hek_events["$i"][4];
       echo "</td>";
       echo "<td>";
       echo $hek_events["$i"][5];
       echo "</td>";
       echo "<td>";
       echo $hek_events["$i"][6];
       echo "</td>";
       if($hek_events[$i][7] != '')
          $hpc_x = round($hek_events[$i][7]);
       else
          $hpc_x = '';
       if($hek_events[$i][8] != '')
          $hpc_y = round($hek_events[$i][8]);
       else
          $hpc_y = '';
       echo "<td>";
       echo $hpc_x;
       echo "</td>";
       echo "<td>";
       echo $hpc_y;
       echo "</td>";
       echo "<td>";
       for($j=10; $j<=22; $j=$j+2) {
       if($hek_events[$i][$j] != '')
       {
       echo "<a href=";
       echo $hek_events[$i][$j];
       echo ">";
       echo $hek_events[$i][$j-1];
       echo "</a>";
       echo '<br>';
       }
       }
       echo "</td>";
       echo '</tr>';
    }

    echo '</table>';
    echo '</body>';
    echo '</html>';


?>
