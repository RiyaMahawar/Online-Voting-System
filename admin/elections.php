<div class="grid grid-cols-12">
	<div class="col-span-4 p-12">
		<h3 class="font-bold text-lg">Add new election</h3>
		<form action="#" method="POST" class="mt-4">
			<div class="mb-4">
				<label class="block text-gray-600">Election title</label>
				<input type="text" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" autocomplete="off" name="ele_title" required />
			</div>
			<div class="mb-4">
				<label class="block text-gray-600">Number of candidates?</label>
				<input type="number" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" autocomplete="off" name="ele_candidates" required />
			</div>
			<?php
			if(isset($_GET['added'])){
				?><div class="bg-emerald-600 rounded-lg p-2 mt-4 font-semibold text-white text-center mb-2">Added new election</div>
			<?php	
			} else if(isset($_GET['invalid_date'])){
				?>
				<div class="bg-red-600 rounded-lg p-2 mt-4 font-semibold text-white text-center mb-2">End date should be greater</div>

				<?php
			}
			?>
			<button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-md py-2 px-4 w-full" name="ele_add">Add election</button>
	</form>
	</div>
	<div class="col-span-8 p-12">
		<h3 class="font-bold text-lg">Elections</h3>
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
									<td class="px-4 py-3 whitespace-nowrap text-sm "><?php echo $row["title"]; ?></td>
									<td class="px-4 py-3 whitespace-nowrap text-sm "><?php echo $row["num_candidates"]; ?></td>
									<td class="px-4 py-3 whitespace-nowrap text-sm "><?php echo $row["status"]==1?"Active":"Inactive"; ?></td>
									<td class="px-4 py-3 whitespace-nowrap text-sm flex gap-4">
										<?php if($row['status']) {?>
										<a href="index.php?electionsPage=1&end=<?php echo $row['id'] ?>" class="rounded-sm py-1 px-5 bg-emerald-600 text-white ">End</a>
										<?php } else { ?>
										<a href="index.php?electionsPage=1&resume=<?php echo $row['id'] ?>" class="rounded-sm py-1 px-5 bg-emerald-600 text-white ">Resume</a>
										<?php } ?>
										<a href="index.php?electionsPage=1&delete=<?php echo $row['id'] ?>" class="rounded-sm py-1 px-5 bg-red-600 text-white ">Delete</a>
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
</div>


<?php 		
	if(isset($_POST["ele_add"])){
		$title = mysqli_real_escape_string($db,$_POST["ele_title"]);
		$num_candidates = mysqli_real_escape_string($db,$_POST["ele_candidates"]);
		$inserted_by = $_SESSION["userid"];
		$inserted_on = date("Y-m-d");
		
		mysqli_query($db, "INSERT INTO elections (title, num_candidates, status, inserted_by, inserted_on) VALUES ('".$title."', '".$num_candidates."', '1' , '".$inserted_by."', '".$inserted_on."')") or die(mysqli_error($db));
		?>
		<script>location.assign("index.php?electionsPage=1&added=1")</script>
		<?php
	} else if(isset($_GET["delete"])){
		$id = $_GET["delete"];
		mysqli_query($db, "DELETE FROM elections WHERE id='".$id."'") or die(mysqli_error($db));
		?>
		<script>location.assign("index.php?electionsPage=1&deleted=1")</script>
		<?php
	} else if(isset($_GET["end"])){
		$id = $_GET["end"];
		mysqli_query($db, "UPDATE elections SET status='0' WHERE id='".$id."'");
		?>
		<script>location.assign("index.php?electionsPage=1&ended=1")</script>
		<?php
	} else if(isset($_GET["resume"])){
		$id = $_GET["resume"];
		mysqli_query($db, "UPDATE elections SET status='1' WHERE id='".$id."'");
		?>
		<script>location.assign("index.php?electionsPage=1&resumed=1")</script>
		<?php
	}
?>
