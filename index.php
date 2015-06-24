<?PHP
///////////////////////////////////////////////////////
// Purpose: Main index page, used to swap template files
// Author: Jacob Fliss (http://newagesoldier.com)
//
error_reporting(E_ALL & ~E_NOTICE); //remove WAMP notices
require("includes/functions.inc.php");
$temp = new template();
$temp_path = 'templates/'.$settings_data['template'];

if (isset($_POST))
	$temp->post($_POST);
	
function age($birthday){
	$date = date_create($birthday);
	$bday = new DateTime(date_format($date,"m/d/Y"));
	$age = date_diff($bday, date_create('today'))->y;
	if (date("m") < $bday->format('m')){
		if (date("d") < $bday->format('d'))
			$age - 1;
	}
	return $age;
}

if (!isset($_SESSION['username'])) {
	$sessionInfo = $temp->loginInfo();
	$_SESSION['username'] = $sessionInfo[0];
	$_SESSION['password'] = $sessionInfo[1];
	$_SESSION['age'] = age($sessionInfo[2]);
	$_SESSION['name'] = $sessionInfo[3];
}

if (isset($_POST['save_settings'])) {
	unset($_POST['save_settings']);
	postToINI($_POST);
	?><script>window.location='./?admin'</script><?PHP
}

if (isset($_POST['save_vdata'])) {
	$db = new db();
	
	$tagline = sqliteEscapeString($_POST['tagline']);
	$overview = sqliteEscapeString($_POST['overview']);
	$release_date = date("Y-m-d", strtotime($_POST['release_date']));
	
	$result = $db->query('videos',"UPDATE info SET title='$_POST[title]',tmdb_id='$_POST[tmdb_id]',imdb_id='$_POST[imdb_id]',poster_path='$_POST[poster_path]',genres='$_POST[genres]',runtime='$_POST[runtime]',spoken_languages='$_POST[spoken_languages]',tagline='$tagline',trailers='$_POST[trailers]',production_companies='$_POST[production_companies]',release_date='$release_date',overview='$overview' WHERE id='$_POST[id]'");
	
	if (!$result)
		$result = $db->query('videos',"INSERT INTO info (title,tmdb_id,imdb_id,poster_path,genres,runtime,spoken_languages,tagline,trailers,production_companies,release_date,overview) VALUES ('$_POST[title]','$_POST[tmdb_id]','$_POST[imdb_id]','$_POST[poster_path]','$_POST[genres]','$_POST[runtime]','$_POST[spoken_languages]','$tagline','$_POST[trailers]','$_POST[production_companies]','$release_date','$overview')");
		
	$result = null;
	flush();
   	ob_flush();
	?><script>window.location='./?admin&vdata'</script><?PHP
}

if (isset($_POST['save_tvdata'])) {
	$db = new db();
	
	$overview = sqliteEscapeString($_POST['overview']);
	$first_air_date = date("Y-m-d", strtotime($_POST['first_air_date']));
	
	$result = $db->query('videos',"UPDATE info SET name='$_POST[name]',tmdb_id='$_POST[tmdb_id]',poster_path='$_POST[poster_path]',genres='$_POST[genres]',episode_run_time='$_POST[episode_run_time]',languages='$_POST[languages]',production_companies='$_POST[production_companies]',first_air_date='$first_air_date',overview='$overview' WHERE id='$_POST[id]'");
	
	if (!$result)
		$result = $db->query('videos',"INSERT INTO info (name,tmdb_id,poster_path,genres,episode_run_time,languages,trailers,production_companies,first_air_date,overview) VALUES ('$_POST[name]','$_POST[tmdb_id]','$_POST[poster_path]','$_POST[genres]','$_POST[episode_run_time]','$_POST[languages]','$_POST[production_companies]','$first_air_date','$overview')");
	
	$result = null;
	flush();
   	ob_flush();
	?><script>window.location='./?admin&vdata'</script><?PHP
}

if (isset($_POST['delete_vdata'])) {
	$db = new db();
	$result = $db->query('videos',"DELETE FROM info WHERE id='$_POST[id]'");
	if (!$result)
		echo 'DELETE DATA FAILED [info]!';
	$result = $db->query('videos',"DELETE FROM images WHERE video_id='$_POST[id]'");
	if (!$result)
		echo 'DELETE DATA FAILED [images]!';
	$result = $db->query('videos',"DELETE FROM ratings WHERE video_id='$_POST[id]'");
	if (!$result)
		echo 'DELETE DATA FAILED [ratings]!';
	else {
		?><script>window.location='./?admin&vdata'</script><?PHP
	}
}

if (isset($_POST['delete_tvdata'])) {
	$db = new db();
	$result = $db->query('videos',"DELETE FROM info WHERE id='$_POST[id]'");
	if (!$result)
		echo 'DELETE DATA FAILED!';
	else {
		?><script>window.location='./?admin&vdata'</script><?PHP
	}
}

if (isset($_POST['save_profile'])) {
	$db = new db();
	$result = $db->query('users',"UPDATE users SET name='$_POST[name]',birthdate='$_POST[birthdate]' WHERE username='$_SESSION[username]' AND password='$_SESSION[password]'");
	$_SESSION['age'] = age($_POST['birthdate']);
	$_SESSION['name'] = $_POST['name'];
	if (!$result)
		echo 'Profile not updated!'."DEBUG: UPDATE users SET name='$_POST[name]',birthdate='$_POST[birthdate]' WHERE username='$_SESSION[username]' AND password='$_SESSION[password]'<br>";
	$result = null;
	flush();
   	ob_flush();
	?><script>window.location='./?profile'</script><?PHP
}

if (isset($_GET['logout'])) {
	session_destroy();
	?><script>window.location='./'</script><?PHP
}

switch (strtok($_SERVER["QUERY_STRING"],'&')) {
	case "admin":
		$page = "admin.html";
		if (isset($_GET['vdata']))
			$page = "vdata.html";
        break;
	case "edit":
        $page = "edit.html";
        break;
    case "register":
        $page = "register.html";
        break;
	case "info":
        $page = "info.html";
        break;
	case "search":
        $page = "search.html";
        break;
	case "profile":
        $page = "profile.html";
        break;
    default:
		$page = 'logging.html';
		if (isset($_SESSION['username']))
        	$page = 'browse.html';
}
$temp->import($temp_path.'/'.$page);
?>