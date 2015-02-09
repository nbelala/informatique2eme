<?php
require_once 'config.php';
class functions {
	public static function redirect($link) {
		ob_start();
		header('LOCATION: '.$link);
	}
}
class connect {
	private $mysqli;
	private $result;
	function connect() {
		$host = database::$host; 
		$name = database::$name;
		$pass = database::$pass;
		$database = database::$database;
		$this->mysqli = new mysqli($host,$name,$pass,$database);
		if(!$this->mysqli->connect_errno) {
			fb::info('connected to database '.$this->mysqli->connect_errno);
			return $this->mysqli;
		}else {
			fb::error('cant connect to database');
			die('cant connect to database <br>'.$this->mysqli->connect_error);
		}
	}
	public function query($query) {
		$this->result = $this->mysqli->query($query);
		$this->result ? fb::info('query true '.$query) : fb::error('query false '.$query);
		return $this->result;
	}
	public function escape_string($value) {
		return $this->mysqli->real_escape_string($value);
	}
	function __destruct() {
		$this->mysqli->close();
		fb::warn('connection to database closed');
	}
}
class upload {
	public $uname,$usize,$udir,$utype,$uerror;
	private $file;
	private $allowed_ext = array();
	public $error = array();
	function __construct($name,$size,$dir,$extention) {
		for($i = 0; $i < count($_FILES[$name]['name']); $i++) {
		$this->file = $_FILES[$name];
		$this->uname = $this->file['name'][$i];
		fb::warn($this->uname);
		$this->usize = $this->file['size'][$i];
		$this->udir = $this->file['tmp_name'][$i];
		$this->uerror = $this->file['error'][$i];
		$this->allowed_ext = $extention;
		$this->utype = $this->get_ext($this->uname);
		if (!$this->uerror) {
			fb::info('there is no error during uploading file : '.$this->uname);
			if (in_array($this->utype, $this->allowed_ext)) {
				fb::info('extention is '.$this->utype.' : allowed');
				if($this->usize < $size){
					fb::info('size '.$this->usize.' allowed');
					$uploaded_name = uniqid().'.'.$this->utype;
					$new_dir = $dir.'/'.$uploaded_name;
					move_uploaded_file($this->udir,$new_dir);
					fb::warn('filel uploaded');
					get_books::set($this->uname,$new_dir);
				}else {
					fb::error('file size '.$this->usize.' not alllowed');
					$this->error[] = 'size does not mear conditions';
				}
			}else {
				fb::error('extention '.$this->utype.' : not allowed');
				$this->error[] = 'extention not allowed';
			}
		}else{
			fb::error('error during uploading file');
			$this->error[] = 'error during uploading file(s)';
		}
		}
	}
	private function get_ext($name) {
		$name_array = explode(".", $name);
		$extention = strtolower(end($name_array));
		return $extention;
	}
}
class crypt
{
	public static function crypt_a($name){
		$name = str_split($name);
		fb::error(count($name));
		for ($i = 0; $i < count($name); $i++) {
			$x = $name[$i];
			$name[$i] = $name[$i+1];
			$name[$i+1] = $x;
			$i++;
		}
		$name = implode("", $name);
		return $name;
	}
	public static function crypt_a_reverse($name){
		$name = str_split($name);
		for($i = 0; $i < count($name); $i++){
			$x = $name[$i + 1];
			$name[$i+1] = $name[$i];
			$name[$i] = $x;
			$i++;
		}
		echo '<br>';
		echo "<br>";
		$name = implode("", $name);
		return $name;
	}
	public static function crypt_b($name){
		$array = explode(" ", $name);
		print_r($array);
	}
}
class get_books {
	public static function get() {
		$section = $_GET['section'];
		// query to get books "select * from books WHERE type='$this->section' AND level='$level'"
		$connect = new connect();
		$result = $connect->query("select * from books WHERE module='$section'");
		$num = $result->num_rows;
		echo "<ol>";
		echo "<h2>There is " . $num . " file(s) </h2>";
		echo "<div class='list'>";
		while($row = $result->fetch_object()) {
			echo "<li class='bottom'>";
			echo "<a class='title' href='{$row->location}'>{$row->title}</a>";
			echo '<br> added by : ' . $row->addedby." / date : ".$row->addedtime;
			echo "</li>";
		}
		echo "</div></ol>";
	}
	public static function set($title,$location){
		$connect = new connect();
		$module = $_POST['module'];
		$addedtime = date('y/m/d  h:i');
		$addedby = $_SESSION['name'];
		$title = $connect->escape_string($title);
		$location = $connect->escape_string($location);
		$module = $connect->escape_string($module);
		$addedtime = $connect->escape_string($addedtime);
		$addedby = $connect->escape_string($addedby);
		$connect->query("insert into books(title,location,module,addedby,addedtime) VALUES ('$title','$location'
		,'$module','$addedby','$addedtime')");
	}
}
class admin {
	private $connection;
	function __construct() {
		$this->connection = new connect();
	}
	public function get_members() {
		$qeury = "select * from users ORDER BY name";
		$result = $this->connection->query($qeury);
		while($row = $result->fetch_object()){
			echo "<tr class='tr'>";
			fb::info($row->name);
			echo '<td>'.$row->id.'</td>';
			echo '<td>'.$row->name.'</td>';
			echo '<td>'.$row->email.'</td>';
			echo '<td>'.$row->groupe.'</td>';
			echo '<td>'.$row->admin.'</td>';
			echo '<td><input type="checkbox" name="check[]" value="'.$row->name.'" /></td>';
			echo "</tr>";
		}
	}
	public function member_edit(){
		if(isset($_POST['delete'])){
			foreach ($_POST['check'] as $name) {
				$query = "delete from users WHERE name='$name'";
				$this->connection->query($query);
			}
		}elseif (isset($_POST['add_admin'])) {
			foreach ($_POST['check'] as $name) {
				$query = "update users set admin = 1 WHERE name='$name'";
				$this->connection->query($query);
			}
		}elseif (isset($_POST['remove_admin'])) {
			foreach ($_POST['check'] as $name) {
				$query = "update users set admin = 0 where name='$name'";
				$this->connection->query($query);
			}
		}
	}
	public function get_files() {
		$query = "select * from books";
		$result = $this->connection->query($query);
		while($row = $result->fetch_object()){
			echo "<tr class='tr'>";
			echo '<td>'.$row->id.'</td>';
			echo '<td>'.$row->title.'</td>';
			echo '<td>'.$row->location.'</td>';
			echo '<td>'.$row->module.'</td>';
			echo '<td>'.$row->addedby.'</td>';
			echo '<td>'.$row->addedtime.'</td>';
			echo '<td><input type="checkbox" name="check[]" value="'.$row->location.'" /></td>';
			echo "</tr>";
		}
	}
	public function edit_files() {
		if(isset($_POST['delete'])) {
			foreach($_POST['check'] as $dir) {
				$query = "delete from books WHERE location='$dir'";
				$this->connection->query($query);
				unlink($dir);
			}
		}
	}
	public function get_affichage(){
		$query = "select * from affichage";
		$result = $this->connection->query($query);
		while ($row = $result->fetch_object()) {
			echo "<tr class='tr'>";
			echo '<td>'.$row->id.'</td>';
			echo "<td>$row->title</td>";
			echo "<td>$row->groupe</td>";
			echo "<td>$row->module</td>";
			echo "<td>$row->affichage</td>";
			echo "<td>$row->time";
			echo '<td><input type="checkbox" name="check[]" value="'.$row->id.'" /></td>';
			echo "</tr>";
		}
	}
	public function edit_affichage(){
		if (isset($_POST['delete'])) {
			foreach ($_POST['check'] as $key) {
				$query = "delete from affichage where id='$key'";
				$this->connection->query($query);
			}
		}
	}
	public function add_affichage(){
		$title = $_POST['title'];
		$groupe = $_POST['groupe'];
		$module = $_POST['module'];
		$date = date("y/m/d h:i");
		session_start();
		$addedby = $_SESSION['name'];
		$affichage = $_POST['affichage'];
		$query = "insert into affichage(title,groupe,module,time,addedby,affichage) VALUES ('$title','$groupe',
			'$module','$date','$addedby','$affichage')";
		$this->connection->query($query);
	}
	/*public static function admin_exist($email,$pass) {
		$connect = new connect();
		$query = "select * from users WHERE email='$email' AND pass = '$pass' AND admin = 1";
		$result = $connect->query($query)->num_rows;
		if($result) {
			// start admin session
			session_start();
			$_SESSION['admin'] = "admin";
		}else{
			die('this page is out of your authorothies');
		}
	}*/
}
class user_login
{
	private $connection;
	public $errors = array();
	private $email;
	private $pass;
	public $name;
	public function user_login() 
	{
		session_start();
		$this->connection = new connect();
		$this->email = $_POST['email'];
		$this->pass = crypt::crypt_a($_POST['pass']);
		// get name of user
		$query = "select * from users WHERE email='$this->email' and pass='$this->pass'";
		$result = $this->connection->query($query)->fetch_object();
		$this->name = $result->name;
		// check if user is an admin
		if($result->admin){
			$_SESSION['admin'] = 'admin';
		}
		if($this->ext_valid()) {
			fb::info('login success');
			$_SESSION['user'] = "user";
			$_SESSION['name'] = $this->name;
			$_SESSION['email'] = $this->email;
			fb::info('sessions was set to '.$_SESSION['name']);
			functions::redirect('ctr.php');
		}else {
			fb::error('user does not exist');
		}
	}
	private function ext_valid()
	{
		$query = "select * from users WHERE email='$this->email' and pass='$this->pass'";
		$row = $this->connection->query($query)->num_rows;
		if($row) {
			fb::info('user exist');
			return true;
		}else {
			fb::error('user does not exost');
			$this->errors[] = "name or password wrong";
			return false;
		}
	}
	public function errors()
	{
		foreach($this->errors as $error) {
			echo "<li>{$error}</li>";
		}
	}
}
class user_subscribe
{
	private $connection;
	private $name;
	private $pass;
	private $email;
	private $groupe;
	private $max = 12;
	private $min = 4;
	private $errors = array();
	public function user_subscribe()
	{
		$this->connection = new connect();
		$this->name = $_POST['name'];
		$this->pass = crypt::crypt_a($_POST['pass']);
		$this->email = $_POST['email'];
		$this->groupe = $_POST['groupe'];
		if(!$this->leg_valid($this->name)) {
			$this->errors[] = "name length error";
		}
		if(!$this->leg_valid($this->pass)) {
			$this->errors[] = "password length error";
		}
		if(!strstr($this->email, "@")) {
			$this->errors[] = "email invalide";
		}
		if($this->user_exist()){
			$this->errors[] = "this name or email already exists please enter diffrent one";
		}
		if(empty($this->errors)) {
			// here add user
			$this->user_add();
			functions::redirect('user.php?login&success');
		}
	}
	private function leg_valid($name)
	{
		$length = strlen($name);
		if($this->min > $length) {
			return false;
		} elseif($this->max < $length) {
			return false;
		} else {
			return true;
		}
	}
	private function user_add()
	{
		$name = $this->connection->escape_string($this->name);
		$pass = $this->connection->escape_string($this->pass);
		$email = $this->connection->escape_string($this->email);
		$groupe = $this->connection->escape_string($this->groupe);
		$query = "insert into users (name,pass,email,groupe) VALUES ('$name','$pass','$email','$groupe')";
		$this->connection->query($query);
	}
	private function user_exist()
	{
		$query = "select * from users WHERE name='$this->name' OR email='$this->email'";
		if($this->connection->query($query)->num_rows) {
			fb::info('user exist');
			return true;
		} else {
			fb::error('user does not exist');
			return false;
		}
	}
	public function errors()
	{
		if(!empty($this->errors)) {
			foreach($this->errors as $error) {
				echo "<li>{$error}</li>";
			}
		}
	}
}
class analyse
{
	private $connection;
	private $ip;
	private $browser;
	private $name;
	private $section;
	private $hits;
	private $date;
	function __construct()
	{
		fb::group('analyse');
		$this->connection = new connect();
		if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$this->ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$this->ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$this->ip = $_SERVER['REMOTE_ADDR'];
		}
		$this->browser = $_SERVER['HTTP_USER_AGENT'];
		$this->date = date("y/m/d - h:i:s");
		if(isset($_SESSION['name'])){
			$this->name = $_SESSION['name'];
		}else{
			$this->name = 'vistor';
		}
		if (isset($_GET['section'])) {
			$this->section = $_GET['section'];
		} else {
			$this->section = 'index';
		}
		$this->hits = 1;
		$this->add();
		$this->new_visits();
		fb::groupEnd();
	}
	private function new_visits(){
		if(!isset($_COOKIE['user'])){
			$query = "insert into analyse2(ip,time) VALUES ('$this->ip','$this->date')";
			$this->connection->query($query);
			setcookie("user", "user", time()+3600*24*365*10);
			fb::info('cookie was not set ');
		}else{
			fb::info('cookie is set');
		}
	}
	private function add()
	{
		$this->name = $this->connection->escape_string($this->name);
		$query = "insert into analyse (ip,browser,name,hits,section,date) VALUES ('$this->ip','$this->browser','$this->name','$this->hits','$this->section','$this->date')";
		$this->connection->query($query);
	}
	public function hits()
	{
		$query = "select id from analyse";
		$result = $this->connection->query($query)->num_rows;
		echo "Hits : $result";
	}
	public function visits()
	{
		$query = "select id from analyse2";
		$result = $this->connection->query($query)->num_rows;
		echo "visitors : $result";
	}
	public function books_number(){
		$query = "select id from books";
		$result = $this->connection->query($query)->num_rows;
		echo "files : $result";
	}
}
class affichage
{
	private $connection;
	public function affichage() {
		$this->connection = new connect();
		if($_GET['groupe'] == 0) {
			$query = "select * from affichage ORDER BY id DESC ";
			$result = $this->connection->query($query);
			echo "<ol>";
			echo "<div class='list'>";
			while($row = $result->fetch_object()) {
				echo "<li class='bottom'>";
				echo "<h2>groupe(s) : $row->groupe<br> module : $row->module <br> Date : $row->time</h2>";
				echo "<h3><ul><li>".nl2br($row->affichage)."</li></ul></h3>";
				echo "By : ".$row->addedby;
				echo "</li>";
			}
			echo "</div></ol>";	
		}else{
			$groupe = $_GET['groupe'];
			$query = "select * from affichage where groupe LIKE '%$groupe%' ORDER BY id DESC ";
			$result = $this->connection->query($query);
			echo "<ol>";
			echo "<div class='list'>";
			while($row = $result->fetch_object()) {
				echo "<li class='bottom'>";
				echo "<h2>groupe(s) : $row->groupe <br> module : $row->module <br> Date : $row->time</h2>";
				echo "<h3><ul><li>".nl2br($row->affichage)."</li></ul></h3>";
				echo "By : ".$row->addedby;
				echo "</li>";
			}
			echo "</div></ol>";	
		}
	}
}
