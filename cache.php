<?PHP
require("includes/functions.inc.php");
$tmdb = new tmdb();
$db = new db();
session_start();

if (!isset($_SESSION['baseURL']))
	$_SESSION['baseURL'] = $tmdb->getConfig();
	
//$sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //shows insert errors

$directories = explode(',',$settings_data['video_paths']);
foreach ($directories as $directory) {
	$files = scandir($directory);
	foreach($files as $file) {
		
		if ($file == '.' || $file == '..')
			continue;
		
		echo 'Checking file/folder '.$file.'...';
		$fullPath = $directory.'/'.$file;

		$result = $db->query('videos',"SELECT videopath FROM info WHERE videopath = '$fullPath'");
		$count = $result->fetch(PDO::FETCH_NUM);
		
		if (!$result)
			echo ' DB ERROR.'.'<br>';
		else if ($count > 0) {
			echo ' already cached.'.'<br>';
			$result = null;
			continue;
		}
		
		$yearCheck = preg_match('/(19[0-9][0-9]|20([0-9][0-9]))/', $file, $year);
		$fileTitle = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
		$fileTitle = str_replace('_', '', $fileTitle);

		if (!isset($year[0]))
			$fileTitle = preg_replace('/(19[0-9][0-9]|20([0-9][0-9]))/', '', $fileTitle);
				
		if (is_dir($directory."/".$file)) {
			$id = $tmdb->getTMDBTVID(trim($fileTitle),$year[0]);
			$result = $db->query('videos',$tmdb->jsonToSQLite_TVInsert($directory."/".$file,$tmdb->getJSONData('tv',$id)));
		} else {
			$id = $tmdb->getTMDBID(trim($fileTitle),$year[0]);
			$result = $db->query('videos',$tmdb->jsonToSQLite_Insert($directory."/".$file,$tmdb->getJSONData('movie',$id)));
		}
		
		if (!$result)
			echo ' <span style="color:red;">BAD</span>'.'<br>';
		else {
			$movieID = $db->query('videos','SELECT id FROM info ORDER BY id DESC');
			$lastID = $movieID->fetch(PDO::FETCH_ASSOC);
			$lastID = $lastID['id'];
			$movieID = null;
			if (is_dir($directory."/".$file)) {
				$tmdb->TMDBRatings_Insert($lastID,"tvshow",$tmdb->getJSONData('tv',$id,'content_ratings'));
				$tmdb->TMDBImgs_Insert($lastID,$tmdb->getJSONData('tv',$id,'images'));
			} else {
				$tmdb->TMDBRatings_Insert($lastID,"movie",$tmdb->getJSONData('movie',$id,'releases'));
				$tmdb->TMDBImgs_Insert($lastID,$tmdb->getJSONData('movie',$id,'images'));
			}
			echo ' <span style="color:green;">GOOD</span>'.'<br>';
		}
		
		$result = null;
		flush();
    	ob_flush();
	}
}
?>