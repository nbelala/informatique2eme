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
		<li class="element"><a href="user.php?sub&section=login">s'inscrire</a></li>
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
	<h1 >Bienvenu</h1 >
	<h2>Vous pouvez telecharger des document ou aussi partager : <u><a class="text-button" href="user.php?login">s'inscrire</a></u> . <br><br>
	    Le site est open source, pour participer au development de site :
		<a class="text-button" href="https://github.com/Armada-dev/informatique2eme" ><u >Github</u ></a >. <br />
		<h2> <u> Statistics de site :</u> <br><br>
			<div class="statistcs">
	<?php
		$analyse->visits();
		echo "<br>";
		$analyse->hits();
		echo "<br>";
		$analyse->books_number();
	?>
			</div>
	</h2>
	</h2 >
<?php
}
?>
</div>
</body>
</html>
