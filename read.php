<?php
$file = fopen("nginx.conf", "r") or exit ("Unable to open file!");

//Output line of the file
$lncount = 0;
$remove_location_character = array('^','"','$','(',')','*','=','{','}','~','location','  ',' ') ;
$remove_character = array('^','"','$','(',')','*','=','{','}','~') ;
echo "<table><th>Line No.</th><th>Source URL</th><th>Target URL</th>";
while(!feof($file))
{
	$current_line = fgets($file);
	$lncount++;
	if (preg_match('/location/',$current_line)){
		$location = explode(" ", str_replace($remove_location_character,"",$current_line));
		$location[1] = str_replace($remove_character,"",$location[1]);
		 
	}
	if (preg_match('/redirect/',$current_line)&!(preg_match('/#/',$current_line))&preg_match('/rewrite/',$current_line)){
		$values = explode(" ", $current_line);
		$values[1] = str_replace($remove_character,"",$values[1]);
		//echo $values[1]."<br/>";
		if(isset($location) && ($values[1]=='.')) 
		{
			$values[1]=$location[0];
			unset($location);
		}
		echo "<tr><td>".$lncount."</td><td><strong>www.lenskart.com".$values[1]."</strong></td><td>  <a href='".$values[2]."'<strong>".$values[2]."</a></strong><td><tr/>";
	}
}

fclose($file);
?>