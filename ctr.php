<?php
require_once 'firephp/firephp.php';
require_once 'include/class.php';
fb::info('debug started');
session_start();
$analyse = new analyse();
$admin = new admin();
?>
<html>
<head>
	<title>Books</title>
	<script src="scripts/jquery.js"></script>
	<script src="scripts/public.js"></script>
	<link rel="stylesheet" href="style/style.css" type="text/css"/>
</head>
<?php
if($_SESSION['user'] == "user"){
	fb::info('session is set : '.$_SESSION['user']);
if (isset($_POST['submit'])) {
	// calling the object that will add book to database
	// after validating the inputs
	$dir = "books/".$_POST['module']."/";
	$new_book = new upload('file',8000000,$dir,upload_conf::$extentions);
}
?>
<body>
	<div>
	<h1><a href="index.php" class="retourner"><-- Retourner</a></h1>
</div>
	<?php
		if ($admin->is_admin()) {
			echo "<h1><a href='admin.php?section=admin'>Admin panel</a></h1>";
		}
	?>
	<div class="form">
		<form action="ctr.php" method="post" enctype="multipart/form-data">
		<h3>Max size per file : 8 MB 
			<?php 
			echo "<br> allowed extentions : ";
			foreach (upload_conf::$extentions as $key) {
			 	echo " $key , ";
			 } 
			?>
		</h3>
		<!-- Maximum upload size is changebale -->
		<div class="errors">
			<?php
			 if(!empty($new_book->error)){
				echo "<h3><u>Erorrs</u> :</h3>";
				 foreach($new_book->error as $error){
					 echo "<li>".$error."</li>";
				 }
			 }
			 ?>
		</div>
		<div class="label">
			<label for="type">Module :</label><br />
			<label for="file">select files :</label>
		</div>
		<div class="fields">
			<select name="module" id="type" class="select">
				<?php
				$sections = general_static::$moduls;
				foreach($sections as $element) {
					echo '<option value="'.$element.'">'.$element.'</option>';
				}
				?>
			</select><br />
			<div class="here">
			<input type="file" name="file[]" placeholder="select your file" /><br />
			</div>
		</div>
		<div class="add">Add another file</div>
		<div class="submit">
			<input class="button" type="submit" name="submit" value="Upload"/>
		</div>
		</form>
	</div>
</body>
</html>
<?php
}else{
	fb::error('session is not set');
	echo "<h1>you are not allowed to enter this page</h1>";
}
 ?>
