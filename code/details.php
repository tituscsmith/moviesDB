<?php 

	// connect to the database
	include('config/db_connect.php');
    
    //Make sure logged in
//    session_start();
//    if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true)){
//        header("location: index.php");
//        exit;
//    }
    
    // check GET request id param
    if(isset($_GET['id'])){
        
        // escape sql chars
        $id = mysqli_real_escape_string($conn, $_GET['id']);

        // Get top three actors
        $actorQuery = "SELECT GROUP_CONCAT(a.actorName order by i.ranking SEPARATOR ', ') FROM acted_in i INNER JOIN actors a ON i.actorID = a.actorID WHERE i.movieID = $id and i.ranking < 4";
        $result = mysqli_query($conn, $actorQuery);
        $actors = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        
        // Get movie genres
        $genreQuery = "SELECT GROUP_CONCAT(TRIM(TRAILING '\r' FROM g.genreID) SEPARATOR ', ') FROM has_genre g WHERE g.movieID = $id";
        $result2 = mysqli_query($conn, $genreQuery);
        $genres = mysqli_fetch_assoc($result2);
        mysqli_free_result($result2);
        
        // call stored procedure for movie details
        $sql = "CALL GetMovieDetails($id)";
        $result3 = mysqli_query($conn, $sql);
        $movie = mysqli_fetch_assoc($result3);
        mysqli_free_result($result3);


        mysqli_close($conn);

    }
    
?>

<!DOCTYPE html>
<html>
    <?php include('templates/header.php'); ?>

    <div class="container center black-text">
        <?php if($movie): ?>
            <div class="card" style = "height: 450px;">
            <table>
                <tr>
                    <td style = "padding-left: 40px; width: 300px">
                        <a class="brand-text" href="details.php?id=<?php echo $movie['id'] ?>"><img src="<?php echo $movie['rtPictureURL']; ?>" alt="movie image" width="225" height="300"></a>
                    </td>
                    <td style = "vertical-align:top;">
                        <h4 style = "text-align: center; color: #498bf5"><?php echo $movie['title']; ?></h4>
                        </br>
                        <h6><b>Título Español: </b><?php echo $movie['spanishTitle']; ?></h6>
                        <h6><b>Director: </b><?php echo $movie['directorName']; ?></h6>
                        <div>
                            <?php foreach($actors as $actor): ?>
                                <h6><b>Top Three Actors: </b><?php echo $actor; ?></h6>
                            <?php endforeach; ?>
                        </div>
                        <div>
                            <?php foreach($genres as $genre): ?>
                                <h6><b>Genre(s): </b><?php echo $genre; ?></h6>
                            <?php endforeach; ?>
                        </div>

                        <h6><b>Year:</b> <?php echo $movie['year']; ?></h6>
                        <h6><b>Country of Origin: </b><?php echo $movie['country']; ?></h6>
                        <h6><b>Rotten Tomotoes Audience Score: </b><?php echo $movie['rtAudienceScore']; ?></h6>
                        </br></br>
                        <div style= "text-align: center">
                            <a class="brand-text" href="add-user-rating.php?id=<?php echo $id ?>"><button type="button" style = "border: none; border-radius: 2px; background-color: #26a69a; color: white; height: 32px; cursor: pointer;">Add a rating!</button></a>
                        </div>
                        
                    </td>
                </tr>
            </table>
            </div>

        <?php else: ?>
            <h5>No such movie exists.</h5>
        <?php endif ?>
    </div>

    <?php include('templates/footer.php'); ?>

</html>