<?php 
	$genre = '';
	$errors = array('genre' => '');
	include('config/db_connect.php');
		// retrieve genre
		if(empty($_POST['genre'])){
			$errors['genre'] = 'A genre Name is required';
		} else{
			$genre = $_POST['genre'];
        }

    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $sql = "SELECT movies.id, movies.title, movies.year, movies.rtPictureURL FROM movies, has_genre WHERE movies.id = has_genre.movieID AND has_genre.genreID LIKE '%$genre%' LIMIT 21";
    //echo $sql;
 
	
    $result = mysqli_query($conn, $sql);
    

	// fetch the resulting rows as an array
    $movies = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    //print_r($genres_arr);

	  //If no data, retrieve error message
	  if (empty($movies) || empty($genre)) {
	  	$result = mysqli_query($conn, $sql);
	  	$movies = mysqli_fetch_all($result, MYSQLI_ASSOC);
	  }


	// free the $result from memory (good practice)
	mysqli_free_result($result);

	// close connection
    mysqli_close($conn);
    
?>
<!DOCTYPE html>
<html>
	
	<?php include('templates/header.php'); ?>

	<h4 class="center grey-text">Genre: <?php echo htmlspecialchars($genre); ?></h4>

	<div class="container">
		<div class="row">
<!-- Case for where movies doesn't exist-->
 			<?php foreach($movies as $movie): ?>

				<div class="col s6 m4">
					<div class="card z-depth-0">
							<div class="card-content center">
							<!-- Link details to image -->
							<a style = "text-align: center" class="brand-text" href="details.php?id=<?php echo $movie['id'] ?>"><img src="<?php echo $movie['rtPictureURL']; ?>" alt="movies image" width="150" height="200"></a>
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