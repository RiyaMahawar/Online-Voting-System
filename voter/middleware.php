<?php
require_once("../dbconnect.php");

if (!isset($_SESSION["user_role"])){
	?>
	<script>location.assign("../index.php");</script>
	<?php
	exit();
}
if($_SESSION["user_role"]!="Voter"){
	session_destroy();
	session_unset();
	?>
	<script>location.assign("../index.php");</script>
	<?php
}
?>