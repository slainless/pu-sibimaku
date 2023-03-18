<?php

if(isset($_POST['status'], $_POST['komentar']) && $s_level > 2):
    
    $q_status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT);
    $q_komentar = filter_input(INPUT_POST, 'komentar', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
    $q_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $process = new inboxProcess($query);

    if($process->update($s_rel_id, $s_id, $q_status, $q_komentar, $q_id, $s_level)) {
//
    }
    else {
        //
    }
    
endif;

