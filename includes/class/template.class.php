<?PHP
///////////////////////////////////////////////////////
// Purpose: Translate HTML to PHP in template files
// Author: Jacob Fliss (http://newagesoldier.com)
//
class template {
	private $loginMSG,$registerMSG,$sessionInfo;
	
	public function languages ($string) {
		global $settings_data;
		$languages = parse_ini_file('languages/'.$settings_data['language'].'.php', true, 1);
		
		return str_replace(array_keys($languages), $languages, $string);
	}
	
	public function allowedVideos () {
		$db = new db();
		$age = $_SESSION['age'];
		$allowedRatings = array();
		$videoIDs = array();
		
		$allowedRatings[] = "''"; //some dont have a rating
		
		if ($age > 0){
			$allowedRatings[] = "'G'";
			$allowedRatings[] = "'TV-Y'";
			$allowedRatings[] = "'TV-G'";
		}
		if ($age >= 3){
			$allowedRatings[] = "'PG'";
			$allowedRatings[] = "'TV-PG'";
		}
		if ($age >= 7)
			$allowedRatings[] = "'TV-Y7'";
		if ($age >= 13)
			$allowedRatings[] = "'PG-13'";
		if ($age >= 14)
			$allowedRatings[] = "'TV-14'";
		if ($age >= 17){
			$allowedRatings[] = "'R'";
			$allowedRatings[] = "'TV-MA'";
		}
		if ($age > 17)
			$allowedRatings[] = "'NC-17'";
			
		$ratings = implode(' OR certification = ', $allowedRatings);
		$result = $db->query('videos',"SELECT video_id FROM ratings WHERE certification = $ratings");
		$count = $result->fetch(PDO::FETCH_NUM);
		
		if ($count == 0)
			echo '<div style="background-color:orange;padding:5px;border:thin black solid;font-weight:bold;color:white;">No video data! Log in to Admin Control Panel and add some!</div>';
		
		if (!$result)
			echo ' DB ERROR.';
		else {
			foreach ($result as $row)
				$videoIDs[] = "'".$row['video_id']."'";
		}
		
		return $videoIDs;
	}
	
	public function dynamicReplace ($start,$end,$string) { //loop replace
		global $settings_data;
		$db = new db();
		$tmdb = new tmdb();
		$base = $tmdb->getConfig();
		$newData = '';
		$langoptions = '';
		$tempoptions = '';
		$qCount = 0;
		$i = 0;
		preg_match('/'.preg_quote($start).'(.*?)'.preg_quote($end).'/s', $string, $matches);
		$dataArray = array();
		
		if (strpos($start,"FILMS")) {
			$allowedVideos = implode(' OR id = ', $this->allowedVideos());
			if (isset($_GET['cat']))
				$result = $db->query('videos',"SELECT * FROM info WHERE genres LIKE '%$_GET[cat]%' AND id = $allowedVideos");
			else if (isset($_POST['s']))
				$result = $db->query('videos',"SELECT * FROM info WHERE title LIKE '%$_POST[s]%' AND id = $allowedVideos");
			else
				$result = $db->query('videos',"SELECT * FROM info WHERE id = $allowedVideos ORDER BY ROWID DESC");
			
			if (!$result)
				echo ' DB ERROR.';
			else {
				foreach ($result as $row) {
					$data = str_replace('LINK_INFO','?info&id='.$row['id'],$matches[1]);
					$data = str_replace('IMG_POSTER',$base.'w185'.$row['poster_path'],$data); //need to change this to optional non-tmdb data
					$qCount++;
					$newData .= $data;
					if ($i >= $settings_data['fp_display'])
						break;
					$i++;
				}
			}
		}
		
		if (strpos($start,"PAGENAV")) { //DOESNT WORK YET!!!!!!!!!!!
			$data = str_replace('PAGE_NEXT_URL','?'.strtok($_SERVER["QUERY_STRING"],'&'),$data);
			$data = str_replace('PAGE_BACK_URL','?'.strtok($_SERVER["QUERY_STRING"],'&'),$data);
			$newData .= $data;
		}
		
		if (strpos($start,"TEMPCATS")) {
			$result = $db->query('videos',"SELECT genres FROM info");
			$categories = array();
			if ($result != false) {
				foreach ($result as $row) {
					$catString = explode(",", $row['genres']);
					$categories = array_merge($categories, $catString);
				}
			}
			$cats = array_unique($categories);
			foreach ($cats as $cat)
				$newData .= str_replace('CAT_NAME',$cat,str_replace('CAT_LINK','?search&cat='.$cat,$matches[1]));
		}
		
		if (strpos($matches[1],'PROFAGE_OPTIONS') !== false){
			for ($i=1; $i <= 100; $i++)
				$ageoptions .= '<option>'.$i.'</option>';
			$newData = str_replace('PROFAGE_OPTIONS',$ageoptions,$matches[1]);
		}
		
		if ((strpos($matches[1],'VDATA_FILE') !== false)) {
			foreach (readDirectory('videos') as $videos) { //TODO: Modify for multiple directories
				$newData .= str_replace('VDATA_FILE','videos/'.$videos,$matches[1]);
				$newData = str_replace('VDATA_TITLE',$videos,$newData);
			}
		}
		
		if ((strpos($matches[1],'LANGUAGE_OPTIONS') !== false) || (strpos($matches[1],'TEMPLATE_OPTIONS') !== false)) {
			foreach (readDirectory('languages') as $lang)
				$langoptions .= '<option>'.str_replace('.php','',$lang).'</option>';
			$newData = str_replace('LANGUAGE_OPTIONS',$langoptions,$matches[1]);
			foreach (readDirectory('templates') as $temp)
				$tempoptions .= '<option>'.$temp.'</option>';
			$newData = str_replace('TEMPLATE_OPTIONS',$tempoptions,$newData);
		}
		
		return preg_replace('/'.preg_quote($start).'(.*?)'.preg_quote($end).'/s', $newData, $string);
	}
	
	public function replacements ($string) { //single replace
		global $settings_data;
		$db = new db();
		$tmdb = new tmdb();
		$base = $tmdb->getConfig();
		$row = array();
		//$resArray = GetShippingDetails($token);
		
		if (isset($_GET['f'])) {
				$result = $db->query('videos',"SELECT * FROM info WHERE videopath = '$_GET[f]'");
			if ($result != false)
				$row = $result->fetch(PDO::FETCH_ASSOC);
			else
				break;
		}
		
		if (isset($_GET['id'])) {
			$result = $db->query('videos',"SELECT * FROM info WHERE id = '$_GET[id]'");
			if ($result != false)
				$row = $result->fetch(PDO::FETCH_ASSOC);
			else
				break;
		}
		
		if (isset($_SESSION['name'])) {
			$result = $db->query('users',"SELECT * FROM users WHERE username = '$_SESSION[username]' AND password = '$_SESSION[password]'");
			if ($result != false)
				$urow = $result->fetch(PDO::FETCH_ASSOC);
			else
				break;
		}
		
		$replacements = array (
			'TEMP_PATH' 		=> 'templates/'.$settings_data['template'], 
			'TEMP_LOGINMSG' 	=> $this->loginMSG,
			'TEMP_REGISTERMSG'	=> $this->registerMSG,
			'TEMP_TITLE'		=> $settings_data['title'],
			'TEMP_BACKDROP'		=> $base.'w1920'.$row['backdrop_path'],
			'TEMP_EULA'			=> $settings_data['eula'],
			'LINK_REGISTER' 	=> '?register',
			'LINK_HOME'			=> './',
			'LINK_LOGOUT'		=> '?logout',
			'LINK_PROFILE'		=> '?profile',
			'LINK_ADMIN'		=> '?admin',
			'DATA_SEARCH'		=> $_POST['s'],
			'DATA_ID'			=> $row['id'],
			'DATA_TITLE'		=> $row['title'],
			'DATA_POSTER'		=> $base.'w185'.$row['poster_path'],
			'DATA_OPOSTER'		=> $row['poster_path'],
			'DATA_OVERVIEW'		=> $row['overview'],
			'DATA_GENRES'		=> $row['genres'],
			'DATA_POPULARITY'	=> round($row['popularity'], 1),
			'DATA_RELEASED'		=> date("F jS, Y", strtotime($row['release_date'])),
			'DATA_RUNTIME'		=> $row['runtime'],
			'DATA_LANGUAGES'	=> $row['languages'],
			'DATA_SPOKENLANG'	=> $row['spoken_languages'],
			'DATA_TAGLINE'		=> $row['tagline'],
			'DATA_TMDBID'		=> $row['tmdb_id'],
			'DATA_IMDBID'		=> $row['imdb_id'],
			'DATA_COMPANY'		=> $row['production_companies'],
			'DATA_TRAILER'		=> $row['trailers'],
			'DATA_VIDEOPATH'	=> $row['videopath'],
			'USERDB_NAME'		=> $_SESSION['name'],
			'ADMIN_TITLE'		=> $settings_data['title'],
			'ADMIN_FPDISPLAY'	=> $settings_data['fp_display'],
			'ADMIN_DIRECTORIES'	=> str_replace(',',"\n",$settings_data['video_paths']),
			'ADMIN_TMDBKEY'		=> $settings_data['tmdb_key'],
			'ADMIN_PAYPAL_USER'	=> $settings_data['API_UserName'],
			'ADMIN_PAYPAL_PASS'	=> $settings_data['API_Password'],
			'ADMIN_PAYPAL_SIG'	=> $settings_data['API_Signature'],
			'PROF_NAME'			=> $urow['name'],
			'PROF_BIRTHDATE'	=> $urow['birthdate'],
			'PAYPAL_ID'			=> $resArray["PAYERID"],
			'PAYPAL_EMAIL'		=> $resArray["EMAIL"],
			'PAYPAL_STATUS'		=> $resArray["ACK"]
		);
			
		$preReplace = array (
			'<!-- INC_NAVIGATION -->'	=> file_get_contents('templates/'.$settings_data['template'].'/navigation.html'),
			'<!-- INC_ADMIN -->'		=> file_get_contents('includes/admin.inc.php'),
			'<!-- INC_EDATA -->'		=> file_get_contents('includes/edata.inc.php'),
			'<!-- INC_VDATA -->'		=> file_get_contents('includes/vdata.inc.php'),
			'<!-- INC_PROFILE -->'		=> file_get_contents('includes/profile.inc.php'),
			'<!-- INC_HEADER -->'		=> file_get_contents('includes/includes.php'),
			'<!-- INC_TEMP_HEADER -->' 	=> file_get_contents('templates/'.$settings_data['template'].'/header.html')
		);
		$string = str_replace(array_keys($preReplace), $preReplace, $string);
		$string = $this->dynamicReplace("<!-- FILMS_BEGIN -->","<!-- FILMS_END -->",$string);
		$string = $this->dynamicReplace("<!-- LANGS_BEGIN -->","<!-- LANGS_END -->",$string);
		$string = $this->dynamicReplace("<!-- LANGOPT_BEGIN -->","<!-- LANGOPT_END -->",$string);
		$string = $this->dynamicReplace("<!-- TEMPOPT_BEGIN -->","<!-- TEMPOPT_END -->",$string);
		$string = $this->dynamicReplace("<!-- PROFAGEOPT_BEGIN -->","<!-- PROFAGEOPT_END -->",$string);
		$string = $this->dynamicReplace("<!-- TEMPCATS_BEGIN -->","<!-- TEMPCATS_END -->",$string);
		$string = $this->dynamicReplace("<!-- PAGENAV_BEGIN -->","<!-- PAGENAV_END -->",$string);
		$string = $this->dynamicReplace("<!-- VDATA_BEGIN -->","<!-- VDATA_END -->",$string);
		
		return str_replace(array_keys($replacements), $replacements, $string);
	}
	
	public function colorMsg ($msg,$type='') {
		if ($type == 'error')
			return '<span style="color:red;">'.$msg.'</span>';
		else if ($type == 'confirm')
			return '<span style="color:green;">'.$msg.'</span>';
		else
			return $msg;
	}
	
	public function logging ($data) {
		$db = new db();
		array_walk($data, 'clean');
		if ($data['username'] == '' || $data['password'] == '' || $data['trap'] != '')
			return $this->loginMSG = $this->colorMsg($this->languages('LANG_UPREQ'),'error');
		
		$results = $db->query('users',"SELECT * FROM users WHERE username='$data[username]' AND password='$data[password]'");
		if ($results != false) {
			$row = $results->fetch(PDO::FETCH_ASSOC);
			if (count($row) < 5)
				return $this->loginMSG = $this->colorMsg($this->languages('LANG_LOGIN_FAIL'),'error');
			else
				$this->loginMSG = $this->colorMsg($this->languages('LANG_LOGIN_GOOD'),'confirm');
			
			$this->sessionInfo = array($row['username'],$row['password'],$row['birthdate'],$row['name']);
		}
	}
	
	public function loginInfo () {
		return $this->sessionInfo;
	}
	
	public function registering ($data) {
		$db = new db();
		array_walk($data, 'clean');
		if ($data['username'] == '' || $data['password'] == '' || $data['name'] == '' || $data['age'] == '' || $data['repeat'] == '' || $data['trap'] != '')
			return $this->registerMSG = $this->colorMsg($this->languages('LANG_REG_EMPTY'),'error');
			
		if ($db->query('users',"INSERT INTO users (name,username,password,age) VALUES ('$data[name]','$data[username]','$data[password]','$data[age]')") != false)
			return $this->registerMSG = $this->replacements($this->languages('LANG_REG_SUCCESS')).'<br>username:'.$data['username'].' password:'.$data['password'];	
		else
			return $this->registerMSG = $this->colorMsg($this->languages('LANG_REG_FAIL'),'error');
	}
	
	public function post ($data) {
		if (array_key_exists('login',$data))
			return $this->logging($data);
		if (array_key_exists('register',$data))
			return $this->registering($data);
	}
	
	public function import ($file) {
		$content = file_get_contents($file);
		$content = $this->languages($content);
		echo $this->replacements($content);
	}
	
}
?>