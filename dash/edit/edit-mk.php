<?php

$process = new dashbProcess($query);
$array = $process->fetch($get_id, 7);

if($array):

    $r_rel_id = $array[0][0];
    $r_kategori = $array[0][1];
    $mk_info = cta($array[0][2]);
    $mk_c_xy = cta($array[0][3]);
    $mk_c_info = cta($array[0][4]);
?>
<head>
<script src="../lib/jquery-3.2.1.min.js"></script>
<script>
</script>
</head>
<style>input[type='text'] { width: 50px; }</style>
<form action="" method="POST" id="edit">
    <b>ID =</b> <?php echo $r_rel_id; ?><br>
    <b>Kategori =</b> <?php echo $r_kategori; ?><br><br>
    <b>#########################################################################################<br></b>
    <b>##________CURVA MONITORING KEGIATAN_____________________________________________##<br></b>
    <b>#########################################################################################<br><br></b>

    <b>Rencana Bulan Ini = </b> <input type="text" name="mk_info[]" value="<?php echo $mk_info[0]; ?>">%<br>
    <b>Realisasi Bulan Ini=</b> <input type="text" name="mk_info[]" value="<?php echo $mk_info[1]; ?>">%<br>
    <b>Deviasi =</b> <input type="text" name="mk_info[]" value="<?php echo $mk_info[2]; ?>">%<br>
    <b>________________________________________________________________________________________<br><br></b>
    <b>Anggap ini Curva =</b><br><br>
<input type="submit" value="SUBMIT" name="submit"><br><br>
</form>
<?php
else:
    echo "NO DATA";
endif;

?>