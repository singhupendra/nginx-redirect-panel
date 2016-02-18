<?php

if(isset($_POST['source']) && (isset($_POST['target']))){
	validate($_POST['source'],$_POST['target']);
	$result = checkDuplicate($_POST['source']);
	if($result[0]==1){
		$redirect_rule = 'rewrite "^/'.$_POST['source'].'$" "'. $_POST['target'].'" redirect'; 
		write_redirect($redirect_rule);
	}else{
		echo "This URL is already redirected to ".$result[1];
		exit;
		}

	}else 
	echo "Sorry need both URL";

function write_redirect($redirect_rule){
	$path_to_file = 'nginx.conf';
	$file_contents = file_get_contents($path_to_file);
	$file_contents = str_replace("#mobile-site-redirect","#mobile-site-redirect\n".$redirect_rule.";",$file_contents);
	
	if ((file_put_contents($path_to_file,$file_contents)) ===  false)
		echo "Failed to place the rule"; 
	else 
		echo "Placed the redirect rule!!";
//	exec('/etc/init.d/nginx reload');	
}

function validate($source,$target){
	echo $source;
$invalid_character = '/\*|~|\"|\$|[\s]/';
if ((substr($source, 0, 1) === '/' ) || preg_match($invalid_character,$source)) {
		echo "kya kar rahe ho bhai, source sale down ho jayegi!!!"; 
		die();
	}elseif(!(substr($target, 0, 4) === 'http') || preg_match($invalid_character,$target)){
		echo "kya kar rahe ho bhai, target sale down ho jayegi!!!"; 
		die();
	} 	
}


function checkDuplicate($source){
$file = fopen("nginx.conf", "r") or exit ("Unable to open file!");
$result=array("1");
$found_rule=0;
//Output line of the file
$remove_character = array('^/','"','$',"") ;
while(!feof($file) && ($found_rule == 0))
{
	$current_line = fgets($file);
		if (preg_match('/redirect/',$current_line)&!(preg_match('/#/',$current_line))&preg_match('/rewrite/',$current_line)){
		$current_line=trim($current_line);
		$values = explode(" ", $current_line);
		$values[1] = str_replace($remove_character,"",$values[1]);
		$res = strcmp($source, $values[1]);
		if(($res == 0)){
			$found_rule=1;
			$result = array("0",$values[2]);
			}
	}
}
fclose($file);
return $result;
}

?>