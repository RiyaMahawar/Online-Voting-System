<?php 
session_start();
require_once("middleware.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Voters Panel</title>
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
        		<a href="index.php?old=1" class="text-sm font-medium hover:underline underline-offset-4">Past elections</a>
        		<a href="index.php?logout=1" class="text-sm font-medium hover:underline underline-offset-4">Logout</a>
      		</nav>
	  		<p>
			Welcome <?php echo $_SESSION["username"] ?>  
			</p>
    	</header>
    	<main class="flex-1 flex">
		<?php
			if(isset($_GET["logout"])){
				session_destroy();
				session_unset();
		?>
			<script>location.assign("../index.php");</script>
		<?php
			} else if(isset($_GET["old"])){
				require_once('past_elections.php');
			} else {
		?>
		
			<div class="grid grid-cols-12 ml-24 mt-16 w-full mr-24">
				<div class ="col-span-12">
					<h3 class="text-3xl font-bold">Voters panel</h3>
					<div class="space-y-8">
					<?php 
						$fetchingActiveElections = mysqli_query($db, "SELECT*FROM elections WHERE status=1") or die(mysqli_error($db));

						if(mysqli_num_rows($fetchingActiveElections)>0){
							while($eRow = mysqli_fetch_assoc($fetchingActiveElections)){
					?>
								<table class="table-fixed min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
									<thead>
										<tr>
											<th scope="col" class="py-3 text-start" colspan="3"> <h5> Election topic: <?php echo $eRow["title"] ?></h5></th>
										</tr>
										<tr>
											<th scope="col" class="py-3 text-start" ><h5> Candidate details </h5></th>
											<th scope="col" class="px-4 py-3 text-start" ><h5> # of votes </h5></th>
											<th scope="col" class="px-4 py-3 text-start" ><h5> Action </h5></th>
										</tr>
									</thead>
									<tbody class="">
									<?php
										$fetchCandidates = mysqli_query($db, "SELECT*FROM candidates WHERE election_id='".$eRow["id"]."'"); 
										if(mysqli_num_rows($fetchCandidates)>0){
											while($cRow = mysqli_fetch_assoc($fetchCandidates)){
												$fetchVotes = mysqli_query($db, "SELECT*FROM votes WHERE candidate_id='".$cRow["id"]."'");
												$totalVotes = mysqli_num_rows($fetchVotes);
									?>
												<tr>
													<td scope="col" class="py-3 text-start" >
													<h5><?php echo '<strong>'.$cRow['name'].'</strong><br>'.$cRow['details']; ?>
													</h5></td>
													<td scope="col" class="px-4 py-3 text-start" >
													<h5><?php echo $totalVotes; ?>
													</h5></td>
													<td scope="col" class="px-4 py-3 text-start" >
													<h5>
													<?php
													$fetchVoteCased = mysqli_query($db, "SELECT*FROM votes WHERE voter_id='".$_SESSION["userid"]."' AND election_id='".$eRow["id"]."' ;");
													if(mysqli_num_rows($fetchVoteCased)>0){
														$row = mysqli_fetch_assoc($fetchVoteCased);
														if($cRow['id']==$row['candidate_id']){
													?>
															<p>Voted</p>
													<?php
														}
													} else {
													?> 
														<a href="index.php?vote=<?php echo $cRow['id']; ?>&election=<?php echo $eRow['id'];?>" class="rounded-sm py-1 px-5 bg-emerald-600 text-white">Vote</a>
													<?php
													}
													?>
													</h5></td>
												</tr>
										<?php
											}
										} else {
										?>
											<tr>
												<td colspan="3"><?php echo "No candidates registered";?></td>
											</tr>
										<?php 
										}
										?>
									</tbody>
								</table>
						<?php
							}
						} else {
							echo "No active elections";
						}
						?>
					</div>
				</div>
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
	if(isset($_GET['vote']) && isset($_GET['election'])){
		$c_id = $_GET['vote'];
		$e_id = $_GET['election'];
		$vote_date = date("Y-m-d");
		$vote_time = date("h:i:s a ");
		mysqli_query($db, "INSERT INTO votes (election_id, voter_id, candidate_id, vote_date, vote_time) VALUES ('".$e_id."', '".$_SESSION['userid']."', '".$c_id."', '".$vote_date."', '".$vote_time."')");
?>
		<script>location.assign("index.php?success=1");</script>
<?php
	}
?>