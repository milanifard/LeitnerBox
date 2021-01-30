<?php
// var_dump($_SESSION);
error_reporting(E_ALL);
ini_set('display_errors', '1');

include_once 'config/database.php';
include_once 'model/box.php';
?>
<body cz-shortcut-listen="true">
<style>
  tr:hover {
          background-color: #ffff99;
        }
</style>
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
<form id="fbox" name="fbox" method="post">
  <div class="form-group">
    <label for="box-name" >نام جعبه</label>
    <input type="text" class="form-control" id="box-name" name="box-name">
  </div>
  <div class="form-group">
    <label for="box-description">توضیحات جعبه</label>
    <textarea class="form-control" id="box-description" name="box-description" rows="3"></textarea>
  </div>
</form>
<button type="button" name="create" onclick="submitForm()" class="btn btn-primary btn-lg">افزودن جعبه جدید</button>
<table class="table" id="boxes-table" >
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">نام</th>
      <th scope="col">توضیحات</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <?php
    $db =  new Database();
    $conn = $db->getConnection();

    if(isset($_SESSION['UserID'])){ //if login in session is not set
        


        $box =  new Box($conn);
        $user_boxes = $box->readByOwnerId(10 ,$_SESSION['PersonID'] );
        $i=1;
        foreach ($user_boxes as $box_item) {
            echo " <tr><th scope=\"row\">".$box_item['id']."</th>
            <td>".$box_item['title']."</td>
            <td>".$box_item['description_text']."</td>
            <td><button type=\"button\" class=\"btn btn-danger\">حذف</button></td> </tr>";
            $i++;
        }

        var_dump($_REQUEST);

        if(isset($_REQUEST["box-name"])){

            $box =  new Box($conn);
            $box->ownerId = $_SESSION['PersonID'];
            
            $box->title = $_REQUEST["box-name"];
            if(isset($_REQUEST["box-description"]))
                $box->description_text = $_REQUEST["box-description"];
            $box->create();


        }
    }
    ?>
  </tbody>
</table>

</ul>
</main><!-- /.container -->


<script>
function addRowHandlers() {

  var table = document.getElementById("boxes-table");
  var rows = table.getElementsByTagName("tr");
  for (i = 0; i < rows.length; i++) {
    var currentRow = rows[i];
    var createClickHandler = function(row) {
      return function() {
        var cell = row.getElementsByTagName("th")[0];
        var id = cell.innerHTML;
        window.location.href = "./box_view.php?box_id="+id
        // alert("id:" + id);
        
      };
    };
    console.log(" i is "+i);
    if(i != (rows.length-1))
      currentRow.onclick = createClickHandler(currentRow);
  }
}


function submitForm()
{   
    console.log("hello con");
    document.fbox.submit();

}
addRowHandlers();
</script>
</body>

