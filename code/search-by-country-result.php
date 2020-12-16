<?php 
	$country = '';
	$errors = array('country' => '');
	include('config/db_connect.php');
		// retrieve country
		if(empty($_POST['country'])){
			$errors['country'] = 'A Country Name is required';
		} else{
			$country = $_POST['country'];
		}

	$country = mysqli_real_escape_string($conn, $_POST['country']);
	$countryWithWildcard = $country . '%';

    $sql = "CALL getMovieByCountry('$countryWithWildcard');";


	
	$result = mysqli_query($conn, $sql);
	

	// fetch the resulting rows as an array
	$movies = mysqli_fetch_all($result, MYSQLI_ASSOC);



	  //If no data, retrieve error message
	  if (empty($movies) || empty($country)) {
	  	$result = mysqli_query($conn, $sql);
	  }
	  else{

	// free the $result from memory (good practice)
	mysqli_free_result($result);

	// close connection
	mysqli_close($conn);


	  }
?>

<!DOCTYPE html>
<html>
	
	<?php include('templates/header.php'); ?>

	<h4 class="center grey-text">Country: <?php echo htmlspecialchars($country); ?></h4>

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