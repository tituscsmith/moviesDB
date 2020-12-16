<?php

include('config/db_connect.php');

 $actor = '';
 $errors = array('actor' => '');
	session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

?>

<!-- Make sure forum is not blank - Credit to w3schools
--><script>function validateForm() {
var x = document.forms["myForm"]["actor"].value;
if (x == "") {
alert("Actor Name must be filled out");
return false;
}
}</script>
<!DOCTYPE html>
<html>

<?php include('templates/header.php'); ?>

<section class="container grey-text">
    <h4 class="center" >Search for a Actor!</h4>
    <form name="myForm" class="white" action="search-by-actor-result.php" onsubmit="return validateForm()" method="POST">
        <label>Enter Actor/Actress Name </label>
        <input type="text" name="actor" value="<?php echo htmlspecialchars($actor) ?>">
        <div class="center">
            <input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
        </div>
    </form>
</section>

<?php include('templates/footer.php'); ?>

</html>