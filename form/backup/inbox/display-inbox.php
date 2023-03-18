<?php

$process = new inboxProcess($query);
$array = $process->fetch($s_id);

if($array):
$z = 1;
?>
    <table border=1>
    <tr>
        <td colspan=4><b>INBOX</b></td>
    </tr>
    <tr>
        <td>NO</td>
        <td>JUDUL</td>
        <td>PENGIRIM</td>
        <td>WAKTU</td>
    </tr>

    <?php
    $limit = count($array);
    for($x=0; $x<$limit; $x++):
        if($array[$x][0] == $s_id):
        ?>
            <tr>
                <td><?php echo $z; ?></td>
                <td><a href="<?php echo "?id=".$array[$x][1]."&in=0"; ?>"><?php echo $array[$x][4]; ?></a></td>
                <td><?php echo $array[$x][10]; ?></td>
                <td><?php echo $array[$x][8]; ?></td>
            </tr>
        <?php
        $z++;
        endif;
    endfor;
    ?>
    <tr>
        <td colspan=4><b>SENT</b></td>
    </tr>
    <tr>
        <td>NO</td>
        <td>JUDUL</td>
        <td>PENGIRIM</td>
        <td>WAKTU</td>
    </tr>
    <?php
    $limit = count($array);
    for($x=0; $x<$limit; $x++):
        if($array[$x][11] == $s_id):
        ?>
            <tr>
                <td><?php echo $z; ?></td>
                <td><?php echo $array[$x][4]; ?></td>
                <td><?php echo $array[$x][10]; ?></td>
                <td><?php echo $array[$x][8]; ?></td>
            </tr>
        <?php
        $z++;
        endif;
    endfor;
    ?>
    </table>

<?php
else:
    echo "NO INBOX";
endif;