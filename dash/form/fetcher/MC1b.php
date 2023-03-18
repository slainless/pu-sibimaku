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

$pdf->TableRows(array('1', '2', '3  = (100 / 110 X 5)', '4  = (10 / 100 X 3)', '5 = ( 3 + 4 )'), 7, 'C');
$pdf->TableRows(array('', 'Nilai Kontrak', '503,928,271', '503,928,271', '503,928,271'), 13);
$pdf->TableRows(array('', 'Pembayaran s/d BAP yang lalu', '503,928,271', '503,928,271', '503,928,271'), 13);
$pdf->TableRows(array('', 'Pembayaran BAP ini', '503,928,271', '503,928,271', '503,928,271'), 13);
$pdf->TableRows(array('', 'Pembayaran s/d BAP yang ini', '503,928,271', '503,928,271', '503,928,271'), 13);
$pdf->TableRows(array('', 'Sisa Kontrak', '503,928,271', '503,928,271', '503,928,271'), 13);

$pdf->OutputTable();

$pdf->Ln(7);

$pdf->cell(5,5,"V.");
$pdf->Indent(7);
$pdf->WriteHTML("Pihak Kedua Sepakat atas Jumlah Pembayaran tersebut diatas dan dibayarkan kepada :");
$pdf->Ln(7);

	$pdf->SetFont('','B');
	$pdf->cell(20,5,"PT. DUTA MEGA PERKASA");
	$pdf->Ln();

	$pdf->SetFont('','');
	$pdf->cell(20,5,"Bank");
	$pdf->cell(5,5,":",0,0,'C');
	$pdf->SetFont('','B');
	$pdf->cell(20,5,"Bank BPD Kaltim Cabang Utama Samarinda");
	$pdf->Ln();

	$pdf->SetFont('','');
	$pdf->cell(20,5,"No. Rekening");
	$pdf->cell(5,5,":",0,0,'C');
	$pdf->SetFont('','B');
	$pdf->cell(20,5,"0031543771");
	$pdf->Ln();

	$pdf->SetFont('','');
	$pdf->cell(20,5,"No. NPWP");
	$pdf->cell(5,5,":",0,0,'C');
	$pdf->SetFont('','B');
	$pdf->cell(20,5,"02.196.487.9-721.000");
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
$pdf->cell($temp2,5,"PT. DUTA MEGA PERKASA",0,0,'C');
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
$pdf->cell($temp2,5,"Ir. SUMADYO",0,0,'C');
$pdf->cell($temp,0);
$pdf->cell(0,5,"SUDJADI, ST. MT",0,0,'C');
$pdf->SetFont('','');
$pdf->Ln();

$pdf->cell($temp2,5,"Direktur Utama",0,0,'C');
$pdf->cell($temp,0);
$pdf->cell(0,5,"NIP. 19700622 199803 1 005",0,0,'C');
$pdf->Ln();
					
