<?php

$process = new dashbProcess($query);
$array = $process->fetch($get_id, 5);

if($array):

    $r_rel_id = $array[0][0];
    $r_kategori = $array[0][1];
    $ke_info = cta($array[0][2]);
    $ke_c = cta($array[0][3]);
?>
<head>
<script src="../lib/jquery-3.2.1.min.js"></script>
<script>
</script>
</head>
<style>input[type='text'] { width: 100px; }</style>
<form action="" method="POST" id="edit">
    <b>ID =</b> <?php echo $r_rel_id; ?><br>
    <b>Kategori =</b> <?php echo $r_kategori; ?><br><br>
    <b>#########################################################################################<br></b>
    <b>##________KEUANGAN_________________________________________________________________##<br></b>
    <b>#########################################################################################<br><br></b>

    <b>Nilai Kontrak = </b> Rp.<input type="text" value="<?php echo $ke_info[0]; ?>" name="ke_info[]">,00<br>
    <b>Realisasi s/d bulan ini =</b> Rp.<input type="text" value="<?php echo $ke_info[1]; ?>" name="ke_info[]">,00<br>
    <b>Sisa Dana =</b> Rp.<input type="text" value="<?php echo $ke_info[2]; ?>" name="ke_info[]">,00<br><br>

    <b>Anggap ini Chart =</b> Realisasi - <b><input type="text" value="<?php echo $ke_c[0]; ?>" name="ke_c[]">%</b>, Sisa Dana - <b><input type="text" value="<?php echo $ke_c[1]; ?>" name="ke_c[]">%</b><br><br>
<input type="submit" value="SUBMIT" name="submit"><br><br>
</form>
<?php
else:
    echo "NO DATA";
endif;

?>