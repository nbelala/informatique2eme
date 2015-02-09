<?php
require_once 'firephp/firephp.php';
require_once 'include/class.php';
fb::info('debug started');
session_start();
$analyse = new analyse();
?>
<head>
	<link rel="stylesheet" type="text/css" href="style/style.css">
</head>
<body>
<?php
if($_SESSION['admin'] == 'admin'){
	// admin controle panel
	$admin = new admin();
	if (!isset($_GET['admin'])) {
		$control = array('members','files','Affichage','add_affichage');
		foreach ($control as $key) {
			echo "<li><a href=admin.php?admin=$key&section=$key>".$key."</a></li>";
		}
	}elseif ($_GET['admin'] == 'members') {?>
		<form method='post' action='http://newbooks/admin.php?admin=members'>
		<table class="table">
			<tr>
				<th>id</th>
				<th>name</th>
				<th>e-mail</th>
				<th>groupe</th>
				<th>Admin</th>
			</tr>
		<?php 
			$admin->get_members(); 
			$admin->member_edit();
		?>
		</table>
		<input type='submit' value='Delete' name='delete' class="buttons"/>
		<input type='submit' value='Add_admin' name='add_admin' class="buttons"/>
		<input type='submit' value='remove_admin' name='remove_admin' class='buttons'/>
		</form>
		<?php
	}elseif($_GET['admin'] == 'files') {?>
		<form action="admin.php?admin=files" method="post">
			<table class="table">
				<tr>
					<th>id</th>
					<th>title</th>
					<th>location</th>
					<th>module</th>
					<th>added by</th>
					<th>added time</th>
				</tr>
			<?php
			$admin->get_files();
			$admin->edit_files();
			?>
			</table>
			<input type="submit" value="Delete" name="delete" class="buttons"/>
		</form>
		<?php
	}elseif ($_GET['admin'] == 'Affichage') { ?>
		<form action="admin.php?admin=Affichage" method="post">
			<table class="table">
				<tr>
					<th>id</th>
					<th>title</th>
					<th>groupe</th>
					<th>module</th>
					<th>affichage</th>
					<th>added time</th>
				</tr>
			<?php
			$admin->get_affichage();
			$admin->edit_affichage();
			?>
			</table>
			<input type="submit" name="delete" value="delete" class="buttons">
		</form>
			<?php
	}elseif($_GET['admin'] == "add_affichage") {
		if(isset($_POST['submit'])){
			$admin->add_affichage();
		}
		?>
		<form action="admin.php?admin=add_affichage" method="post" class="form">
			<input type="text" name="title" placeholder="Title"/><br />
			<input type="text" name="groupe" placeholder="Groupe(s)"/><br />
			<input type="text" name="module" placeholder="Module"/><br />
			<textarea name="affichage" cols="30" rows="10" placeholder="Affichage"></textarea ><br />
			<input type="submit" name="submit" value="Add" class="buttons"/>
		</form >
		<?php
	}
}else{
	die("you have no authorthies here");
}
?>
</body>
