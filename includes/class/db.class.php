<?PHP
///////////////////////////////////////////////////////
// Purpose: General DB calls.
// Author: Jacob Fliss (http://newagesoldier.com)
//
class db {
	public function query($db,$statement) {
		try {
			if ($db == 'users') $sqlite = new PDO('sqlite:includes/database/users.db');
			else if ($db == 'videos') $sqlite = new PDO('sqlite:includes/database/videos.db');
			else return false;
		} catch (PDOException $e) {
			return false;
		}
		return $sqlite->query($statement);
	}
}
?>