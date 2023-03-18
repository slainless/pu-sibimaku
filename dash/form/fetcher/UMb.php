<?php
$pdf->setMargins(13,11);
$pdf->SetIndent(13);
$margin = $pdf->GetMargins();

$pdf->AddPage();
$pdf->SetFont('','');

$pdf->cell(5,5,"IV.");
$pdf->Indent(7);
$pdf->WriteHTML("REKAPITULASI PEMBAYARAN KONTRAK");
$pdf->Indent(-7);
$pdf->Ln(10);

$header = array(
	array('title' => 'No', 'width' => 7, 'number' => true),
	array('title' => 'URAIAN', 'width' => 50),
	array('title' => 'FISIK (Rp)', 'width' => 0),
	array('title' => 'PPn (Rp)', 'width' => 0),
	array('title' => 'JUMLAH (Rp)', 'width' => 0),
);

$columnOption = array(
	array('align' => 'C'),
	array('align' => 'L'),
	array('align' => 'R'),
	array('align' => 'R'),
	array('align' => 'R')
);

$headerOption = array('height' => 10);

$pdf->SetLineWidth(0.2);
$pdf->InitTable($header, $headerOption, $columnOption);

unset($temp);
$temp['nilai_total'] = floor($result['proyek_nilai']);
$temp['nilai'] = floor($var['nilai']);
$temp['selisih'] = $temp['nilai_total'] - $temp['nilai'];


$pdf->TableRows(array('1', '2', '3  = (100 / 110 X 5)', '4  = (10 / 100 X 3)', '5 = ( 3 + 4 )'), 7, 'C');
$pdf->TableRows(
	array('', 'Nilai Kontrak', 
		number_format(round(floor($temp['nilai_total']) * 100 / 110)), 
		number_format(round((floor($temp['nilai_total']) * 100/110) * 10/100)), 
		number_format($temp['nilai_total'])
	),
13);
$pdf->TableRows(array('', 'Pembayaran s/d BAP yang lalu', '-', '-', '-'), 13);

$pdf->TableRows(
	array('', 'Pembayaran BAP ini', 
		number_format(round($temp['nilai'] * 100 / 110)), 
		number_format(round(($temp['nilai'] * 100/110) * 10/100)), 
		number_format($temp['nilai'])
	),
13);

$pdf->TableRows(
	array('', 'Pembayaran s/d BAP yang ini', 
		number_format(round($temp['nilai'] * 100 / 110)), 
		number_format(round(($temp['nilai'] * 100/110) * 10/100)), 
		number_format($temp['nilai'])
	),
13);
$pdf->TableRows(
	array('', 'Sisa Kontrak', 
		number_format(round($temp['selisih'] * 100 / 110)), 
		number_format(round(($temp['selisih'] * 100/110) * 10/100)), 
		number_format($temp['selisih'])
	),
13);

$pdf->OutputTable();

$pdf->Ln(7);

$pdf->cell(5,5,"V.");
$pdf->Indent(7);
$pdf->WriteHTML("Pihak Kedua Sepakat atas Jumlah Pembayaran tersebut diatas dan dibayarkan kepada :");
$pdf->Ln(7);

	$pdf->SetFont('','B');
	$pdf->cell(20,5,$result['kon_perusahaan']);
	$pdf->Ln();

	$pdf->SetFont('','');
	$pdf->cell(20,5,"Bank");
	$pdf->cell(5,5,":",0,0,'C');
	$pdf->SetFont('','B');
	$pdf->cell(20,5,$result['kon_bank']);
	$pdf->Ln();

	$pdf->SetFont('','');
	$pdf->cell(20,5,"No. Rekening");
	$pdf->cell(5,5,":",0,0,'C');
	$pdf->SetFont('','B');
	$pdf->cell(20,5,$result['kon_rekening']);
	$pdf->Ln();

	$pdf->SetFont('','');
	$pdf->cell(20,5,"No. NPWP");
	$pdf->cell(5,5,":",0,0,'C');
	$pdf->SetFont('','B');
	$pdf->cell(20,5,$result['kon_npwp']);
	$pdf->SetFont('','');
	$pdf->Ln(14);

$pdf->Indent(-7);
$pdf->WriteHTML("Demikian Berita Acara Pembayaran ini dibuat dalam rangkap 5 ( Lima ) untuk dipergunakan sebagaimana mestinya.");

$temp = 20;
$temp2 = ($pdf->GetPageWidth() - $margin['left'] - $margin['right'] - $temp)/2;

$pdf->Ln(28);
$pdf->cell($temp2,5,"Menerima dan Menyetujui :",0,0,'C');
$pdf->cell($temp,0);
$pdf->cell(0,5,"Menyerahkan :",0,0,'C');
$pdf->Ln();

$pdf->cell($temp2,5,"PIHAK PERTAMA",0,0,'C');
$pdf->cell($temp,0);
$pdf->cell(0,5,"PIHAK KEDUA",0,0,'C');
$pdf->Ln();

$pdf->SetFont('','B');
$pdf->cell($temp2,5,strtoupper($result['kon_perusahaan']),0,0,'C');
$pdf->cell($temp,0);
$pdf->cell(0,5,"Dinas PUPR-PERKIM Prov. Kalimantan Utara",0,0,'C');
$pdf->SetFont('','');
$pdf->Ln();

$pdf->cell($temp2,5,"",0,0,'C');
$pdf->cell($temp,0);
$pdf->cell(0,5,"Pejabat Pembuat Komitmen,",0,0,'C');
$pdf->Ln();
$pdf->Ln(30);

$pdf->SetFont('','BU');
$pdf->cell($temp2,5,$var['nama_2'],0,0,'C');
$pdf->cell($temp,0);
$pdf->cell(0,5,$var['nama_1'],0,0,'C');
$pdf->SetFont('','');
$pdf->Ln();

$pdf->cell($temp2,5,"Direktur Utama",0,0,'C');
$pdf->cell($temp,0);
$pdf->cell(0,5,"NIP. ".$result['ppk_nip'],0,0,'C');
$pdf->Ln();
					
