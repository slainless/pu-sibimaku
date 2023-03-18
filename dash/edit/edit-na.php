<?php

$process = new dashbProcess($query);
$array = $process->fetch($get_id, 8);

if($array):

    $r_rel_id = $array[0][0];
    $r_kategori = $array[0][1];
    $na_info = cta($array[0][2], true);
?>
<head>
<script src="../lib/jquery-3.2.1.min.js"></script>
<script>
</script>
</head>
<style>input[type='text'] { width: 200px; }</style>
<form action="" method="POST" id="edit">
    <b>ID =</b> <?php echo $r_rel_id; ?><br>
    <b>Kategori =</b> <?php echo $r_kategori; ?><br><br>
    <b>#########################################################################################<br></b>
    <b>##________ORANG PENTING____________________________________________________________##<br></b>
    <b>#########################################################################################<br><br></b>

    <?php
    $limit = count($na_info);
    for($x=0;$x<$limit;$x++){
        echo "
        <input type='text' name='na_nama[]' value='".$na_info[$x][1]."'><br>
        <b>".$na_info[$x][0]."</b><br>
        <input type='text' name='na_ket[]' value='".$na_info[$x][2]."'><br><br>";
    }
    ?>
<input type="submit" value="SUBMIT" name="submit"><br><br>
</form>
<?php
else:
    echo "NO DATA";
endif;

?>