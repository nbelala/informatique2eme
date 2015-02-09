<?php
require_once 'firephp/firephp.php';
require_once 'include/class.php';
fb::info('debug started');
$analyse = new analyse();
?>
<html>
<head>
	<title>Informatique</title>
	<script src="scripts/jquery.js"></script>
	<script src="scripts/public.js"></script>
	<link rel="stylesheet" href="style/style.css" type="text/css"/>
</head>
<body>
<header class="head">
	<ul class="menu">
		<?php
		$sections = general_static::$moduls;
		?>
		<li class="element"><a href="affichage.php?section=affichage&groupe=0">Affichage</a></li>
		<?php
		foreach($sections as $element) {
			echo '<li class="element"><a href="index.php?section='.$element.'">' . $element . '</a></li>';
		}
		?>
		<li class="element"><a href="user.php?sub&section=login">Subscribe</a></li>
		<li class="element"><a href="index.php">About</a></li>
	</ul>
</header>
<div class="content">
	<?php
if (isset($_GET['section'])) {
	if(in_array($_GET['section'], $sections)) {
		// printing books lists
		get_books::get();
	}
}else {
	?>
	<h1 >Welcome</h1 >
	<h2 >the website is empty because its under development right now . <br >
	     You can participate in the development on  :
		<a href="https://github.com/Armada-dev/books4free" ><u >Github</u ></a > <br />
		<h2> <u>Site Statistics :</u> <br><br>
	<?php
		$analyse->visits();
		echo "<br>";
		$analyse->hits();
		echo "<br>";
		$analyse->books_number();
	?>
	</h2>
	</h2 >
<?php
}
?>
</div>
</body>
</html>
