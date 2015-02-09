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
	<form action="affichage.php" method="get">
		<label for="groupe"> Search By groupe : </label>
		<select name="groupe" class="select">
			<option value="0">All</option>
			<?php 
			for($i = 1; $i <= 16; $i++){
				echo "<option value='$i'>$i</option>";
			}	
			?>
		</select>
		<input type="submit" name="submit" value="Search" class="search"/>
	</form>
<?php
	$affichage = new affichage();
?>
</div>
</body>
</html>
