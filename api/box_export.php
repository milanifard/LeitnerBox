<?php

include_once 'box.php';
include_once 'section.php';
include_once 'card.php';



function export_box($box_id , $conn ){

    $section = new Section($conn);
    $box_sections = $section->readByBoxId(100, $box_id);
    foreach ($box_sections as $current_section) {
        $card = new Card($conn);
        $section_cards = $card->readBySectionId(100,  $current_section['id']);
        foreach ($section_cards as $current_card) {
            var_dump($current_card);
        }
    }

}

?>
