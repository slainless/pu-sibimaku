<?php

$process = new dashbProcess($query);
$array = $process->fetch($get_id, 1);

if($array):

    $r_rel_id = $array[0][0];
    $r_kategori = $array[0][1];
    $dp_info = cta($array[0][2]);
    $dp_kontrak = cta($array[0][3], true);
    $dp_pelaksana = cta($array[0][4]);
    $dp_surat = cta($array[0][5]);
    $r_n_kontrak = $array[0][6];
    $r_n_pagu = $array[0][7];
?>
<form action="" method="POST" id="edit">
    <b>ID =</b> <?php echo $r_rel_id; ?><br>
    <b>Kategori =</b> <?php echo $r_kategori; ?><br><br>
    <b>#########################################################################################<br></b>
    <b>##________DATA PROYEK_______________________________________________________________##<br></b>
    <b>#########################################################################################<br><br></b>
    <b>Kegiatan =</b> <input type="text" name="dp_info_0" value="<?php echo $dp_info[0]; ?>"></input><br>
    <b>Pekerjaan =</b> <input type="text" name="dp_info_1" value="<?php echo $dp_info[1]; ?>"></input><br>
    <b>Nomor Kontrak =</b> <input type="text" name="dp_kontrak_0" value="<?php echo $dp_kontrak[0][0]; ?>"></input><br>
    <b>Tanggal Kontrak =</b> <input type="text" name="dp_kontrak_1" value="<?php echo $dp_kontrak[0][1]; ?>"></input><br>
    <b>Nomor Add. Kontrak 01 =</b> <input type="text" name="dp_kontrak_2" value="<?php echo $dp_kontrak[1][0]; ?>"></input><br>
    <b>Tanggal Add. Kontrak 01 =</b> <input type="text" name="dp_kontrak_3" value="<?php echo $dp_kontrak[1][1]; ?>"></input><br><br>
    <b>Lokasi Kegiatan =</b> <input type="text" name="dp_info_2" value="<?php echo $dp_info[1]; ?>"></input><br>
    <b>________________________________________________________________________________________<br><br></b>
    <b>Pengguna Anggaran =</b> <input type="text" name="dp_pelaksana_0" value="<?php echo $dp_pelaksana[0]; ?>"></input><br>
    <b>Pejabat Pembuat Komitmen =</b> <input type="text" name="dp_pelaksana_1" value="<?php echo $dp_pelaksana[1]; ?>"></input><br>
    <b>Pejabat Pelaksana Teknis Kegiatan =</b> <input type="text" name="dp_pelaksana_2" value="<?php echo $dp_pelaksana[2]; ?>"></input><br>
    <b>Kontraktor Pelaksana =</b> <input type="text" name="dp_pelaksana_3" value="<?php echo $dp_pelaksana[3]; ?>"></input><br>
    <b>Konsultasi Supervisi =</b> <input type="text" name="dp_pelaksana_4" value="<?php echo $dp_pelaksana[4]; ?>"></input><br>
    <b>________________________________________________________________________________________<br><br></b>
    <b>Surat Penyerahan Lapangan =</b> <input type="text" name="dp_surat_0" value="<?php echo $dp_surat[0]; ?>"></input><br>
    <b>Surat Perintah Mulai Kerja =</b> <input type="text" name="dp_surat_1" value="<?php echo $dp_surat[1]; ?>"></input><br>
    <b>Masa Pelaksanaan =</b> <input type="text" name="dp_surat_2" value="<?php echo $dp_surat[2]; ?>"></input><br>
    <b>Akhir Masa Pelaksanaan =</b> <input type="text" name="dp_surat_3" value="<?php echo $dp_surat[3]; ?>"></input><br>
    <b>Masa Pemeliharaan =</b> <input type="text" name="dp_surat_4" value="<?php echo $dp_surat[4]; ?>"></input><br>
    <b>________________________________________________________________________________________<br><br></b>
    <b>Nilai Kontrak =</b> Rp.<input type="text" name="n_kontrak" value="<?php echo $r_n_kontrak; ?>"></input>,00<br>
    <b>Nilai Kontrak =</b> Rp.<input type="text" name="n_pagu" value="<?php echo $r_n_pagu; ?>"></input>,00<br>
    <br><input type="submit" value="Submit" name="submit">
</form>
<?php
else:
    echo "NO DATA";
endif;

?>