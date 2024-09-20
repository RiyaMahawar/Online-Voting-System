<?php
session_start();
if(isset($_SESSION["user_role"])){
	if($_SESSION["user_role"]=='Admin'){
		?>
			<script>location.assign("admin/index.php");</script>
		<?php
	} else {
		?>
			<script>location.assign("voter/index.php");</script>
		<?php
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<script src="https://cdn.tailwindcss.com"></script>
	<title>Online Voting system | Home</title>
</head>
<body>
	<div class="flex justify-center items-center h-screen">	
		<div class="w-1/2 h-full hidden lg:block lg:flex lg:items-center lg:justify-center text-center">
		<h1 class="text-black text-6xl text-start font-bold"> Online Voting System </h1>
		</div>
		<div class="lg:px-36 md:px-52 sm:px-20 px-8 w-full lg:w-1/2">
			<?php 
			if(isset($_GET['signup'])){
			?>
				<h1 class="text-2xl font-semibold mb-4">Sign up</h1>
				<form action="#" method="POST">
					<!-- Username Input -->
					<div class="mb-4">
						<label class="block text-gray-600">Username</label>
						<input type="text" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" autocomplete="off" name="su_name" required />
					</div>
					<!-- Phone Number Input -->
					<div class="mb-4">
						<label class="block text-gray-600">Phone number</label>
						<input type="text" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" autocomplete="off" name="su_phone" required />
					</div>
					<!-- Password Input -->
					<div class="mb-4">
						<label class="block text-gray-600">Password</label>
						<input type="password" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" autocomplete="off" name="su_password" required />
					</div>
					<div class="mb-4">
						<label class="block text-gray-600">Confirm password</label>
						<input type="password" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" autocomplete="off" name="su_c_password" required />
					</div>
					<button type="submit" class="bg-gray-900 hover:bg-black text-white font-semibold rounded-md py-2 px-4 w-full" name="su_button">Sign up</button>
				</form>
				<!-- Sign up  Link -->
				<div class="mt-6 text-black text-center">
					<a href="index.php" class="hover:underline">Login Here</a>
				</div>
			<?php
			} else {
			?>
				<h1 class="text-2xl font-semibold mb-4">Sign in</h1>
				<form action="#" method="POST">
					<!-- Username Input -->
					<div class="mb-4">
						<label for="username" class="block text-gray-600">Username</label>
						<input type="text" id="username" name="si_name" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" autocomplete="off" required/>
					</div>
					<!-- Password Input -->
					<div class="mb-4">
						<label for="password" class="block text-gray-600">Password</label>
						<input type="password" id="password" name="si_password" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" autocomplete="off" required/>
					</div>
					<!-- Login Button -->
					<button type="submit" class="bg-gray-900 hover:bg-black text-white font-semibold rounded-md py-2 px-4 w-full" name="si_button">Login</button>
				</form>
				<!-- Sign up  Link -->
				<div class="mt-6 text-black text-center">
					<a href="?signup=1" class="hover:underline">Sign up Here</a>
				</div>
			<?php 
			} ?>
		
			<?php
			if(isset($_GET["registered"])){

			?>
				<div class="bg-emerald-600 rounded-lg p-2 mt-4 font-semibold text-white text-center">Registered</div>
			<?php 
			} elseif(isset($_GET["invalid"])){ 
			?>
				<div class="bg-red-600 rounded-lg p-2 mt-4 font-semibold text-white text-center">Passwords don't match</div>
			<?php
			} else if(isset($_GET["not_registered"])){
			?>
				<div class="bg-red-600 rounded-lg p-2 mt-4 font-semibold text-white text-center">Account doesn't exist</div>
			<?php
			} else if(isset($_GET["invalid_access"])) { 
			?>
				<div class="bg-red-600 rounded-lg p-2 mt-4 font-semibold text-white text-center">Invalid password</div>
			<?php
			} 
			?>
		</div>
	</div>
</body>
</html>


<?php 
	require_once("dbconnect.php");
	if(isset($_POST["su_button"])){
		$su_name = mysqli_real_escape_string($db, $_POST["su_name"]);
		$su_phone = mysqli_real_escape_string($db, $_POST["su_phone"]);
		$su_password = mysqli_real_escape_string($db, $_POST["su_password"]);
		$su_c_password = mysqli_real_escape_string($db, $_POST["su_c_password"]);
		$su_role = "Voter";

		if($su_password == $su_c_password){
			mysqli_query($db, "INSERT INTO users(username, phone, password, user_role) VALUES('".$su_name."', '".$su_phone."', '".hash('sha256', $su_password)."', '".$su_role."')") or die(mysqli_error($db));
?>
			<script> location.assign("index.php?signup=1&registered=1"); </script>
		<?php
		} else {
		?>
			<script> location.assign("index.php?signup=1&invalid=1"); </script>
	<?php
		}
	} else if(isset($_POST["si_button"])) {
		$si_name = mysqli_real_escape_string($db, $_POST["si_name"]);
		$si_password = mysqli_real_escape_string($db, $_POST["si_password"]);

		$query = mysqli_query($db, "SELECT*FROM users 	WHERE username='".$si_name."';") or die(mysqli_error($db));
		
		if(mysqli_num_rows($query)>0){
			$data = mysqli_fetch_assoc($query);
			if($data["password"]==hash('sha256',$si_password)){
				$_SESSION["user_role"]=$data["user_role"];
				$_SESSION["username"]=$data["username"];
				$_SESSION["userid"]=$data["id"];
				if($data["user_role"]=="Admin"){
	?>
					<script>location.assign("admin/index.php")</script>
				<?php
				} else {
				?> 
					<script>location.assign("voter/index.php")</script>
			<?php
				}
			} else {
			?>
				<script> location.assign("index.php?invalid_access=1"); </script>
		<?php
			}
		} else {
		?>
			<script> location.assign("index.php?not_registered=1"); </script>
<?php
		}
	}
?>