<?php 

	// connect to the database
	include('config/db_connect.php');
	// $conn = mysqli_connect('localhost', 'root', '', 'movies');
	session_start();

	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	    header("location: login.php");
	    exit;
	}


	// write query for all movies
	$sql = 'SELECT movies.title, movies.id, movies.year, movies.rtPictureURL FROM movies WHERE movies.rtAudienceNumRatings > 5000 ORDER BY movies.rtAudienceScore DESC LIMIT 21';

// SELECT movies.title, movies.year, reviewed_in.rt_id FROM reviewed_in INNER JOIN movies ON movies.id = reviewed_in.movie_id LIMIT 20
	// get the result set (set of rows)
	$result = mysqli_query($conn, $sql);

	// fetch the resulting rows as an array
	$movies = mysqli_fetch_all($result, MYSQLI_ASSOC);

	// free the $result from memory (good practise)
	mysqli_free_result($result);

	// close connection
	mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
	
	<?php include('templates/header.php'); ?>

	<h4 class="center blue-text">Top Rated Movies</h4>

	<div class="container">
		<div class="row">

			<?php foreach($movies as $movie): ?>
				<!-- 3 -->
				<div class="col s6 m4">
					<div class="card z-depth-0" style = "height: 300px;">
						<div class="card-content center">
							<!-- Link details to image -->
							<a style = "text-align: center" class="brand-text" href="details.php?id=<?php echo $movie['id'] ?>"><img src="<?php echo $movie['rtPictureURL']; ?>" alt="movie image" width="150" height="200"></a>
						</div>
						<div class="card-action right-align">
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

