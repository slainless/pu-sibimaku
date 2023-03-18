<?php

$process = new dashbProcess($query);
$array = $process->fetch($get_id, 3);

if($array):

    $r_rel_id = $array[0][0];
    $r_kategori = $array[0][1];
    $mp_info = cta($array[0][2], true);
    $mp_limit = count($mp_info);

    $cal = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Des');

    for($x=0;$x<$mp_limit;$x++){
        $y = $mp_info[$x][0];

        $mp_info2[$y][0] = $mp_info[$x][0];
        $mp_info2[$y][1] = $mp_info[$x][1];
        $mp_info2[$y][2] = $mp_info[$x][2];
        $mp_info2[$y][3] = $mp_info[$x][3];

        $range[$x] = $mp_info[$x][0];
    }

?>
<head>
<script src="../lib/jquery-3.2.1.min.js"></script>
<script>
$(document).ready(function(){

    $(".add").click(function(){
        var m1 = $("#m1").val();
        var m2 = $("#m2").val();
        m2++;
        var m3 = m2 - m1;
        var ms2;
        
        alert(m1 + ',' + m2);
        if(m1 < m2){

            $('tbody tr').slice(m1, m2).css("display", "none");
            $("#m_head").attr("colspan", m3);
        }
    });

});
</script>
</head>
<style>input[type='text'] { width: 50px; }</style>
<form action="" method="POST" id="edit">
    <input type="hidden" name="startm" value="1">
    <input type="hidden" name="endm" value="12">
    <b>ID =</b> <?php echo $r_rel_id; ?><br>
    <b>Kategori =</b> <?php echo $r_kategori; ?><br><br>
    <b>#########################################################################################<br></b>
    <b>##________MONITORING PROGRESS____________________________________________________##<br></b>
    <b>#########################################################################################<br><br></b>

    <table border=1 id="main">
        <thead>
            <tr>
                <td>MONITORING PROGRESS</td>
                <td id="m_head" colspan="12">Tahun 2017</td>
            </tr>
            <tr>
                <td class="m_0">Bulan</td>
<?php

    for($x=1;$x<13;$x++){
        if(isset($mp_info2[$x][0]) && $mp_info2[$x][0] == $x){
            echo "<td class='m_".$x."'>$x</td>";
        }
        else {
            echo "<td class='m_".$x."'>$x</td>";            
        }
    } 
?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="m_0">Kumulatif Rencana (%)</td>
<?php
    for($x=1;$x<13;$x++){
        if(isset($mp_info2[$x][0]) && $mp_info2[$x][0] == $x){
            echo "<td class='m_".$x."'><input type='text' name='mp_kum_1[]' value='".$mp_info2[$x][1]."'></td>";
        }
        else {
            echo "<td class='m_".$x."'><input type='text' name='mp_kum_1[]' value=''></td>";            
        }
    } 
?>                
            </tr>
                <td class="m_0">Kumulatif Realisasi (%)</td>
<?php
    for($x=1;$x<13;$x++){
        if(isset($mp_info2[$x][0]) && $mp_info2[$x][0] == $x){
            echo "<td class='m_".$x."'><input type='text' name='mp_kum_2[]' value='".$mp_info2[$x][2]."'></td>";
        }
        else {
            echo "<td class='m_".$x."'><input type='text' name='mp_kum_2[]' value=''></td>";            
        }
    } 
?>                
            </tr>
                <td class="m_0">Deviasi (%)</td>
<?php
    for($x=1;$x<13;$x++){
        if(isset($mp_info2[$x][0]) && $mp_info2[$x][0] == $x){
            echo "<td class='m_".$x."'><input type='text' name='mp_kum_3[]' value='".$mp_info2[$x][3]."'></td>";
        }
        else {
            echo "<td class='m_".$x."'><input type='text' name='mp_kum_3[]' value=''></td>";            
        }
    } 
?>                
            </tr>
        </tbody>
    </table>
<input type="submit" value="SUBMIT" name="submit"><br><br>
</form>
<?php
else:
    echo "NO DATA";
endif;

?>