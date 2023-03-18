<?php

$process = new dashbProcess($query);
$array = $process->fetch($get_id, 4);

if($array):

    $r_rel_id = $array[0][0];
    $r_kategori = $array[0][1];
    $rp_info_prg = cta($array[0][2]);
    $rp_info_waktu = cta($array[0][3]);
    $rp_info_prg_c = cta($array[0][4]);
    $rp_info_waktu_c = cta($array[0][5]);

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
    <b>##________RINGKASAN PROGRESS______________________________________________________##<br></b>
    <b>#########################################################################################<br><br></b>

    <b>Kumulatif Rencana = </b> <input type="text" name="rp_info_prg[]" value="<?php echo $rp_info_prg[0]; ?>"><br>
    <b>Kumulatif Realisasi =</b> <input type="text" name="rp_info_prg[]" value="<?php echo $rp_info_prg[1]; ?>"><br>
    <b>Deviasi =</b> <input type="text" name="rp_info_prg[]" value="<?php echo $rp_info_prg[2]; ?>"><br><br>

    <b>Progress Bulan Ini =</b> <input type="text" name="rp_info_prg[]" value="<?php echo $rp_info_prg[3]; ?>"><br><br>

    <b>Anggap ini Chart =</b> Realisasi - <b><input type="text" name="rp_info_prg_c[]" value="<?php echo $rp_info_prg_c[0]; ?>">%</b>, Belum Terealisasi - <b><input type="text" name="rp_info_prg_c[]" value="<?php echo $rp_info_prg_c[1]; ?>">%</b><br>
    <b>________________________________________________________________________________________<br><br></b>

    <b>Masa Pelaksanaan = </b> <input type="text" name="rp_info_waktu[]" value="<?php echo $rp_info_waktu[0]; ?>"> Hari<br><br>
    <b>Waktu Terpakai =</b> <input type="text" name="rp_info_waktu[]" value="<?php echo $rp_info_waktu[1]; ?>"> Hari<br>
    <b>Sisa Waktu =</b> <input type="text" name="rp_info_waktu[]" value="<?php echo $rp_info_waktu[2]; ?>"> Hari<br><br>

    <b>Anggap ini Chart =</b> Waktu Terpakai - <b><input type="text" name="rp_info_waktu_c[]" value="<?php echo $rp_info_waktu_c[0]; ?>">%</b>, Sisa Waktu - <b><input type="text" name="rp_info_waktu_c[]" value="<?php echo $rp_info_waktu_c[1]; ?>">%</b><br><br>
<input type="submit" value="SUBMIT" name="submit"><br><br>
</form>
<?php
else:
    echo "NO DATA";
endif;

?>