<head>
  <title>Movie Database</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <link rel = "stylesheet"
         href = "https://fonts.googleapis.com/icon?family=Material+Icons">
      <script type = "text/javascript"
         src = "https://code.jquery.com/jquery-2.1.1.min.js"></script>           
      <script src = "https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/js/materialize.min.js">
      </script> 
  <style type="text/css">
    .brand{
      background: #498bf5 !important;
    }
    .brand-text{
      color: #498bf5 !important;
    }
    form{
      max-width: 460px;
      margin: 20px auto;
      padding: 20px;
    }

    .col{
      height: 500px;
    }
    

  </style>
</head>
<body class="grey lighten-4">

  <nav class="white z-depth-0">
    
    <div class="container">
      <a href="index.php" class="brand-logo brand-text">Movie Database</a>
    <?php if((isset($_SESSION["admin"]) && $_SESSION["admin"] == true) || (isset($_SESSION["username"]) && $_SESSION["username"] == true)): ?>

          <ul id="nav-mobile" class="right hide-on-small-and-down">
            <ul id = "dropdown-1" class = "dropdown-content">
             <li><a href = "search-by-title.php">Title</a></li>
                          <li><a href = "search-by-genre.php">Genre</a></li>
             <li><a href = "search-by-country.php">Country</a></li>
             <li><a href = "search-by-actor.php">Actor</a></li>
             <li><a href = "search-by-year.php">Year</a></li>
          </ul>
          <ul id="nav-mobile" class="right hide-on-small-and-down">
            <ul id = "dropdown-2" class = "dropdown-content">
             <li><a href = "reset-password.php">Reset Password</a></li>
              <li><a href = "logout.php">Sign Out</a></li>
          </ul>
          
          

          <a class = "btn dropdown-button" href = "#" data-activates = "dropdown-2"><?php echo htmlspecialchars($_SESSION["username"]); ?>'s Account:
             <i class = "mdi-navigation-arrow-drop-down right"></i></a>

          <a class = "btn dropdown-button" href = "#" data-activates = "dropdown-1">Search by:
             <i class = "mdi-navigation-arrow-drop-down right"></i></a>

             <?php if(isset($_SESSION["admin"]) && $_SESSION["admin"] == true): ?>
            <a class = "btn dropdown-button" href = "#" data-activates = "dropdown-3">Add by:
                        <i class = "mdi-navigation-arrow-drop-down right"></i></a>
          <ul id = "dropdown-3" class = "dropdown-content">
             <li><a href = "add-movie.php">Movie</a></li>
          </ul>
        <?php endif; ?>
  <?php endif; ?>
    </div>

  </nav>
  