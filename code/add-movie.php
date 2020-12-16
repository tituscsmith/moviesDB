<?php

	include('config/db_connect.php');

	//Variable names
	$title = $actorRanks = $spanish_title = $year = $rtID = $actorNames = $audience_rating = $url = $directorIDs  = $location = $country = $genres = $actorIDs = $audience_score = $ratings_count = '';
	$errors = array('country' => '', 'actorRanks' => '', 'year' => '', 'rtID' => '',  'spanish_title' => '','title' => '', 'location' => '', 'actorNames' => '', 'directorIDs' => '','url' => '', 'audience_rating' => '', 'ratings_count' => '', 'audience_score' => '', 'actorIDs' => '','genres' => '');
	session_start();

	//Make sure logged in
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["admin"]) || $_SESSION["admin"] !== true){
	    header("location: index.php");
	    exit;
	}
	if(isset($_POST['submit'])){
		
		// check title
		if(empty($_POST['title'])){
			$errors['title'] = 'A title is required';
		} else{
			$title = $_POST['title'];
		}
		// check rtID
		if(empty($_POST['rtID'])){
			$errors['rtID'] = 'A rtID is required';
		} else{
			$rtID= $_POST['rtID'];
		}
		// check title
		if(empty($_POST['spanish_title'])){
			$errors['spanish_title'] = 'A spanish title is required';
		} else{
			$spanish_title = $_POST['spanish_title'];
		}
		// check directorIDs
		if(empty($_POST['directorIDs'])){
			$errors['directorIDs'] = 'Director IDs are required';
		} else{
			$directorIDs = $_POST['directorIDs'];
		}
		if(empty($_POST['actorIDs'])){
			$errors['actorIDs'] = 'Actor IDs are required';
		} else{
			$actorIDs = $_POST['actorIDs'];
		}

		if(empty($_POST['audience_rating'])){
			$errors['audience_rating'] = 'An audience rating is required';
		} else{
			$audience_rating = $_POST['audience_rating'];
		}
		if(empty($_POST['country'])){
			$errors['country'] = 'A country is required';
		} else{
			$country = $_POST['country'];
			
		}
		if(empty($_POST['genres'])){
			$errors['genres'] = 'Genres are required';
		} else{
			$genres = $_POST['genres'];
		}
		if(empty($_POST['url'])){
			$errors['url'] = 'A picture url is required';
		} else{
			$url = $_POST['url'];
		}
		if(empty($_POST['ratings_count'])){
			$errors['ratings_count'] = 'A ratings count is required';
		} else{
			$ratings_count = $_POST['ratings_count'];
		}
		if(empty($_POST['audience_score'])){
			$errors['audience_score'] = 'Audience score is required';
		} else{
			$audience_score = $_POST['audience_score'];
		}
		if(empty($_POST['location'])){
			$errors['location'] = 'A location is required';
		} else{
			$location = $_POST['location'];
		}
		if(empty($_POST['year'])){
			$errors['year'] = 'A year is required';
		} else{
			$year = $_POST['year'];
		}
		if(empty($_POST['actorRanks'])){
			$errors['actorRanks'] = 'Actor Rankings are required';
		} else{
			$actorRanks = $_POST['actorRanks'];
		}

		//Check if there are any filters first
		if(array_filter($errors)){
			print_r($errors);
		} else {
			//Don't need to use escape strings because it's an admin
			$sqlerror = False;

			//Call stored procedure to add a movie
			$sql1 = "CALL addMovie('$title', '$spanish_title', '$year', '$rtID', '$audience_rating', '$ratings_count', '$audience_score', '$url', '$country', @id)";

			if(mysqli_query($conn, $sql1)){
				// success
			} else {
				echo 'query error1: '. mysqli_error($conn);
				$sqlerror = True;
			}    

			$sql2 = mysqli_query($conn, "SELECT @id");
			$result = mysqli_fetch_assoc($sql2);
			$movieID = $result['@id'];

			//Parse actor id into acted_in table
			$actorString = $actorIDs;
			$actorString = preg_replace('/\.$/', '', $actorString); //Remove dot at end if exists
			$actorArray = explode(', ', $actorString); //split string into array seperated by ', '

			//Parse actor rank into acted_in table
			$rankString = $actorRanks;
			$rankString = preg_replace('/\.$/', '', $rankString); //Remove dot at end if exists
			$rankArray = explode(', ', $rankString); //split string into array seperated by ', '

			//Insert into acted_in table
			for ($i = 0; $i < count($actorArray); $i++) {
			 $sql = "INSERT INTO acted_in VALUES ($movieID, '$actorArray[$i]', '$rankArray[$i]')";

			if(mysqli_query($conn, $sql)){
					// success
				} else {
					echo 'query error2: '. mysqli_error($conn);
					$sqlerror = True;
				}    
			}

			//parse genres
			$genreString = $genres;
			$genreString = preg_replace('/\.$/', '', $genreString); //Remove dot at end if exists
			$genreArray = explode(', ', $genreString); //split string into array seperated by ', '

			//Insert into has_genre table
			for ($i = 0; $i < count($genreArray); $i++) {

			          $sql = "INSERT INTO has_genre VALUES ($movieID, '$genreArray[$i]')";

			if(mysqli_query($conn, $sql)){
					// success
				} else {
					echo 'query error3: '. mysqli_error($conn);
					$sqlerror = True;
				}    
			}

			//Parse directors
			$dirString = $directorIDs;
			$dirString  = preg_replace('/\.$/', '', $dirString ); //Remove dot at end if exists
			$dirArray = explode(', ', $dirString ); //split string into array seperated by ', '

			//Insert into directed_by table
			for ($i = 0; $i < count($dirArray); $i++) {

			          $sql = "INSERT INTO directed_by VALUES ($movieID, '$dirArray[$i]')";

			if(mysqli_query($conn, $sql)){
					// success
				} else {
					echo 'query error4: '. mysqli_error($conn);
					$sqlerror = True;
				}    
			}

			//Parse locations
			$locString = $location;
			$locString  = preg_replace('/\.$/', '', $locString ); //Remove dot at end if exists
			$locArray = explode(', ', $locString ); //split string into array seperated by ', '

			//Insert into locations table
			$sql = "INSERT INTO locations VALUES ($movieID, '$locArray[0]', '$locArray[1]', '$locArray[2]', '$locArray[3]')";

			if(mysqli_query($conn, $sql)){
					// success
				} else {
					echo 'query error4: '. mysqli_error($conn);
					$sqlerror = True;
				}    
			}

			//If there were errors, don't relocate
			if($sqlerror == False){
				header('Location: index.php');
			}

			// close connection
			mysqli_close($conn);

	}

?>

<!DOCTYPE html>
<html>
	
	<?php include('templates/header.php'); ?>

	<section class="container grey-text">
		<h4 class="center">Add a Movie</h4>
		<form class="white" action="add-movie.php" method="POST">
			<label>Movie Title</label>
			<input type="text" name="title" value="<?php echo htmlspecialchars($title) ?>">
			<div class="red-text"><?php echo $errors['title']; ?></div>
			<label>Spanish Title</label>
			<input type="text" name="spanish_title" value="<?php echo htmlspecialchars($spanish_title) ?>">
			<div class="red-text"><?php echo $errors['spanish_title']; ?></div>
			<label>Year (Integer)</label>
			<input type="text" name="year" value="<?php echo htmlspecialchars($year) ?>">
			<div class="red-text"><?php echo $errors['year']; ?></div>
			<label>Rotten Tomatoes ID</label>
			<input type="text" name="rtID" value="<?php echo htmlspecialchars($rtID) ?>">
			<div class="red-text"><?php echo $errors['rtID']; ?></div>
			<label>Audience Rating (Decimal, out of 5)</label>
			<input type="text" name="audience_rating" value="<?php echo htmlspecialchars($audience_rating) ?>">
			<div class="red-text"><?php echo $errors['audience_rating']; ?></div>
			<label>Rating count (Integer)</label>
			<input type="text" name="ratings_count" value="<?php echo htmlspecialchars($ratings_count) ?>">
			<div class="red-text"><?php echo $errors['ratings_count']; ?></div>
			<label>Audience Score (Out of 100)</label>
			<input type="text" name="audience_score" value="<?php echo htmlspecialchars($audience_score) ?>">
			<div class="red-text"><?php echo $errors['audience_score']; ?></div>
			<label>Picture URL</label>
			<input type="text" name="url" value="<?php echo htmlspecialchars($url) ?>">
			<div class="red-text"><?php echo $errors['url']; ?></div>
			<label>Country</label>
			<input type="text" name="country" value="<?php echo htmlspecialchars($country) ?>">
			<div class="red-text"><?php echo $errors['country']; ?></div>
			<label>Actor IDs (separated by comma)</label>
			<input type="text" name="actorIDs" value="<?php echo htmlspecialchars($actorIDs) ?>">
			<div class="red-text"><?php echo $errors['actorIDs']; ?></div>
			<label>Actor Rankings (separated by comma)</label>
			<input type="text" name="actorRanks" value="<?php echo htmlspecialchars($actorRanks) ?>">
			<div class="red-text"><?php echo $errors['actorRanks']; ?></div>
			<label>Director IDs (separated by commas)</label>
			<input type="text" name="directorIDs" value="<?php echo htmlspecialchars($directorIDs) ?>">
			<div class="red-text"><?php echo $errors['directorIDs']; ?></div>
			<label>Genres (separated by commas)</label>
			<input type="text" name="genres" value="<?php echo htmlspecialchars($genres) ?>">
			<div class="red-text"><?php echo $errors['genres']; ?></div>
			<label>Location (Country, Region, City, Address - separated by commas; NULL if N/A)</label>
			<input type="text" name="location" value="<?php echo htmlspecialchars($location) ?>">
			<div class="red-text"><?php echo $errors['location']; ?></div>
			
			<div class="center">
				<input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
			</div>
		</form>
	</section>
<?php include('templates/footer.php'); ?>

</html>