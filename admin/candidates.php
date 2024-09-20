<div class="grid grid-cols-12">
	<div class="col-span-4 p-12">
		<h3 class="font-bold text-lg">Add new candidate</h3>
		<form action="#" method="POST" class="mt-4">
			<div class="mb-4">
				<label class="block text-gray-600">Election title</label>
				<select name="election_id" class="p-2" required>
					<?php
						$fetchElections = mysqli_query($db, "SELECT id, title, num_candidates FROM elections WHERE inserted_by='".$_SESSION["userid"]."'");
						$count = 0;
						if(mysqli_num_rows($fetchElections)>0){
							while($row = mysqli_fetch_assoc($fetchElections)){
								$election_id = $row["id"];
								$election_title = $row["title"];
								$candidates_num = $row["num_candidates"];
								
								$fetch_candidates = mysqli_query($db, "SELECT*FROM candidates WHERE election_id='".$election_id."'");
								
								if(mysqli_num_rows($fetch_candidates)<$candidates_num){
									$count++;
					?>
									<option value="<?php echo $row["id"]; ?>"><?php echo $row["title"]; ?></option>
					<?php
								}
							}
							if($count==0){
					?>
							<option value="">No elections yet</option>
					<?php
							}
						} else {
					?>
							<option value="">No elections yet</option>
							<?php
						}
					?>
				</select>
			</div>
			<div class="mb-4">
				<label class="block text-gray-600">Candidate name</label>
				<input type="text" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" autocomplete="off" name="cand_name" required />
			</div>
			<div class="mb-4">
				<label class="block text-gray-600">Candidate details</label>
				<input type="text" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" name="cand_details" autocomplete="off" required />
			</div>
			<?php
			if(isset($_GET['added'])){
				?><div class="bg-emerald-600 rounded-lg p-2 mt-4 font-semibold text-white text-center mb-2">Added new candidate</div>
			<?php	
			} else if(isset($_GET['failed'])){
				?>
				<div class="bg-red-600 rounded-lg p-2 mt-4 font-semibold text-white text-center mb-2">Election can't be empty</div>
				<?php
			}
			?>
			<button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-md py-2 px-4 w-full" name="cand_add">Add candidate</button>
	</form>
	</div>
	<div class="col-span-8 p-12">
		<h3 class="font-bold text-lg">Candidates</h3>
		<table class="table-fixed min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
			<thead>
				<tr>
				<th scope="col" class="px-4 py-3 text-start">S. No.</th>
				<th scope="col" class="px-4 py-3 text-start">Election</th>
				<th scope="col" class="px-4 py-3 text-start">Name</th>
				<th scope="col" class="px-4 py-3 text-start">Details</th>
				<th scope="col" class="px-4 py-3 text-start">Action</th>
				</tr>
			</thead>
			<tbody class="">
				<?php
					
					$fetchingData = mysqli_query($db, "SELECT c.id, e.title, c.name, c.details FROM candidates c JOIN elections e ON e.id=c.election_id WHERE e.inserted_by='".$_SESSION['userid']."'") or die(mysqli_error($db));
					if(isset($_GET['deleted'])){
						?>
						<tr>
							<td colspan="7" class="bg-emerald-600 rounded-lg p-2 mt-4 font-semibold text-white text-center mb-2">Deleted candidate</td>
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
									<?php echo $row["name"]; ?>
									</td>
									<td class="px-4 py-3 whitespace-nowrap text-sm "><?php echo $row["details"]; ?></td>
									<td class="px-4 py-3 whitespace-nowrap text-sm ">
										<a href="index.php?candidatesPage=1&delete=<?php echo $row['id'] ?>" class="rounded-sm py-1 px-5 bg-red-600 text-white ">Delete</a>
									</td>
								</tr>
							<?php
						}
					} else {
						?>
						<tr>
							<td colspan="7">No candidates added yet</td>
						</tr>
						<?php
					}
				?>
			</tbody>
		</table>
	</div>
</div>


<?php 
	if(isset($_POST['cand_add'])){
		$election_id = $_POST["election_id"];
		$name = $_POST["cand_name"];
		$details = $_POST["cand_details"];

		if(empty($election_id)){
?>
			<script>location.assign("index.php?candidatesPage=1&failed=1")</script>
<?php
		}
		mysqli_query($db, "INSERT INTO candidates (election_id, name, details, inserted_by) VALUES('".$election_id."', '".$name."', '".$details."', '".$_SESSION['userid']."')") or die(mysqli_error($db));
?>
		<script>location.assign("index.php?candidatesPage=1&added=1");</script>
<?php
	} else if(isset($_GET["delete"])){
		$id = $_GET["delete"];
		mysqli_query($db, "DELETE FROM candidates WHERE id='".$id."'") or die(mysqli_error($db));
?>
		<script>location.assign("index.php?candidatesPage=1&deleted=1")</script>
<?php
	}
?>