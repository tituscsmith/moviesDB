<?php 
	$year = '';
	$errors = array('year' => '');
	include('config/db_connect.php');
		// retrieve year
		if(empty($_POST['year'])){
			$errors['year'] = 'A year is required';
		} else{
			$year = $_POST['year'];
		}

		//My escape string prevents injections
	$year = mysqli_real_escape_string($conn, $_POST['year']);
	// if(empty($_POST['year'])){
 //    echo "year field cannot be left blank";
	// }

	//$sql = "SELECT * FROM movies WHERE movies.year LIKE '%$year%' LIMIT 21";
	$sql = "CALL getMovieByYear('$year')";
	$result = mysqli_query($conn, $sql);

	// fetch the resulting rows as an array
	$movies = mysqli_fetch_all($result, MYSQLI_ASSOC);

	  //If no data, retrieve error message
	  if (empty($movies) || empty($year)) {
	  	$result = mysqli_query($conn, $sql);
	  	$movies = mysqli_fetch_all($result, MYSQLI_ASSOC);
	  }

	  //print_r($movies);

	// free the $result from memory (good practice)
	mysqli_free_result($result);

	// close connection
	mysqli_close($conn);


?>

<!DOCTYPE html>
<html>
	
	<?php include('templates/header.php'); ?>

	<h4 class="center grey-text">Movies with Year: <?php echo htmlspecialchars($year); ?></h4>

	<div class="container">
		<div class="row">
<!-- Case for where movie doesn't exist-->
 			<?php foreach($movies as $movie): ?>

				<div class="col s6 m4">
					<div class="card z-depth-0">
							<div class="card-content center">
							<!-- Link details to image -->
							<a style = "text-align: center" class="brand-text" href="details.php?id=<?php echo $movie['id'] ?>"><img src="<?php echo $movie['rtPictureURL']; ?>" alt="movie image" width="150" height="200"></a>
						</div>
						
						<div class="card-action right-align">
							<a class="brand-text" href="details.php?id=<?php echo $movie['id'] ?>">more info</a>
                            <h5><?php echo htmlspecialchars($movie['title']); ?></h5>
							<h6><?php echo htmlspecialchars($movie['year']); ?></h6>
						</div> 
					</div>
				</div>

			<?php endforeach; ?> 

		</div>
	</div>

	<?php include('templates/footer.php'); ?>

</html>