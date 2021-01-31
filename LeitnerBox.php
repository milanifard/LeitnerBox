<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جعبه لایتنر</title>
    <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.0.0/css/bootstrap.min.css" integrity="sha384-P4uhUIGk/q1gaD/NdgkBIl3a6QywJjlsFJFk7SPRdruoGddvRVSwv5qFnvZ73cpz" crossorigin="anonymous">

    <?php

    include_once 'header.inc.php';

    ?>
</head>

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
        <nav class="navbar navbar-expand-md navbar-dark bg-dark " >
            <a class="navbar-brand" href="#">جعبه لایتنر</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExampleDefault" >
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

        <form action="/LeitnerBox/api/box_list.php" method="post" id="fbox" name="fbox" method="post">
            <div class="form-group">
                <label class="mt-4" for="box-name">نام جعبه</label>
                <input type="text" class="form-control" id="box-name" name="box-name">
            </div>
            <div class="form-group">
                <label for="box-description">توضیحات جعبه</label>
                <textarea class="form-control" id="box-description" name="box-description" rows="3"></textarea>
            </div>
            <?php
            echo "<input type=\"hidden\" id=\"ownerId\" name=\"ownerId\" value=\"" . $_SESSION['PersonID'] . "\">";
            ?>

        </form>
        <button type="button" name="create" onclick="submitForm()" class="btn btn-primary btn-lg mt-1 mb-4">افزودن جعبه جدید</button>
        <table class="table" id="boxes-table">
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

            if (isset($_SESSION['UserID'])) { //if login in session is not set


                $box =  new Box($conn);
                $user_boxes = $box->readByOwnerId(100, $_SESSION['PersonID']);
                $i = 1;
                // var_dump($user_boxes);
                foreach ($user_boxes as $box_item) {
                    echo " <tr><th scope=\"row\">" . $box_item['id'] . "</th>
            <td>" . $box_item['title'] . "</td>
            <td>" . $box_item['description_text'] . "</td>
            <td><form action=\"/LeitnerBox/api/box_list.php\" method=\"post\" id=\"form" . $box_item['id'] . "\"><input type=\"hidden\" name=\"remove_id\" value=\"" . $box_item['id'] . "\" /><button type=\"submit\" form=\"form" . $box_item['id'] . "\" onlick=\"removeBox()\"  class=\"btn btn-danger\">حذف</button></form></td> </tr>";
                    $i++;
                }

                // var_dump($_REQUEST);

            }
            ?>
            </tbody>
        </table>

        </ul>
    </main><!-- /.container -->

    <script src="../../LeitnerBox/html/boxlist.js"></script>
</body>

</html>