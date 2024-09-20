<?php 
session_start();
require_once("middleware.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Admin Panel</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
  	<div class="flex flex-col min-h-[100dvh]">
		<header class="bg-primary text-primary-foreground px-4 lg:px-6 py-4 flex items-center justify-between">
			<a href="#" class="flex items-center gap-2">
				<span class="text-xl font-bold">Voting system</span>
			</a>
			<nav class="hidden lg:flex items-center gap-6">
				<a href="index.php" class="text-sm font-medium hover:underline underline-offset-4">Home</a>
				<a href="index.php?electionsPage=1" class="text-sm font-medium hover:underline underline-offset-4">Elections</a>
				<a href="index.php?candidatesPage=1" class="text-sm font-medium hover:underline underline-offset-4">Candidates</a>
				<a href="index.php?logout=1" class="text-sm font-medium hover:underline underline-offset-4">Logout</a>
			</nav>
			<p>
				Welcome <?php echo $_SESSION["username"] ?>  
			</p>
		</header>
    	<main class="flex-1 flex justify-center">
		<?php
			if(isset($_GET["candidatesPage"])){
				require_once("candidates.php");
			} else if(isset($_GET["logout"])){
				session_destroy();
				session_unset();
		?>
				<script>location.assign("../index.php");</script>
		<?php
			} else if(isset($_GET["electionsPage"])) {
				require_once("elections.php");
			} else if(isset($_GET["result"])) {
				require_once('results.php');
			}
			else {
		?>
				<div class="p-12 w-full">
					<h3 class="font-bold text-3xl">Elections</h3>
					<table class="table-fixed min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
						<thead>
							<tr>
								<th scope="col" class="px-4 py-3 text-start">S. No.</th>
								<th scope="col" class="px-4 py-3 text-start">Title</th>
								<th scope="col" class="px-4 py-3 text-start"># Candidates</th>
								<th scope="col" class="px-4 py-3 text-start">Status</th>
								<th scope="col" class="px-4 py-3 text-start">Action</th>
							</tr>
						</thead>
						<tbody class="">
							<?php
							$fetchingData = mysqli_query($db, "SELECT*FROM elections WHERE inserted_by='".$_SESSION["userid"]."'") or die(mysqli_error($db));
							if(isset($_GET['deleted'])){
							?>
								<tr>
									<td colspan="7" class="bg-emerald-600 rounded-lg p-2 mt-4 font-semibold text-white text-center mb-2">Deleted election</td>
								</tr>
							<?php
							}
							if(isset($_GET['ended'])){
							?>
								<tr>
									<td colspan="7" class="bg-emerald-600 rounded-lg p-2 mt-4 font-semibold text-white text-center mb-2">Ended election</td>
								</tr>
							<?php
							}
							if(isset($_GET['resumed'])){
							?>
								<tr>
									<td colspan="7" class="bg-emerald-600 rounded-lg p-2 mt-4 font-semibold text-white text-center mb-2">Resumed election</td>
								</tr>
							<?php
							}		
							if(mysqli_num_rows($fetchingData)>0){			
								while($row = mysqli_fetch_assoc($fetchingData)){
							?>
									<tr>
										<td class="px-4 py-3 whitespace-nowrap text-sm "><?php echo $row["id"]; ?></td>
										<td class="px-4 py-3 whitespace-nowrap text-sm ">
										<?php echo $row["title"]; ?>
										</td>
										<td class="px-4 py-3 whitespace-nowrap text-sm ">
										<?php echo $row["num_candidates"]; ?>
										</td>
										<td class="px-4 py-3 whitespace-nowrap text-sm "><?php echo $row["status"]==1?"Active":"Inactive"; ?></td>
										<td class="px-4 py-3 whitespace-nowrap text-sm flex gap-4">
										<?php 
										if($row['status']) {
										?>
											<a href="index.php?end=<?php echo $row['id'] ?>" class="rounded-sm py-1 px-5 bg-emerald-600 text-white ">End</a>
										<?php 
										} else {
										?>
											<a href="index.php?result=<?php echo $row['id'] ?>" class="rounded-sm py-1 px-5 bg-emerald-600 text-white ">Results</a>
										<?php 
										} 
										?>
											<a href="index.php?delete=<?php echo $row['id'] ?>" class="rounded-sm py-1 px-5 bg-red-600 text-white ">Delete</a>
										</td>
									</tr>
								<?php
								}
							} else {
								?>
								<tr>
									<td colspan="7">No elections added yet</td>
								</tr>
							<?php
							}
							?>
						</tbody>
					</table>
				</div>
			<?php
			}
			?>

		</main>
    	<footer class="bg-muted text-muted-foreground px-4 lg:px-6 py-6 flex flex-col lg:flex-row items-center justify-between gap-4">
      	<div class="flex items-center w-full justify-center gap-2">
        	<span>&copy; Copyright 2024. All rights reserved.</span>
      	</div>
    	</footer>
  	</div>
</body>
</html>

<?php
if(isset($_GET["delete"])){
	$id = $_GET["delete"];
	mysqli_query($db, "DELETE FROM elections WHERE id='".$id."'") or die(mysqli_error($db));
?>
	<script>location.assign("index.php?deleted=1")</script>
<?php
} else if(isset($_GET["end"])){
	$id = $_GET["end"];
	mysqli_query($db, "UPDATE elections SET status='0' WHERE id='".$id."'");
?>
	<script>location.assign("index.php?ended=1")</script>
<?php
} 
?>