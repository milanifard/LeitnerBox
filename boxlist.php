<?php
// var_dump($_SESSION);
error_reporting(E_ALL);
ini_set('display_errors', '1');

include_once 'config/database.php';
include_once 'model/box.php';
?>
<body cz-shortcut-listen="true">

<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
  <a class="navbar-brand" href="#">جعبه لایتنر</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarsExampleDefault">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">خانه<span class="sr-only">(current)</span></a>
      </li>

      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>

<main role="main" class="container">

<button type="button" class="btn btn-primary btn-lg">افزودن جعبه جدید</button>
<ul class="list-group list-group-flush">
    <?php


    if(isset($_SESSION['UserID'])){ //if login in session is not set
        
        $db =  new Database();
        $conn = $db->getConnection();

        $box =  new Box($conn);
        $user_boxes = $box->readByOwnerId(10 ,$_SESSION['UserID'] );
        var_dump($user_boxes);

    }
    // <li class="list-group-item">Cras justo odio</li>
    // <li class="list-group-item">Dapibus ac facilisis in</li>
    // <li class="list-group-item">Morbi leo risus</li>
    // <li class="list-group-item">Porta ac consectetur ac</li>
    // <li class="list-group-item">Vestibulum at eros</li>
    
    ?>
</ul>
</main><!-- /.container -->



</body>