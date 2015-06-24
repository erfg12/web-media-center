<?PHP
///////////////////////////////////////////////////////
// Purpose: Functions used for all files.
// Author: Jacob Fliss (http://newagesoldier.com)
//
session_start();
ini_set('session.save_path', 'tmp');

$settings_file = 'settings.ini';
$settings_data = parse_ini_file($settings_file, true, 1);

function loader ($class) {
	include('class/'.$class.'.class.php');
}
spl_autoload_register('loader');

function clean ($string) {
	$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
	return preg_replace('/[^(\x20-\x7F)]*/','',$string);
}

function sqliteEscapeString ($value) {
	/*if (is_array($value)) {
		$value=preg_replace("/\'/","'\'",$value);
        return $value;
	} else*/
		return str_replace("'","''",$value);
}

function settingsArray ($setting) {
	return explode(',', $setting);
}

function readDirectory ($dir) {
	$array = array();
	if ($handle = opendir($dir)) {
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != ".." && $entry != "")
        		array_push ($array, $entry);
    	}
	}
	return $array;
}

function ArrayToString ($array,$new_array=array()) { //mostly just for tmdb
	$num = count($array);
	for ($i=0; $i<=$num; $i++){
		if (isset($array[$i]['name']) && $array[$i]['name'] != '') //multi-array
			array_push($new_array, $array[$i]['name']);
		else if ($array['youtube'][0]['source'] != '' && !in_array($array['youtube'][0]['source'], $new_array)) //trailer
			array_push($new_array, $array['youtube'][0]['source']);
	}
	return implode(',', $new_array);
}

function postToINI ($data) {
	global $settings_file;
	$fh = fopen($settings_file, 'w');
	foreach ( $data as $key => $value ) {
		if (!empty($data[$key])){
			$value = $data[$key];
		}
		$value = str_replace("\r\n",",",$value);
		fwrite($fh, "{$key}={$value}"."\r\n");
	}
	fclose($fh);
}
?>