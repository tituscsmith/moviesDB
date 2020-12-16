<?php

    include('config/db_connect.php');

    //Make sure logged in
    session_start();
    if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true)){
        header("location: index.php");
        exit;
    }
    
    if(isset($_GET['id'])){
        
        // escape sql chars
        $id = mysqli_real_escape_string($conn, $_GET['id']);

        // make sql
        $sql = "SELECT movies.title, movies.year, movies.country, movies.rtPictureURL, movies.rtAudienceNumRatings, movies.rtAudienceScore FROM movies WHERE id = $id";
        
        // get the query result
        $result = mysqli_query($conn, $sql);

        // fetch result in array format
        $movie = mysqli_fetch_assoc($result);

        mysqli_free_result($result);
        
    }

?>


<!DOCTYPE html>
<html>
    <head>
       <style>
          .error {color: #FF0000;}
       </style>
    </head>
    <?php include('templates/header.php'); ?>
    <?php
        $score = $scoreErr = "";
        $showform = "";
        $success = "display:none";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $score = $_POST["score"];
            if (empty($_POST["score"])) {
                $scoreErr = "Score is required";
                $score="";
            }else if (!is_numeric($_POST["score"])) {
                $scoreErr = "Score must be numeric";
                $score=$_POST["score"];
            }else if ($score < 1 || $score > 100) {
                $scoreErr = "Score must be between 1 and 100";
                $score=$_POST["score"];
            }else {
                $score = test_input($_POST["score"]);
                $scoreErr = "";
                $update_query = "UPDATE movies SET rtAudienceScore =  FLOOR((((rtAudienceNumRatings*rtAudienceScore) + $score)/rtAudienceNumRatings+1)), rtAudienceNumRatings = rtAudienceNumRatings+1 WHERE id = $id;";
               if (mysqli_query($conn, $update_query)) {
                   $showform = "display:none";
                   $success = "";
               } else {
                   echo "Error updating record: " . mysqli_error($conn);
               }
               $username = htmlspecialchars($_SESSION["username"]);
               $insert_query = "INSERT INTO rated_by (movieID, userID, rating) VALUES ($id, '$username', $score)";
                if (mysqli_query($conn, $insert_query)) {
                   $showform = "display:none";
                   $success = "";
               } else {
                   echo "Error updating record: " . mysqli_error($conn);
               }


               mysqli_close($conn);
           }
        }
       
       function test_input($data) {
          $data = trim($data);
          $data = stripslashes($data);
          $data = htmlspecialchars($data);
          return $data;
       }
    ?>

    <div class="container center black-text">
        <?php if($movie): ?>
            <div class="card" style = "height: 450px;">
            <table>
                <tr>
                    <td style = "padding-left: 40px; width: 300px">
                        <a class="brand-text" href="details.php?id=<?php echo $_GET['id']?>"><img src="<?php echo $movie['rtPictureURL']; ?>" alt="movie image" width="225" height="300"></a>
                    </td>
                    <td style = "vertical-align:top;">
                        <h4 style="color:#498bf5; text-align: center"><?php echo $movie['title']; ?></h4>
                    <div style="<?php echo $showform; ?>">
                        <form action="add-user-rating.php?id=<?php echo $_GET['id']?>" method="POST">
                                <text>Add a score out of 100:</text>
                                <input type="text" name="score" value="<?php echo $score ?>"</input>
                                <h9 style="color:red;"><?php echo $scoreErr; ?></h9>
                                <div style="text-align: center">
                                    </br>
                                    <input type="submit" style = "border: none; border-radius: 2px; background-color: #26a69a; color: white; height: 32px; cursor: pointer;" value="SUBMIT"></input>
                                </div>
                                <input type="hidden" name="id" value=<?php echo $_GET['id']?>></input>
                            </form>
                    </div>
                    <div style="<?php echo $success; ?>">
                        </br></br>
                        <h6 style = "text-align: center; color: black">"Rating successfully submitted!"</h6>
                        <div style= "text-align: center"></br></br>
                            <a class="brand-text" href="index.php"><button type="button" style = "border: none; border-radius: 2px; background-color: #26a69a; color: white; height: 32px;">BACK TO HOME</button></a>
                        </div>
                    </div>
                    </td>
                </tr>
            </table>
            </div>


            <!-- DELETE FORM -->

            <!-- <form action="details.php" method="POST">
                <input type="hidden" name="id_to_delete" value="<?php echo $movie['id']; ?>">
                <input type="submit" name="delete" value="Delete" class="btn brand z-depth-0">
            </form>
            <form action="update.php?id=<?php echo $movie['id'] ?>" method="POST">
                <input type="hidden" name="id_to_update" value="<?php echo $movie['id']; ?>">
                <input type="submit" name="update" value="Update" class="btn brand z-depth-0">
            </form> -->
    

        <?php else: ?>
            <h5>No such movie exists.</h5>
        <?php endif ?>
    </div>

    <?php include('templates/footer.php'); ?>

</html>