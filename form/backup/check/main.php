<?php

$dir_html = $dir_html.'form-check/';
require $dir_html."header.html";

$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$get_in = filter_input(INPUT_GET, 'in', FILTER_SANITIZE_NUMBER_INT);

require_once 'function.php';

$process = new inboxProcess($query);
$array = $process->fetch($get_id, true);

$komentar = str_getcsv($array[0][3]);
$pengirim = str_getcsv($array[0][2]);
$waktu = str_getcsv($array[0][9]);
$status = str_getcsv($array[0][13]);

$limit = count($pengirim);
for($x=0; $x<$limit; $x++):
    $replyList[$x][0] = $pengirim[$x];
    $replyList[$x][1] = $komentar[$x];
    $replyList[$x][2] = $waktu[$x];
    $replyList[$x][3] = $status[$x];
endfor;
$replyList = array_reverse($replyList);

$reply = $process->fetchReply($array[0][2]);

$limit = count($replyList);
for($x=0; $x<$limit; $x++):
    $limit2 = count($reply);
    for($y=0; $y<$limit2; $y++):
        if($replyList[$x][0] == $reply[$y][0]){
            $replyList[$x][4] = $reply[$y][1];
        }
    endfor;
endfor;
?>

<table border=1>
    <tr>
        <td>JUDUL</td>
        <td><?php echo $array[0][4]; ?></td>
    </tr>
    <tr>
        <td>PENGIRIM</td>
        <td><?php echo $array[0][10]; ?></td>
    </tr>
    <tr>
        <td>WAKTU</td>
        <td><?php echo $array[0][8]; ?></td>
    </tr>
    <tr>
        <td>ISI</td>
        <td><?php echo $array[0][5]; ?></td>
    </tr>
    <tr>
        <td>ATTACHMENT</td>
        <td><?php echo $array[0][6]; ?></td>
    </tr>
</table>
<br><br>
<table border=1>
    <tr>
        <td colspan=4><b>REPLY</b></td>
    </tr>
    <tr>
        <td>STATUS</td>
        <td>KOMENTAR</td>
        <td>PENGIRIM</td>
        <td>WAKTU</td>
    </tr>
    <?php
    $limit = count($replyList);
    for($x=0; $x<$limit; $x++):
    ?>
        <tr>
            <td><?php echo $replyList[$x][3]; ?></td>
            <td><?php echo $replyList[$x][1]; ?></td>
            <td><?php echo $replyList[$x][4]; ?></td>
            <td><?php echo $replyList[$x][2]; ?></td>
        </tr>
    <?php
    endfor;
    ?>
</table>
<br><br>
<form action="" method="POST" id="edit">
<table border=1>
    <tr>
        <td>STATUS</td>
        <td>
            <select name="status">
                <option>Pilih Status</option>
                <option value=1>Diterima</option>
                <option value=2>Ditolak</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>KOMENTAR</td>
        <td><textarea name="komentar"></textarea></td>
    </tr>
    <tr>
        <td colspan="2"><input type="submit" name="submit" value="SUBMIT"></td>
    </tr>
</table>
</form>


<?php
require $dir_html."footer.html";