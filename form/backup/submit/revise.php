<?php
$process = new inboxProcess($query);
$array = $process->fetch($s_id, true);

$komentar = str_getcsv($array[0][3]);
$pengirim = str_getcsv($array[0][2]);
$waktu = str_getcsv($array[0][9]);
$status = str_getcsv($array[0][13]);

$reply = $process->fetchReply($array[0][2]);

$limit = count($pengirim);
for($x=0; $x<$limit; $x++):
    $replyList[$x][0] = $pengirim[$x];
    $replyList[$x][1] = $komentar[$x];
    $replyList[$x][2] = $waktu[$x];
    $replyList[$x][3] = $status[$x];

    $limit2 = count($reply);
    for($y=0; $y<$limit2; $y++):
        if($pengirim[$x] == $reply[$y][0]){
            $replyList[$x][4] = $reply[$y][1];
        }
    endfor;

    if($status[$x] == 1){
        $replyList[$x][5] = "DITERIMA";
    }
    elseif($status[$x] == 2){
        $replyList[$x][5] = "DITOLAK";
    }

endfor;
$replyList = array_reverse($replyList);

?>
<form action="" method="POST" id="edit">
<table border=1>
    <tr>
        <td>JUDUL</td>
        <td><input type="text" name="title" value="<?php echo $array[0][4]; ?>"></td>
    </tr>
    <tr>
        <td>ISI</td>
        <td><textarea  name="main"><?php echo $array[0][5]; ?></textarea></td>
    </tr>
    <tr>
        <td>ATTACHMENT</td>
        <td><textarea  name="attach"><?php echo $array[0][6]; ?></textarea></td>
    </tr>
    <tr>
        <td colspan="2"><input type="submit" name="submit" value="SUBMIT"></td>
    </tr>
</table>
</form>
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
            <td><?php echo $replyList[$x][5]; ?></td>
            <td><?php echo $replyList[$x][1]; ?></td>
            <td><?php echo $replyList[$x][4]; ?></td>
            <td><?php echo $replyList[$x][2]; ?></td>
        </tr>
    <?php
    endfor;
    ?>
</table>
