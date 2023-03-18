<?php

$process = new dashbProcess($query);
$array = $process->fetch($get_id, 1);
$arrayMember = $process->fetchMember(2);

if($array):

    $r_rel_id = $array[0][0];
    $r_kategori = $array[0][1];
    $dp_info = cta($array[0][2]);
    $dp_kontrak = cta($array[0][3], true);
    $dp_pelaksana = cta($array[0][4]);
    $dp_surat = cta($array[0][5]);
    $r_id = $array[0][20];
    $r_nama = $array[0][21];

?>
<form action="" method="POST" id="edit">

    <b>#########################################################################################<br></b>
    <b>##________DATA KONTRAKTOR_______________________________________________________________##<br></b>
    <b>#########################################################################################<br><br></b>
    <b>ID =</b> <?php echo $r_rel_id; ?><br>
    <b>Kategori =</b> <?php echo $r_kategori; ?><br>

    <b>Kontraktor =</b> 
    <select name="kontraktor">
        <option value=0>Pilih Kontraktor</option> 
        <?php
        $limit = count($arrayMember);
        $selected = "";
        for($x=0;$x<$limit;$x++){
            if($arrayMember[$x][0] == $r_rel_id){
                $selected = "selected";
            }
            echo "<option value=".$arrayMember[$x][0]." ".$selected.">".$arrayMember[$x][1]."</option>";
            $selected = "";
        }
        ?>
    </select>
    <br>

    <b>Kegiatan =</b><?php echo $dp_info[0]; ?><br>
    <b>Pekerjaan =</b><?php echo $dp_info[1]; ?><br>
    <br><input type="submit" value="Submit" name="submit">
</form>
<?php
else:
    echo "NO DATA";
endif;

?>