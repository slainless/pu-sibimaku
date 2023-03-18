<?php

$process = new dashbProcess($query);
$array = $process->fetch($get_id, 2);

if($array):

    $r_rel_id = $array[0][0];
    $r_kategori = $array[0][1];
    $pf_info = cta($array[0][2], true);
?>

<form action="" method="POST" id="edit">
    <b>ID =</b> <?php echo $r_rel_id; ?><br>
    <b>Kategori =</b> <?php echo $r_kategori; ?><br><br>
    <b>#########################################################################################<br></b>
    <b>##________PROGRESS FISIK____________________________________________________________##<br></b>
    <b>#########################################################################################<br><br></b>

    <table border=1 class="main">
    <thead>
        <tr>
            <td colspan=7>PROGRESS FISIK</td>
        </tr>
        <tr>
            <td rowspan=2>DIV.</td>
            <td rowspan=2>URAIAN</td>
            <td colspan=4>BOBOT (%)</td>
            <td rowspan=2>ACTION</td>
        </tr>
        <tr>
            <td>Kontrak</td>
            <td>Bulan Lalu</td>
            <td>Bulan Ini</td>
            <td>Sd/ Bulan Ini</td>
        </tr>
    </thead>
    <tbody>
<?php

    $limit = count($pf_info);
    for($x=0;$x<$limit;$x++){
        echo "
        <tr>
            <td><input type='text' name='pf_div[]' value='".$pf_info[$x][0]."'></td>
            <td><input type='text' name='pf_title[]' value=".$pf_info[$x][1]."></td>
            <td><input type='text' name='pf_kontrak[]' value=".$pf_info[$x][2]."></td>
            <td><input type='text' name='pf_bln_1[]' value=".$pf_info[$x][3]."></td>
            <td><input type='text' name='pf_bln_2[]' value=".$pf_info[$x][4]."></td>
            <td><input type='text' name='pf_bln_3[]' value=".$pf_info[$x][5]."></td>
            <td><button class='delete' type='button'>DELETE</button></td>
        </tr>
        ";
    }
?>
        </tbody>
    </table><button class="add" type="button">ADD ROW</button><input type="submit" value="SUBMIT" name="submit"><br><br>
</form>
<?php
else:
    echo "NO DATA";
endif;

?>