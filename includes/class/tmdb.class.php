<?PHP
///////////////////////////////////////////////////////
// Purpose: Handle TMDB api and DB calls.
// Author: Jacob Fliss (http://newagesoldier.com)
//
class tmdb {
	
	public function getConfig() {
		global $settings_data;
		$json = json_decode(file_get_contents('http://api.themoviedb.org/3/configuration?api_key='.$settings_data['tmdb_key']));
		return $json->images->base_url;
	}
	
	public function getTMDBID($title,$year='') {
		global $settings_data;
		$obj = json_decode(file_get_contents('http://api.themoviedb.org/3/search/movie?api_key='.$settings_data['tmdb_key'].'&query='.urlencode($title).'&year='.$year), true);
		return $obj[results][0][id];
	}
	
	public function getTMDBTVID($title,$year='') {
		global $settings_data;
		$obj = json_decode(file_get_contents('http://api.themoviedb.org/3/search/tv?api_key='.$settings_data['tmdb_key'].'&query='.urlencode($title).'&year='.$year), true);
		return $obj[results][0][id];
	}
	
	public function getJSONData($mode,$id,$type=NULL) { //mode: tv,movie //type: images,releases,content_ratings
		global $settings_data;
		$trailer = NULL;
		if ($type != NULL) //tmdb doesnt like trailing slashes
			$type = '/'.$type;
		if ($mode == 'movie' && $type == NULL)
			$trailer = '&append_to_response=trailers';
		if ($id != '')
			$json = json_decode(file_get_contents('http://api.themoviedb.org/3/'.$mode.'/'.$id.$type.'?api_key='.$settings_data['tmdb_key'].$trailer), true);
	}

	public function SQLPrep ($value) {
		if (is_array($value)==false)
			$value = htmlentities($value,ENT_QUOTES);
		else
			$value = ArrayToString($value);
		return "'".$value."'";
	}
	
	public function TMDBImgs_Insert ($id,$json) {
		$db = new db();
		$num = count($json['posters']);
		for ($i=0; $i<=$num; $i++){
			$url = $json['posters'][$i]['file_path'];
			$result = $db->query('videos',"INSERT INTO images (video_id,url) VALUES ('$id','$url')");
			if (!$result) {
				echo ' DB ERROR [images]!'.'<br>';
				echo "INSERT INTO images (video_id,url) VALUES ('$id','$url')";
			}
			$result = null;
			flush();
    		ob_flush();
		}
	}
	
	public function TMDBRatings_Insert ($id,$type,$json) { //NOT DONE
		$db = new db();
		
		if ($type == "movie") {
			$num = count($json['countries']);
			if (in_array('US',$json['countries']) === false)
				$result = $db->query('videos',"INSERT INTO ratings (video_id,certification,iso_3166_1,primary_rating,release_date) VALUES ('$id','','US','','')");
		} else {
			$num = count($json['results']);
			if (in_array('US',$json['results']) === false)
				$result = $db->query('videos',"INSERT INTO ratings (video_id,certification,iso_3166_1,primary_rating,release_date) VALUES ('$id','','US','','')");
		}
			
		for ($i=0; $i<=$num; $i++){				
			if ($type == "movie"){
				$certification = $json['countries'][$i]['certification'];
				$iso_3166_1 = $json['countries'][$i]['iso_3166_1'];
				$primary = $json['countries'][$i]['primary'];
				$release_date = $json['countries'][$i]['release_date'];
				if (strpos($iso_3166_1,'US') === false)
					continue;
				$result = $db->query('videos',"INSERT INTO ratings (video_id,certification,iso_3166_1,primary_rating,release_date) VALUES ('$id','$certification','$iso_3166_1','$primary','$release_date')");
			} else { //TODO: Not done yet
				$certification = $json['results'][$i]['rating'];
				$iso_3166_1 = $json['results'][$i]['iso_3166_1'];
				if (strpos($iso_3166_1,'US') === false)
					continue;
				$result = $db->query('videos',"INSERT INTO ratings (video_id,certification,iso_3166_1) VALUES ('$id','$certification','$iso_3166_1')");
			}
			if (!$result){
				echo ' DB ERROR [ratings]!'.'<br>';
				if ($type == "movie")
					echo "INSERT INTO ratings (video_id,certification,iso_3166_1,primary_rating,release_date) VALUES ('$id','$certification','$iso_3166_1','$primary','$release_date')";
				else
					echo "INSERT INTO ratings (video_id,certification,iso_3166_1) VALUES ('$id','$certification','$iso_3166_1')";
			}
			$result = null;
			flush();
    		ob_flush();
		}
	}
	
	public function jsonToSQLite_TVInsert ($path,$json) { // no trailers, tagline
		$columns = implode(",",array_keys(array_filter($json)));
		$columns = str_replace(',id,',',tmdb_id,',$columns);
		$columns = str_replace(',name,',',title,',$columns);
		$columns = str_replace(',original_name,',',original_title,',$columns);
		$columns = str_replace(',episode_run_time,',',runtime,',$columns);
		$columns = str_replace(',first_air_date,',',release_date,',$columns);
		$columns = str_replace(',languages,',',spoken_languages,',$columns);
		$columns = str_replace(',created_by,',',belongs_to_collection,',$columns);
		$columns = str_replace(',networks,',',production_companies,',$columns);
		$columns = str_replace(',origin_country,',',production_countries,',$columns);
		$escaped_values = array_map(array($this, 'SQLPrep'), array_values(array_filter($json)));
		$values = implode(",", $escaped_values);
		$columns = $columns.",videopath";
		$values = $values.",'".$path."'";
		return "INSERT INTO info ($columns) VALUES ($values)";
	}
	
	public function jsonToSQLite_Insert ($path,$json) {
		$columns = implode(",",array_keys(array_filter($json)));
		$columns = str_replace(',id,',',tmdb_id,',$columns);
		$escaped_values = array_map(array($this, 'SQLPrep'), array_values(array_filter($json)));
		$values = implode(",", $escaped_values);
		$columns = $columns.",videopath";
		$values = $values.",'".$path."'";
		return "INSERT INTO info ($columns) VALUES ($values)";
	}
}
?>