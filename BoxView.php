<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جعبه لایتنر</title>
    <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.0.0/css/bootstrap.min.css" integrity="sha384-P4uhUIGk/q1gaD/NdgkBIl3a6QywJjlsFJFk7SPRdruoGddvRVSwv5qFnvZ73cpz" crossorigin="anonymous">
    <base href="../../LeitnerBox/" target="_blank">
    <link rel="stylesheet" href="html/leitner.css">
    <?php
    include_once 'header.inc.php';

    ?>

</head>

<body>
    <?php

    error_reporting(E_ALL);
    define('BASE_PATH', '../../LeitnerBox/');
    ini_set('display_errors', '1');
    ini_set("file_uploads","On");
    ini_set('post_max_size', '4M');
    ini_set('upload_max_filesize', '8M');
    
    include_once 'database.php';
    include_once 'box.php';
    include_once 'section.php';
    include_once 'card.php';


    if (isset($_SESSION['UserID'])) { //if login in session is not set
        // TODO : check if user owns box
        if (isset($_REQUEST['box_id'])) {
            $db =  new Database();
            $conn = $db->getConnection();

            $box =  new Box($conn);
            $box->readById($_REQUEST['box_id']);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                echo "\n POST:";
                var_dump($_POST);
                
                if (isset($_REQUEST["create_section"])) {
                    $section =  new Section($conn);
                    $section->box_id = $box->id;
                    $section->create();
                    echo '\ncreated section : ';
                    var_dump($section);
                } else if (isset($_REQUEST['remove_section_id'])) //if login in session is not set
                {
                    $section =  new Section($conn);
                    $section->id = $_REQUEST['remove_section_id'];
                    $section->deleteByID();
                    // echo "<script>window.location.href = 'LeitnerBox.php';</script>";
                } else if (isset($_REQUEST["create_card"])) {


                    echo "\n FILEs:";
                    var_dump($_FILES);
                    $card =  new card($conn);
                    $card->section_id = $box->default_section;
                    $card->front_text = $_REQUEST["front_text"];
                    $card->back_text = $_REQUEST["back_text"];

                    $date_str = date("d-m-Y_G_i");
                    if (isset($_FILES["front_image"]) && $_FILES['front_image']['name'] != '') {
                        $file_name = $_SESSION['UserID'] . "_frontimg_".$date_str."_".$_FILES['front_image']['name'];
                        move_uploaded_file($_FILES['front_image']['tmp_name'] , BASE_PATH."user_files/images/".$file_name);
                        $card->front_image_name = $file_name;
                    }
                    if (isset($_FILES["back_image"])  && $_FILES['back_image']['name'] != '') {
                        $file_name = $_SESSION['UserID'] . "_backimg_".$date_str."_".$_FILES['back_image']['name'];
                        move_uploaded_file($_FILES['back_image']['tmp_name'] , BASE_PATH."user_files/images/".$file_name);
                        $card->back_image_name = $file_name;
                    }

                    if (isset($_FILES["front_audio"]) && $_FILES['front_audio']['name'] != '') {
                        $file_name = $_SESSION['UserID'] . "_frontaudio_".$date_str."_".$_FILES['front_audio']['name'];
                        echo "\n front audio : ";
                        var_dump($file_name);
                        move_uploaded_file($_FILES['front_audio']['tmp_name'] , BASE_PATH."user_files/audios/".$file_name);
                        $card->front_audio_name = $file_name;
                    }
                    if (isset($_FILES["back_audio"]) && $_FILES['back_audio']['name'] != '' ) {
                        $file_name = $_SESSION['UserID'] . "_backaudio_".$date_str."_".$_FILES['back_audio']['name'];
                        echo "\n back audio : ";
                        var_dump($file_name);
                        move_uploaded_file($_FILES['back_audio']['tmp_name'] , BASE_PATH."user_files/audios/".$file_name);
                        $card->back_audio_name = $file_name;
                    }
                    $card->create();
                }else if(isset($_REQUEST["answer_card"])){ 

                    $card =  new card($conn);
                    $card->readById($_REQUEST["card_id"]);
                    if($card->back_text == $_REQUEST["answer"]){
                        echo "your answer is true";
                        echo "<script>open_card_modal(
                            event,
                            1,
                            '',
                            'user_files/images/". $card->front_image_name ."',
                            'user_files/audios/". $card->front_audio_name ."',
                            '". $card->back_text ."',
                            'user_files/images/". $card->front_image_name ."',
                            'user_files/audios/". $card->back_image_name ."',
                        ) </script>";
                    }else{
                        echo "your answer is NOT true";

                    }
                }


                die();
            } else {


                $section = new Section($conn);
                // var_dump($box);
                $box_sections = $section->readByBoxId(100, $_REQUEST['box_id']);


                foreach ($box_sections as $current_section) {
                    var_dump($current_section);
                }
            }
        } else {
            die();
        }
    }

    ?>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark ">
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

    <main role="main" class="container-fluid">
        <div class="modal-wrapper">

            <div class="create-card-modal" id="create-card-modal">
                <div class="close-modal-btn" onclick="close_all_modals(event)"></div>
                <h3>ساختن یک کارت جدید</h3>
                <form action=<?php echo ($_SERVER['REQUEST_URI']); ?> method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="create-card-action" name="create_card" value="create_card">

                    <input required class="text-inp form-control" type="text" name="front_text" id="front_text" placeholder="متن جلوی کارت">
                    <div class="file-input-wrapper">
                        <input type="file" id="front_image" name="front_image" accept="image/*">
                        <span>آپلود عکس جلوی کارت</span>
                    </div>
                    <div class="file-input-wrapper">
                        <input type="file" id="front_audio" name="front_audio" accept="audio/*">
                        <span>آپلود صدای جلوی کارت</span>
                    </div>
                    <input required class="text-inp form-control" type="text" name="back_text" id="back_text" placeholder="متن پشت کارت">
                    <div class="file-input-wrapper">
                        <input type="file" id="back_image" name="back_image" accept="image/*">
                        <span>آپلود عکس پشت کارت</span>
                    </div>
                    <div class="file-input-wrapper">
                        <input type="file" id="back_audio" name="back_audio" accept="audio/*">
                        <span>آپلود صدای پشت کارت</span>
                    </div>
                    <button class="ltn-button">ساختن</button>
                </form>
            </div>

            <div class="card-modal create-card-modal" id="card-view">

            </div>
        </div>

        <div class="container-rsp">
            <div class="top-header">
                <h1 class="leitner-header">
                    <span class="little-text">
                    </span><?php echo $box->title ?>
                </h1>
            </div>
            <hr>
            <div class="create-section">
                <form action=<?php echo ($_SERVER['REQUEST_URI']); ?> method="POST">
                    <input type="hidden" id="create-section-action" name="create_section" value="create_section">
                    <button class="ltn-button">
                        اضافه کردن بخش جدید به جعبه لایتنر
                    </button>
                </form>
            </div>
            <hr>
            <div class="create-cart">
                <a class="ltn-button" onclick="open_create_card_modal(event)">
                    اضافه کردن کارت جدید به جعبه لایتنر
                </a>
            </div>
            <hr>
            <div class="leitner-game">

                <?php
                $k = 1;
                foreach ($box_sections as $current_section) {
                    // var_dump($current_section);
                    echo '<div class="leitner-section">';
                    echo '<div class="section-header">';
                    echo '<h4 >بخش شماره ' . $k . '</h4>';
                    echo "<form action=\"" . $_SERVER['REQUEST_URI'] . "\" method=\"post\" id=\"form" . $current_section['id'] . "\"><input type=\"hidden\" name=\"remove_section_id\" value=\"" . $current_section['id'] . "\" /><button type=\"submit\" form=\"form" . $current_section['id'] . "\" onlick=\"removeBox()\"  class=\"btn btn-danger\">حذف</button></form>";
                    echo '</div>';
                    echo '<hr>';
                    echo '<div class="card-wrapper">';
                   

                    $card = new Card($conn);
                    $section_cards = $card->readBySectionId(100,  $current_section['id']);


                    foreach ($section_cards as $current_card) {
                        echo '<div class="card">';
                        echo '<div class="top-pic">';
                        echo '<img onerror="this.onerror=null; this.src=\'placeholder.png\'" src="user_files/images/'.$current_card['front_image_name'] . '" alt="alternative">';
                        echo '</div>';
                        echo '<div class="middle-audio">';
                        echo '</div>';
                        echo '<div class="bottom-text">'. $current_card['front_text'] .'</div>';

                        echo "<a  class=\"open-card-btn\"
                        onclick=\"open_card_modal(
                            event,
                            1,
                            '". $current_card['front_text'] ."',
                            'user_files/images/". $current_card['front_image_name'] ."',
                            'user_files/audios/". $current_card['front_audio_name'] ."',
                            '". $current_card['back_text'] ."',
                            'user_files/images/". $current_card['back_image_name'] ."',
                            'user_files/audios/". $current_card['back_audio_name'] ."',
                        )\">
                            باز کردن کارت
                        </a>";

                        echo '</div>';
                    }

                    
                    echo '</div>';
                    echo '</div>';
                    $k++;
                }



                ?>

            </div>
        </div>


        <script src="html/leitner.js"></script>
    </main>
</body>