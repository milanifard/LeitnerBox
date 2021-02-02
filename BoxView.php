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
    ini_set('display_errors', '1');


    include_once 'database.php';
    include_once 'box.php';
    include_once 'section.php';

    if (isset($_SESSION['UserID'])) { //if login in session is not set
        // TODO : check if user owns box

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        } else {

            if (isset($_REQUEST['box_id'])) {
                $db =  new Database();
                $conn = $db->getConnection();

                $box =  new Box($conn);
                $box->readById($_REQUEST['box_id']);


                $section = new Section($conn);
                // var_dump($box);
                $box_sections = $section->readByBoxId(100, $_REQUEST['box_id']);


                foreach ($box_sections as $current_section) {
                    var_dump($current_section);
                }
            } else {
                die();
            }
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
                <form action="" method="POST">
                    <input type="hidden" id="action" name="action" value="create_card">

                    <input required="required" class="text-inp" type="text" name="front_text" id="front_text" placeholder="متن جلوی کارت">
                    <div class="file-input-wrapper">
                        <input required="required" type="file" id="front_image" name="front_image" accept="image/*">
                        <span>آپلود عکس جلوی کارت</span>
                    </div>
                    <div class="file-input-wrapper">
                        <input required="required" type="file" id="front_audio" name="front_audio" accept="audio/*">
                        <span>آپلود صدای جلوی کارت</span>
                    </div>
                    <input class="text-inp" type="text" name="back_text" id=back_text" placeholder="متن پشت کارت">
                    <div class="file-input-wrapper">
                        <input required="required" type="file" id="back_image" name="back_image" accept="image/*">
                        <span>آپلود عکس جلوی کارت</span>
                    </div>
                    <div class="file-input-wrapper">
                        <input required="required" type="file" id="back_audio" name="back_audio" accept="audio/*">
                        <span>آپلود صدای جلوی کارت</span>
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
                <form action="" method="POST">
                    <input type="hidden" id="action" name="action" value="create_section">
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
                <div class="leitner-section">
                    <h4>بخش شماره یک</h4>
                    <hr>
                    <div class="card-wrapper">
                        <div class="card">
                            <div class="top-pic">
                                <img src="html/test_pic.jpeg" alt="alternative">
                            </div>
                            <div class="middle-audio">
                                <audio controls>
                                    <source src="html/example_audio.mp3">
                                    Your browser does not support the audio tag.
                                </audio>
                            </div>
                            <div class="bottom-text">
                                این متن جلوی کارت است
                            </div>
                            <a href="" class="open-card-btn" onclick="open_card_modal(
                            event,
                            1,
                            'متن جلوی کارت تستی',
                            'html/test_pic.jpeg',
                            'html/example_audio.mp3',
                            'متن عقب کارت تستی',
                            'html/test_pic.jpeg',
                            'html/example_audio.mp3',
                        )">
                                باز کردن کارت
                            </a>
                        </div>
                        <div class="card">
                        </div>
                        <div class="card">
                        </div>
                        <div class="card">
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <script src="html/leitner.js"></script>
    </main>
</body>