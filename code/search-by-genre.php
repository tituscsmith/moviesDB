 <?php

include('config/db_connect.php');

 $genre = '';
 $errors = array('genre' => '');
	session_start();

//SESSION Doesn't work
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}



?>

<!-- Make sure forum is not blank - Credit to w3schools
--><script>function validateForm() {
var x = document.forms["myForm"]["actor"].value;
if (x == "--select--") {
alert("Genre Name must be filled out");
return false;
}
}</script>



<!DOCTYPE html>
<html>
<?php include('templates/header.php'); ?>
<section class="container grey-text">
		<h4 class="center" >Search for a Movie By Genre!</h4>
		<form name="myForm" class="white" action="search-by-genre-result.php" onsubmit="return validateForm()" method="POST">
			<label>Enter Movie Genre</label>
			<input type="text" name="genre" value="<?php echo htmlspecialchars($genre) ?>">
			<div class="center">
				<input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
			</div>
		</form>
	</section>

<?php include('templates/footer.php'); ?>

</html>