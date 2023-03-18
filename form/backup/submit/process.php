<?php

if(isset($_POST['title'], $_POST['main'], $_POST['attach']) && $s_level == 2):

    $s_rel_id_ex = explode(",",$s_rel_id);
    $q_rel_id = $s_rel_id_ex[0];
    
    $q_title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
    $q_main = filter_input(INPUT_POST, 'main', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
    $q_attach = filter_input(INPUT_POST, 'attach', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);

    $process = new inboxProcess($query);

    if($process->submit($s_id, $q_rel_id, $q_title, $q_main, $q_attach)) {
        require $dir_html."main-progress.html";
    }
    else {
        //
    }
    
endif;

