<?php
	$host_name = "localhost"; 
	$data_base = "mission_next_prod";   // missionnext.org
	$user_name = "admin";
	$pass_word = "D4ufA&.%WJ-^#erL]q,$F5ZmXG{~2\bG";
	
	$db_link = pg_connect("host=$host_name port=5432 user=$user_name password=$pass_word dbname=$data_base")
	or die ("Not connected to database");
	
		$sql_std = "SELECT COUNT(target_id) FROM favorite_agency WHERE app_id = $site AND target_type = 'candidate' AND user_id = $userId";
		// echo "<br>\$sql_std = $sql_std<br>";
		$res_std = pg_query($db_link,$sql_std) or die("\$sql_std query failed: <br>$sql_std");
			while ($row = pg_fetch_array($res_std)) {
				$target = $row[0];
			}
		
		print ("$target");

	pg_close($db_link);
?>