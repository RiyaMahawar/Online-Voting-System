<div class="grid grid-cols-12 ml-24 mt-16 w-full mr-24">
	<div class ="col-span-12">
		<h3 class="text-3xl font-bold">Past elections</h3>
		<div class="space-y-8">
		<?php 
			$fetchEndedElections = mysqli_query($db, "SELECT*FROM elections WHERE status=0") or die(mysqli_error($db));

			if(mysqli_num_rows($fetchEndedElections)>0){
				while($eRow = mysqli_fetch_assoc($fetchEndedElections)){
					echo "<h3 class='font-bold text-xl mt-4'>".$eRow['title']."</h3>";
					$query = "
				SELECT 
					c.id,
					c.name,
					COUNT(v.id) AS total_votes
				FROM 
					candidates c
				JOIN 
					votes v ON c.id = v.candidate_id
				WHERE 
					c.election_id = '" . $eRow["id"] . "'
				GROUP BY 
					c.id, c.name
				ORDER BY 
					total_votes DESC
				LIMIT 1";

			$fetchMaxVotes = mysqli_query($db, $query);

			if(mysqli_num_rows($fetchMaxVotes)>0){
				$row = mysqli_fetch_assoc($fetchMaxVotes);
				echo "Candidate ".$row['name']." won the election with ".$row['total_votes']. " votes.";
			} else {
				echo "No candidates stood in this election.";
			}
			}
			}
			else {
				echo "<p class='mt-4'>No elections that have ended</p>";
			}
		?>
	</div>
	</div>
</div>