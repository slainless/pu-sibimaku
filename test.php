<?php
// $string = '/'1", "2", "3"/;/"2","3","4"/';

// $array = str_getcsv($string, ";", "/"); //parse the rows
// //var_dump($Data);

// $limit = count($array);
// for($x=0;$x<$limit;$x++){
// 	$array2[$x] = str_getcsv($array[$x]);
// }

// echo $array2[1][2];

// var_dump($array2);


//$_POST['hello'] = array('hey1','ho2','silver3');
// if(true && true && true && true){
// 	echo "TRUE";
// }
// else {
// 	echo "FALSE";
// }
// 
// 
/*
require 'master-config.php';
require $access_main;

$level = 2;
$level2 = 3;

class dbExec {

    private $query;

    function __construct($query){
        $this->query = $query;
    }

    function select($param){

        $stmtq = "SELECT";

        if(isset($param['join'])){

            switch ($param['join']) {
                case 'left':
                    $join = " LEFT JOIN ";
                    break;
                
                default:
                    # code...
                    break;
            }

            $y = count($param['field']);
            $z = ', ';
            for($x=0;$x<$y;$x++){
                if($x == $y - 1){
                    $z = '';
                }
                $stmtq .= " ".$param['field'][$x]['from'].".".$param['field'][$x]['name'].$z;
            }

            $stmtq .= " FROM ".$param['table'];

            $y = count($param['join_table']);
            for($x=0;$x<$y;$x++){
                $stmtq .=  " ".$join." ".$param['join_table'][$x]." ON ".$param['on'][$x][0]['from'].".".$param['on'][$x][0]['name']." = ".$param['on'][$x][1]['from'].".".$param['on'][$x][1]['name'];
            }

            if(isset($param['where'])){
                $stmtq .= " WHERE ".$param['where']['from'].".".$param['where']['name']." = ?";
            }


        }
        else { 

            $y = count($param['field']);
            $z = ', ';
            for($x=0;$x<$y;$x++){
                if($x == $y - 1){
                    $z = '';
                }
                $stmtq .= " ".$param['field'][$x]['name'].$z;
            }

            $stmtq .= " FROM ".$param['table'];

            if(isset($param['where'])){
                $stmtq .= " WHERE ".$param['where']." = ?";
            }

        }

        if ($stmt = $this->query->prepare($stmtq)){
            $stmt->bind_param($param['param']['type'], $param['param']['value']); 
            $stmt->execute();

            $y = count($param['field']);
            for($x=0;$x<$y;$x++){
                ${$param['field'][$x]['result']} = null;
                $temp[$param['field'][$x]['result']] = &${$param['field'][$x]['result']};
            }

            call_user_func_array(array($stmt,'bind_result'),$temp);

            $stmt->fetch();

            $stmt->close();
            if(is_arr_empty($temp)){
                return false;
            }
            else {
                return $temp;
            }
        }
    }

    function delete($param){

        $stmtq = "DELETE FROM ";

        $stmtq .= $param['table']." WHERE ".$param['where']." = ?";

        if ($stmt = $this->query->prepare($stmtq)){
            $stmt->bind_param($param['param']['type'], $param['param']['value']); 
            $stmt->execute();

            $stmt->close();
            return true;
        }
        else {
            return false;
        }
    }
}
$table = 'dash';
$table1 = 'member';
$table2 = 'cat';

$param['param'] = array('value' => 8, 'type' => 'i');
$param['field'] = array(
    array('from' => $table, 'name' => 'rel_id', 'result' => 'id_kontraktor'), 
    array('from' => $table, 'name' => 'kategori', 'result' => 'id_kegiatan'), 
    array('from' => $table, 'name' => 'dp_info', 'result' => 'info_proyek'), 
    array('from' => $table, 'name' => 'dp_kontrak', 'result' => 'info_kontrak'), 
    array('from' => $table, 'name' => 'dp_pelaksana', 'result' => 'info_pelaksana'), 
    array('from' => $table, 'name' => 'dp_surat', 'result' => 'info_surat'), 
    array('from' => $table, 'name' => 'pf_info', 'result' => 'info_fisik'), 
    array('from' => $table, 'name' => 'mp_info', 'result' => 'info_monitor'), 
    array('from' => $table, 'name' => 'rp_info_prg', 'result' => 'info_progress'), 
    array('from' => $table, 'name' => 'rp_info_waktu', 'result' => 'info_waktu'), 
    array('from' => $table, 'name' => 'rp_info_prg_c', 'result' => 'chart_progress'), 
    array('from' => $table, 'name' => 'rp_info_waktu_c', 'result' => 'chart_waktu'), 
    array('from' => $table, 'name' => 'ke_info', 'result' => 'info_uang'), 
    array('from' => $table, 'name' => 'ke_c', 'result' => 'chart_uang'), 
    array('from' => $table, 'name' => 'gallery', 'result' => 'gallery'), 
    array('from' => $table, 'name' => 'mk_info', 'result' => 'info_monitor_k'), 
    array('from' => $table, 'name' => 'mk_c_xy', 'result' => 'chart_monitor_k_xy'), 
    array('from' => $table, 'name' => 'mk_c_info', 'result' => 'chart_monitor_k_info'), 
    array('from' => $table, 'name' => 'na_info', 'result' => 'info_nama'), 
    array('from' => $table, 'name' => 'lead_id', 'result' => 'id_pptk'), 
    array('from' => $table, 'name' => 'id', 'result' => 'id_proyek'), 
    array('from' => $table, 'name' => 'n_kontrak', 'result' => 'nilai_kontrak'), 
    array('from' => $table, 'name' => 'n_pagu', 'result' => 'nilai_pagu'),
    array('from' => $table, 'name' => 'dp_addendum', 'result' => 'info_addendum'),
    array('from' => $table1, 'name' => 'name', 'result' => 'nama_kontraktor'),
    array('from' => $table2, 'name' => 'title', 'result' => 'nama_kegiatan'),
);
$param['table'] = $table;
$param['join'] = 'left';
$param['join_table'] = array($table1, $table2);
$param['on'] = array(
    array(
        array('from' => $table, 'name' => 'rel_id'),
        array('from' => $table1, 'name' => 'id'),
    ),
    array(
        array('from' => $table, 'name' => 'kategori'),
        array('from' => $table2, 'name' => 'id'),
    )
);
$param['where'] = array('from' => $table, 'name' => 'id');

$param2['field'] = array(
    array('name' => 'id', 'result' => 'id_kegiatan'),
    array('name' => 'title', 'result' => 'nama_kegiatan')
);
$param2['table'] = 'cat';
$param2['where'] = 'id';
$param2['param'] = array('value' => '24', 'type' => 'i');

$process = new dbExec($query);
$array = $process->select($param2);

if($array){
    echo 'success';
} ?> */
/*
<!-- <head>
<script src="dash/jquery.js"></script>
<script>
$(document).ready(function(){

	$(".delete").click(function(){
		var count = $("tbody").children().length;
	  	if(count == 1){
	  		alert("Minimal 1 Divisi !");
	  		return false;
	  	}
	  	else {
			$(this).parentsUntil("tbody").remove();
	  	}
	});

	$(".add").click(function(){
		var append = "<tr><td><input name='pf_div[]' type='text'></td><td><input name='pf_title[]' type='text'></td><td><input name='pf_kontrak[]' type='text'></td><td><input name='pf_bln_1[]' type='text'></td><td><input name='pf_bln_2[]' type='text'></td><td><input name='pf_bln_3[]' type='text'></td><td><button class='delete' type='button'>DELETE</button></td></tr>"
		$(".main tbody").append(append);
	}); 

});
</script>
</head>
<table border="1" class="main">
    <thead>
        <tr>
            <td colspan="7">PROGRESS FISIK</td>
        </tr>
        <tr>
            <td rowspan="2">DIV.</td>
            <td rowspan="2">URAIAN</td>
            <td colspan="4">BOBOT (%)</td>
            <td rowspan="2">ACTION</td>
        </tr>
        <tr>
            <td>Kontrak</td>
            <td>Bulan Lalu</td>
            <td>Bulan Ini</td>
            <td>Sd/ Bulan Ini</td>
        </tr>
    </thead>
    <tbody>

        <tr>
            <td>1</td>
            <td><input name="pf_title[]" value="Mobilisasi" type="text"></td>
            <td><input name="pf_kontrak[]" value="8,43522" type="text"></td>
            <td><input name="pf_bln_1[]" value="5,90" type="text"></td>
            <td><input name="pf_bln_2[]" value="" type="text"></td>
            <td><input name="pf_bln_3[]" value="5,90" type="text"></td>
            <td><button class="delete" type="button">DELETE</button></td>
        </tr>
        
        <tr>
            <td>2</td>
            <td><input name="pf_title[]" value="Drainase" type="text"></td>
            <td><input name="pf_kontrak[]" value="0,775" type="text"></td>
            <td><input name="pf_bln_1[]" value="" type="text"></td>
            <td><input name="pf_bln_2[]" value="" type="text"></td>
            <td><input name="pf_bln_3[]" value="" type="text"></td>
            <td><button class="delete" type="button">DELETE</button></td>
        </tr>
        
        <tr>
            <td>3</td>
            <td><input name="pf_title[]" value="Pekerjaansss" type="text"></td>
            <td><input name="pf_kontrak[]" value="90,8" type="text"></td>
            <td><input name="pf_bln_1[]" value="0,97" type="text"></td>
            <td><input name="pf_bln_2[]" value="82,25" type="text"></td>
            <td><input name="pf_bln_3[]" value="83,22" type="text"></td>
            <td><button class="delete" type="button">DELETE</button></td>
        </tr>
                </tbody>
    </table>
    <button class="add" type="button">ADD ROW</button> -->*/
/*
    $date = "9/9/2017";

    $date = strtotime($date);
    $d2=ceil(($date-time())/60/60/24);
    echo "There are " . $d2 ." days until 4th of July.";*/

// select nilai addendum from csv format
// 
// SELECT CONVERT(REPLACE(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(dp_addendum, '/', -2), '"', -2), '"', ''), '/', ''), UNSIGNED) as n_addendum FROM dash ORDER BY `n_addendum` DESC
// 
// select total:
// select 
/*count(id) as total,
sum(@addendum:=CONVERT(REPLACE(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(dp_addendum, '/', -2), '\"', -2), '\"', ''), '/', ''), signed)) as addendum,
sum(n_pagu) as pagu,
sum(n_kontrak) as kontrak,
sum(ke_info) as penyerapan,
sum(convert(n_pagu, signed) - convert(ke_info, signed)) as sisa_pagu, 
sum(case 
    when @addendum > 0 then @addendum - convert(ke_info, signed) 
    else convert(n_kontrak, signed) - convert(ke_info, signed) 
end) as sisa_kontrak
from dash where kategori = 26

$stmtq_title = "REPLACE(REPLACE(SUBSTRING_INDEX(dp_info, '\"', 2), '\"', ''), '/', '')";
$stmtq_addendum = "CONVERT(REPLACE(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(dp_addendum, '/', -2), '\"', -2), '\"', ''), '/', ''), signed)";
$stmtq = "SELECT ".$stmtq_title." as title, ".$stmtq_addendum." as addendum, n_pagu as pagu, n_kontrak as kontrak, ke_info as penyerapan, convert(n_pagu, signed) - convert(ke_info, signed) as sisa_1, case when ".$stmtq_addendum." > 0 then ".$stmtq_addendum." - convert(ke_info, signed) else convert(n_kontrak, signed) - convert(ke_info, signed) end as sisa_2, sisa_2 FROM dash WHERE kategori = 26 ORDER BY title DESC";

echo $stmtq;*/

//     function pf($string, $decimal = 2) {

//         if(strpos($string, '.') !== false){

//             $string = explode('.', $string);

//             if(strlen($string[1]) > 2){
//                 $string[1] = substr($string[1], 0, $decimal);
//             }

//             if(strlen($string[0]) > 2){
//                 $string[0] = substr($string[0], 0, 3);
//                 $string[1] = '';

//                 settype($string[0], 'int');

//                 if($string[0] > 100) {
//                     $string[0] = 0;
//                 } 
//             }
//             else {
//                 $string[1] = '.'.$string[1];
//             }
            
//             return $string[0].$string[1];

//         }
//         else {
//             if(strlen($string) > 3) $string = substr($string, 0, 3);

//             settype($string, 'int');

//             if($string > 100) {
//                 $string = 0;
//             } 
//             return $string;
//         }

//     }

// echo pf('99.9927326');
// 

     
    $test = ?> <h4>HELLO</h4> <?php; 

    echo $test;