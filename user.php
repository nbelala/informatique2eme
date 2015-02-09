<?php
require_once 'firephp/firephp.php';
require_once 'include/class.php';
fb::info('debug started');
session_start();
$analyse = new analyse();
if($_SESSION['user'] == "user"){
	fb::info($_SESSION['name']);
}else {
	fb::info('session is not set');
}
?>
<html>
<head>
	<title>Books</title>
	<script src="scripts/jquery.js"></script>
	<script src="scripts/public.js"></script>
	<link rel="stylesheet" href="style/style.css" type="text/css"/>
</head>
<?php
if (isset($_GET['login'])) {
	fb::info('get is set to login');
	// here goes the login script
	if (isset($_POST['submit'])) {
		fb::info('form was submited');
		// here goes the query of loggin
		$user_login = new user_login();
	}
?>
<body>
<!-- this is the login form -->
	<div class="form">
		<h1>Log in :</h1>
		<form action="user.php?login" method="post">
		<div class="errors">
			<?php
			if(isset($_POST['submit'])){
				$user_login->errors();
			}
			 ?>
		</div>
		<div class="label">
			<label for="email">E-mail</label><br>
			<label for="pass">Password</label>
		</div>
		<div class="fields">
			<input class="text" type="text" name="email" placeholder="Your E-mail"/>
			<br />
			<input class="text" type="password" name="pass" placeholder="Your password"/>
			<br />
		</div>
		<div class="submit">
			<input class="button" type="submit" name="submit" value="Log in"/><br/><br/>
			<p>Or</p>
			<a href="user.php?sub&section=login"><u>Subscribe</u></a>
		</div>
		</form>
	</div>
</body>
<?php
}elseif (isset($_GET['sub'])) {    // here goes the subscib form validation
	if(isset($_POST['submit'])){
		 // if allright
		 $new_user = new user_subscribe();
	}
	?>
<body>
<!-- this is the subscribe form -->
<div class="form">
	<h1>Subscribe :</h1>
<form action="user.php?sub" method="post">
	<div class="errors">
		<?php
		if(isset($_POST['submit'])){
			$new_user->errors();
		}
		?>
	</div>
<div class="label">
	<label for="name">Name :</label><br>
	<label for="pass">Password :</label><br/>
	<label for="email">E-mail :</label><br>
	<label for="groupe">groupe :</label>
</div>
<div class="fields">
	<input class="text" type="text" name="name" placeholder="Your Name"/>
	<br />
	<input class="text" type="password" name="pass" placeholder="Your password"/>
	<br />
	<input class="text" placeholder="You E-mail" name="email" />
	<br />
	<select name="groupe" class="groupe">
		<?php
		for ($i=1; $i < 17; $i++) {
			echo "<option value=".$i.">".$i."</option>";
		}
		?>
	</select>
</div>
<div class="submit">
	<input class="button" type="submit" name="submit" value="subscribe"/><br/><br/>
	Already have account : <a href="user.php?login&section=subscribe">Log in </a>
</div>
</form>
</div>
	</body>
	</html>
<?php
}else {
	echo("<h1>this page does not exist</h1>");
}
?>
