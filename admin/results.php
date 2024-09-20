<?php
	$id = $_GET["result"];
	$fetchElection = mysqli_query($db, "SELECT title, status FROM elections WHERE id='".$id."' AND inserted_by='".$_SESSION['userid']."';");

	$row = mysqli_fetch_assoc($fetchElection);
	if($row['status']==1){
		echo "<script>location.assign('index.php')</script>";
	}
?>
<div class="p-12 w-full">
	<h2 class="font-bold text-3xl">Results</h2>
	<h3 class="font-bold mt-2 text-xl">Election: <?php echo $row['title']; ?></h3>
	<table class="table-fixed mt-4 min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
		<thead>
			<tr>
			<th scope="col" class="px-4 py-3 text-start">Name</th>
			<th scope="col" class="px-4 py-3 text-start">Details</th>
			<th scope="col" class="px-4 py-3 text-start">Votes</th>
			</tr>
		</thead>
		<tbody class="">
			<?php
				$fetchElectionCandidates = mysqli_query($db, "SELECT*FROM candidates WHERE election_id='".$id."'");

				if(mysqli_num_rows($fetchElectionCandidates)==0){
			?>
					<tr>
						<td colspan="3" class="bg-red-600 rounded-lg p-2 mt-4 font-semibold text-white text-center mb-2">No candidates found!</td>
					</tr>
			<?php
				} else {
					while($row = mysqli_fetch_assoc($fetchElectionCandidates)){
						$candidateId = $row['id'];
			?>
						<tr>
							<td class = "font-bold text-lg"><?php echo $row['name']; ?></td>
							<td><?php echo $row['details']; ?></td>
							<td><?php 
								$query = "SELECT c.id as id, 
										COALESCE(v.num_votes, 0) AS num_votes 
										FROM candidates c
										LEFT JOIN 
											(SELECT candidate_id, COUNT(voter_id) AS num_votes
											FROM votes
											WHERE election_id = '".$id."'
											GROUP BY candidate_id) v 
										ON c.id = v.candidate_id
										WHERE c.election_id = '".$id."';";

								$fetchVotes = mysqli_query($db, $query);
								if(mysqli_num_rows($fetchVotes)>0){
									while($row=mysqli_fetch_assoc($fetchVotes)){
										if($row['id']==$candidateId){
											echo $row['num_votes'];
										} 
									}
								}
					}
							?></td>
						</tr>
					<?php
				}
					?>
		</tbody>
	</table>
</div>
	