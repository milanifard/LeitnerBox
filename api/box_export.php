<?php

include_once 'box.php';
include_once 'section.php';
include_once 'card.php';


function export_box($box_id , $conn  , $person_id){
    $date_str = date("Y_m_d_G_i");

    $section = new Section($conn);
    $box_sections = $section->readByBoxId(100, $box_id);
    $fp = fopen('../user_files/boxes/'.$person_id.'/section_'.$box_id.'_'.$date_str.'_.json', 'w');
    fwrite($fp, json_encode($box_sections));
    fclose($fp);
    foreach ($box_sections as $current_section) {
        $card = new Card($conn);
        $section_cards = $card->readBySectionId(100,  $current_section['id']);
        $fp = fopen('../user_files/boxes/'.$person_id.'/cards_'.$box_id.'_'.$date_str.'_.json', 'w');
        fwrite($fp, json_encode($section_cards));
        fclose($fp);

        foreach ($section_cards as $current_card) {
            var_dump($current_card);
        }
    }

}

?>
