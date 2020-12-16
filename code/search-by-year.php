 <?php

	include('config/db_connect.php');

 	$year = '';
 	$errors = array('year' => '');
  	
  	//Make sure logged in
  	session_start();
	if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true)){
	    header("location: index.php");
	    exit;
	}
?>

<!-- Make sure input is valid
 --><script>function validateForm() {
  var x = parseInt(document.forms["myForm"]["year"].value);
  if(typeof x==='number' && (x%1)===0 && x>1900 && x <2025) {
    return true;
}
else{
	alert("Please enter a valid input");
	return false;
}
}</script>
<!DOCTYPE html>
<html>
	
	<?php include('templates/header.php'); ?>

	<section class="container grey-text">
		<h4 class="center" >Search for a Movie By Year!</h4>
		<form name="myForm" class="white" action="search-year-result.php" onsubmit="return validateForm()" method="POST">
			<label>Enter Movie Year</label>
			<input type="text" name="year" value="<?php echo htmlspecialchars($year) ?>">
			<div class="center">
				<input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
			</div>
		</form>
	</section>

	<?php include('templates/footer.php'); ?>

</html>