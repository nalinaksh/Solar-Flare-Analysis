<?php

#Original Author: 
#	Nalinaksh Gaur
#	email: ng294@njit.edu
#Description:
#	php script to parse hek register data stored in xml format	

   $servername = "sql.njit.edu";
   $username = "ucid";
   $password = "database-password";
      
   try {
     
         $conn = new PDO("mysql:host=$servername;dbname=ucid", $username, $password);
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e)
    {
       echo "Connection failed: " . $e->getMessage();
    }
      
      for($i = 1; $i <= 30; $i++)
      {
         $file = "hek_2014-06-" . $i . ".xml";
         $xml=simplexml_load_file($file) or die("Error: Cannot create object");
         #print_r($xml);

	 foreach($xml as $result)
	 {
	 $w1 = $result->param[0];
	 $w2 = $result->param[1]; 
	 $w3 = $result->param[2];
	 $w4 = $result->param[3]; 
	 $w5 = $result->param[4];
	 $w6 = $result->param[5];
	 $w7 = $result->param[6];
	 $w8 = $result->param[7];
	 $w9 = $result->param[8];
	 $w10 = $result->param[9];
	 $w11 = $result->param[10];
	 $w12 = $result->param[11];
	 $w13 = $result->param[12];
	 $w14 = $result->param[13];
	 $w15 = $result->param[14];
	 $w16 = $result->param[15];
	 $w17 = $result->param[16];
	 $w18 = $result->param[17];
	 $w19 = $result->param[18];
	 $w20 = $result->param[19];
	 $w21 = $result->param[20];
	 $w22 = $result->param[21];
	 $w23 = $result->param[22];
	 $w24 = $result->param[23];
	 $w25 = $result->param[24];
	 $w26 = $result->param[25];
	 $w27 = $result->param[26];
	 $w28 = $result->param[27];
	 $w29 = $result->param[28];
	 $w30 = $result->param[29];
	 $w31 = $result->param[30];
	 $w32 = $result->param[31];
	 $w33 = $result->param[32];
	 $w34 = $result->param[33];
	 $w35 = $result->param[34];
	 $w36 = $result->param[35];
	 $w37 = $result->param[36];
	 $w38 = $result->param[37];
	 $w39 = $result->param[38];
	 $w40 = $result->param[39];
	 $w41 = $result->param[40];
	 $w42 = $result->param[41];
	 $w43 = $result->param[42];
	 $w44 = $result->param[43];
	 $w45 = $result->param[44];
	 $w46 = $result->param[45];
	 $w47 = $result->param[46];
	 $w48 = $result->param[47];
	 $w49 = $result->param[48];
	 $w50 = $result->param[49];
	 $w51 = $result->param[50];
	 $w52 = $result->param[51];
	 $w53 = addslashes($result->param[52]);
	 $w54 = $result->param[53];
	 $w55 = $result->param[54];
	 $w56 = $result->param[55];
	 $w57 = $result->param[56];
	 $w58 = $result->param[57];
	 $w59 = $result->param[58];
	 $w60 = $result->param[59];
	 $w61 = $result->param[60];
	 $w62 = $result->param[61];
	 $w63 = $result->param[62];
	 $w64 = $result->param[63];
	 $w65 = $result->param[64];
	 $w66 = $result->param[65];
	 $w67 = $result->param[66];
	 $w68 = $result->param[67];
	 $w69 = $result->param[68];
	 $w70 = $result->param[69];
	 $w71 = $result->param[70];
	 $w72 = $result->param[71];
	 $w73 = $result->param[72];
	 $w74 = $result->param[73];
	 $w75 = $result->param[74];
	 $w76 = $result->param[75];
	 $w77 = $result->param[76];
	 $w78 = $result->param[77];
	 $w79 = $result->param[78];
	 $w80 = $result->param[79];
	 $w81 = $result->param[80];
	 $w82 = $result->param[81];
	 $w83 = $result->param[82];
	 $w84 = $result->param[83];
	 $w85 = $result->param[84];
	 $w86 = $result->param[85];
	 $w87 = $result->param[86];
	 $w88 = $result->param[87];
	 $w89 = $result->param[88];
	 $w90 = $result->param[89];
	 $w91 = $result->param[90];
	 $w92 = $result->param[91];
	 $w93 = $result->param[92];
	 $w94 = $result->param[93];
	 $w95 = $result->param[94];
	 $w96 = $result->param[95];
	 $w97 = $result->param[96];
	 $w98 = $result->param[97];
	 $w99 = $result->param[98];
	 $w100 = $result->param[99];
	 $w101 = $result->param[100];
	 $w102 = $result->param[101];
	 $w103 = $result->param[102];
	 $w104 = $result->param[103];
	 $w105 = $result->param[104];
	 $w106 = $result->param[105];
	 $w107 = $result->param[106];
	 $w108 = $result->param[107];
	 $w109 = $result->param[108];
	 $w110 = $result->param[109];
	 $w111 = $result->param[110];
	 $w112 = $result->param[111];
	 $w113 = $result->param[112];
	 $w114 = $result->param[113];
	 $w115 = $result->param[114];
	 $w116 = $result->param[115];
	 $w117 = $result->param[116];
	 $w118 = $result->param[117];
	 $w119 = $result->param[118];
	 $w120 = $result->param[119];
	 $w121 = $result->param[120];
	 $w122 = $result->param[121];
	 $w123 = $result->param[122];
	 $w124 = $result->param[123];
	 $w125 = $result->param[124];
	 $w126 = $result->param[125];
	 $w127 = $result->param[126];
	 $w128 = $result->param[127];
	 $w129 = $result->param[128];
	 $w130 = $result->param[129];
	 $w131 = $result->param[130];
	 $w132 = $result->param[131];
	 $w133 = $result->param[132];
	 $w134 = $result->param[133];
	 $w135 = $result->param[134];
	 
	 $w136 = $result->param[135];
	 $w137 = $result->param[136];
	 $w138 = $result->param[137];
	 $w139 = $result->param[138];
	 $w140 = $result->param[139];
	 $w141 = $result->param[140];
	 $w142 = $result->param[141];
	 $w143 = $result->param[142];
	 $w144 = $result->param[143];
	 $w145 = $result->param[144];
	 $w146 = $result->param[145];
	 $w147 = $result->param[146];
	 $w148 = $result->param[147];
	 $w149 = $result->param[148];
	 $w150 = $result->param[149];
	 $w151 = $result->param[150];
	 $w152 = $result->param[151];
	 $w153 = $result->param[152];
	 $w154 = $result->param[153];
	 $w155 = $result->param[154];
	 $w156 = $result->param[155];
        
         $sql    = "INSERT INTO hek3 VALUES
	 (
	 '$w1',
	 '$w2',
	 '$w3',
	 '$w4',
	 '$w5',
	 '$w6',
	 '$w7',
	 '$w8',
	 '$w9',
	 '$w10',
	 '$w11',
	 '$w12',
	 '$w13',
	 '$w14',
	 '$w15',
	 '$w16',
	 '$w17',
	 '$w18',
	 '$w19',
	 '$w20',
	 '$w21',
	 '$w22',
	 '$w23',
	 '$w24',
	 '$w25',
	 '$w26',
	 '$w27',
	 '$w28',
	 '$w29',
	 '$w30',
	 '$w31',
	 '$w32',
	 '$w33',
	 '$w34',
	 '$w35',
	 '$w36',
	 '$w37',
	 '$w38',
	 '$w39',
	 '$w40',
	 '$w41',
	 '$w42',
	 '$w43',
	 '$w44',
	 '$w45',
	 '$w46',
	 '$w47',
	 '$w48',
	 '$w49',
	 '$w50',
	 '$w51',
	 '$w52',
	 '$w53',
	 '$w54',
	 '$w55',
	 '$w56',
	 '$w57',
	 '$w58',
	 '$w59',
	 '$w60',
	 '$w61',
	 '$w62',
	 '$w63',
	 '$w64',
	 '$w65',
	 '$w66',
	 '$w67',
	 '$w68',
	 '$w69',
	 '$w70',
	 '$w71',
	 '$w72',
	 '$w73',
	 '$w74',
	 '$w75',
	 '$w76',
	 '$w77',
	 '$w78',
	 '$w79',
	 '$w80',
	 '$w81',
	 '$w82',
	 '$w83',
	 '$w84',
	 '$w85',
	 '$w86',
	 '$w87',
	 '$w88',
	 '$w89',
	 '$w90',
	 '$w91',
	 '$w92',
	 '$w93',
	 '$w94',
	 '$w95',
	 '$w96',
	 '$w97',
	 '$w98',
	 '$w99',
	 '$w100',
	 '$w101',
	 '$w102',
	 '$w103',
	 '$w104',
	 '$w105',
	 '$w106',
	 '$w107',
	 '$w108',
	 '$w109',
	 '$w110',
	 '$w111',
	 '$w112',
	 '$w113',
	 '$w114',
	 '$w115',
	 '$w116',
	 '$w117',
	 '$w118',
	 '$w119',
	 '$w120',
	 '$w121',
	 '$w122',
	 '$w123',
	 '$w124',
	 '$w125',
	 '$w126',
	 '$w127',
	 '$w128',
	 '$w129',
	 '$w130',
	 '$w131',
	 '$w132',
	 '$w133',
	 '$w134',
	 '$w135',
	 
	 '$w136',
	 '$w137',
	 '$w138',
	 '$w139',
	 '$w140',
	 '$w141',
	 '$w142',
	 '$w143',
	 '$w144',
	 '$w145',
	 '$w146',
	 '$w147',
	 '$w148',
	 '$w149',
	 '$w150',
	 '$w151',
	 '$w152',
	 '$w153',
	 '$w154',
	 '$w155',
	 '$w156'
	 )";
	 $stmt = $conn->prepare($sql);
	 $stmt->execute();
	 $res = $stmt->setFetchMode(PDO::FETCH_NUM);
     }
   }

?>
