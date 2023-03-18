<?php

$stmtq = "
    SELECT 
        ppk.name as ppk_name, 
        ppk.nip as ppk_nip, 
        kontraktor.direktur as kon_name, 
        kontraktor.alamat as kon_alamat,
        kontraktor.perusahaan as kon_perusahaan,
        kontraktor.bank as kon_bank,
        kontraktor.rekening as kon_rekening,
        kontraktor.npwp as kon_npwp,
        replace(substring_index(".$tbl_dash.".dp_info, ',', 1), '\"', '') as proyek_name, 
        replace(substring_index(".$tbl_dash.".dp_info, ',', -1), '\"', '') as proyek_lokasi,
        replace(substring_index(".$tbl_dash.".dp_kontrak, ',', 1), '\"', '') as proyek_kontrak, 
        replace(substring_index(".$tbl_dash.".dp_kontrak, ',', -1), '\"', '') as proyek_tanggal,
        ".$tbl_dash.".n_kontrak as proyek_nilai,
        ".$tbl_dash.".id as proyek_id,
        (select tahun from cat where id = ".$tbl_dash.".kategori) as proyek_tahun,
        ".$tbl_mail.".form as form
    FROM 
        (select name, nip from ".$tbl_mem." where level = 4 and mask_level = 4 and f_assign = 1) as ppk 
        join 
        (select direktur, alamat, perusahaan, bank, npwp, rekening from ".$tbl_mem." where id = 
            (select rel_id from ".$tbl_dash." where id = (select rel_id from ".$tbl_mail." where id = ?))
        ) as kontraktor
        join
        ".$tbl_dash." 
        join ".$tbl_mail." 
    on ".$tbl_mail.".rel_id = ".$tbl_dash.".id
    where ".$tbl_mail.".id = ?";

$param['param']['type'] = 'ii';
$param['param']['value'] = array($code['id'], $code['id']);
$param['result'] = array('ppk_name','ppk_nip','kon_name','kon_alamat','kon_perusahaan','kon_bank','kon_rekening','kon_npwp','proyek_name','proyek_lokasi','proyek_kontrak','proyek_tanggal','proyek_nilai', 'proyek_id', 'proyek_tahun', 'form');

$result = $exec->freeQuery($stmtq, $param);
unset($param);

if(!$result)
    errCode("404", "Page not found");

$pdf->setMargins(13,5);
$pdf->SetIndent(13);
$margin = $pdf->GetMargins();

$pdf->AddPage();
// PAGE #1
// [# HEADER #]
$pdf->Image('assets/images/logo.png',$margin['left'] + 5,$margin['top'],16);

$pdf->Cell(25);

$pdf->SetFont('Tahoma','B',11);
$pdf->Cell(0,5,'PEMERINTAH PROVINSI KALIMANTAN UTARA',0,2,'C');

$pdf->SetFontSize(13);
$pdf->Cell(0,6,'DINAS PEKERJAAN UMUM, PENATAAN RUANG,',0,2,'C');

$pdf->SetFontSize(13);
$pdf->Cell(0,6,'PERUMAHAN DAN KAWASAN PERMUKIMAN',0,2,'C');

$pdf->SetFont('','',8);
$pdf->Cell(0,5,'Jalan Agatis Telp. 0552-21490 fax. 0552-21452 , Tanjung Selor',0,1,'C');
$pdf->Ln(3);
$pdf->SetLineWidth(0.3);
$pdf->Cell(0,0,'','B',2);
$pdf->Cell(0,0.7,'','B');
// Line break
$pdf->Ln(8);

$pdf->SetFont('Arial','UB',12);
$pdf->Cell(0,5,"BERITA ACARA PEMBAYARAN",0,1,'C');
$pdf->Ln(2);
$pdf->SetFont('','',8);
$pdf->Cell(0,2,"Nomor : ".$var['kontrak'],0,1,'C');
$pdf->Ln(8);

$pdf->cell(5,5,"I.");

$var['tanggal_bap'] = DateTime::createFromFormat('d/m/Y', $var['tanggal_bap'])->format('N/j/n/Y');
$var['tanggal_bap'] = explode('/', $var['tanggal_bap']);

$var['tanggal_bap'][0] = translate($var['tanggal_bap'][0], 'day');
$var['tanggal_bap'][1] = ucwords(numtoText($var['tanggal_bap'][1]));
$var['tanggal_bap'][2] = translate($var['tanggal_bap'][2], 'month');
$var['tanggal_bap'][3] = ucwords(numtoText($var['tanggal_bap'][3]));

$pdf->Indent(7);
$pdf->WriteHTML("Pada hari ini <b>".$var['tanggal_bap'][0]."</b> Tanggal <b>".$var['tanggal_bap'][1]."</b> Bulan <b>".$var['tanggal_bap'][2]."</b> Tahun <b>".$var['tanggal_bap'][3]."</b>, Kami yang bertanda tangan dibawah ini :");
	$pdf->Ln(7);

	$pdf->cell(7,5,"1.");

		$pdf->cell(20,5,"Nama");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->SetFont('','B');

		$var['nama_1'] = $var['nama_1'] ?? $result['ppk_name'];

		$pdf->cell(20,5,$var['nama_1']);
		$pdf->Ln();

		$pdf->Indent(7);
		$pdf->SetFont('','');
		$pdf->cell(20,5,"N I P");
		$pdf->cell(5,5,":",0,0,'C');

		$pdf->cell(20,5,$result['ppk_nip']);
		$pdf->Ln();

		$pdf->SetFont('','');
		$pdf->cell(20,5,"Jabatan");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->Indent(25);

		$pdf->WriteHTML('Pejabat Pembuat Komitmen Dinas Pekerjaan Umum dan Tata Ruang Provinsi Kalimantan Utara');
		$pdf->Indent(-25);
		$pdf->Ln();
		
		$pdf->SetFont('','');
		$pdf->cell(20,5,"Alamat");
		$pdf->cell(5,5,":",0,0,'C');

		$pdf->cell(20,5,'Jalan Agatis, Tanjung Selor',0,2);
		$pdf->WriteHTML("untuk selanjutnya disebut <b>PIHAK PERTAMA</b>");
	$pdf->Ln(7);

	$pdf->Indent(-7);
	$pdf->cell(7,5,"2.");

		$pdf->cell(20,5,"Nama");
		$pdf->cell(5,5,":",0,0,'C');

		$var['nama_2'] = $var['nama_2'] ?? $result['kon_name'];

		$pdf->SetFont('','B'); $pdf->cell(20,5,$var['nama_2']);
		$pdf->Ln();

		$pdf->Indent(7);
		$pdf->SetFont('','');
		$pdf->cell(20,5,"Jabatan");
		$pdf->cell(5,5,":",0,0,'C');

		$pdf->WriteHTML("Direktur ".$result['kon_perusahaan']);
		$pdf->Ln();

		$pdf->SetFont('','');
		$pdf->cell(20,5,"Alamat");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->Indent(25);

		$pdf->cell(20,5,$result['kon_alamat'],0,2);
		$pdf->WriteHTML("untuk selanjutnya disebut <b>PIHAK KEDUA</b>");
		$pdf->Indent(-25);

	$pdf->Ln(7);

$pdf->Indent(-14);
$pdf->cell(5,5,"II.");
$pdf->Indent(7);
$pdf->WriteHTML("Berdasarkan");
$pdf->Ln(7);

	$pdf->cell(7,5,"1.");
		
		$pdf->cell(5,5,"a)",0,0,'C');
		$pdf->cell(60,5,"Nomor DPA SKPD TA. ".$result['proyek_tahun']);
		$pdf->cell(5,5,":",0,0,'C');

		$pdf->WriteHTML($var['dpa']."<br>");

		$pdf->Indent(12);
		$pdf->cell(60,5,"Tanggal");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->WriteHTML(changeDate($var['tanggal_dpa'], 'd/m/Y', 'd F Y', true)."<br>");

		$pdf->Indent(-5);
		$pdf->cell(5,5,"b)",0,0,'C');
		$pdf->cell(60,5,"Nama dan Kode Pekerjaan");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->Indent(70);

		$pdf->WriteHTML($result['proyek_name']."<br>");

		$pdf->WriteHTML($var['kode']."<br>");
		$pdf->Indent(-70);

		$pdf->cell(5,5,"c)",0,0,'C');
		$pdf->cell(60,5,"Nomor Kontrak");
		$pdf->cell(5,5,":",0,0,'C');

		$pdf->WriteHTML($result['proyek_kontrak']."<br>");

		$pdf->cell(5,5,"d)",0,0,'C');
		$pdf->cell(60,5,"Tanggal Kontrak");
		$pdf->cell(5,5,":",0,0,'C');

		$pdf->WriteHTML(changeDate($result['proyek_tanggal'], 'd-m-Y', 'd F Y', true)."<br>");

		$pdf->cell(5,5,"e)",0,0,'C');
		$pdf->cell(60,5,"Nilai Kontrak");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->SetFont('','B');
		$pdf->cell(10,5,"Rp.",0,0);
		$pdf->SetFont('','');

		$pdf->WriteHTML("<b>".number_format($result['proyek_nilai'], 2)."</b><br>");

		$pdf->cell(5,5,"f)",0,0,'C');
		$pdf->cell(60,5,"Lokasi");
		$pdf->cell(5,5,":",0,0,'C');

		$pdf->WriteHTML($result['proyek_lokasi']."<br>");

		$pdf->cell(5,5,"g)",0,0,'C');
		$pdf->cell(60,5,"Sumber Dana");
		$pdf->cell(5,5,":",0,0,'C');

		$var['sumber'] = $var['sumber'] ?? 1;

		switch ($var['sumber']) {
			case 1: $var['sumber'] = 'APBD PROVINSI KALIMANTAN UTARA'; break;
			case 2: $var['sumber'] = 'APBN PROVINSI KALIMANTAN UTARA'; break;
			default: $var['sumber'] = ''; break;
		}

		$pdf->WriteHTML($var['sumber']."<br>");

		$pdf->cell(5,5,"h)",0,0,'C');
		$pdf->cell(60,5,"Tahun Anggaran");
		$pdf->cell(5,5,":",0,0,'C');

		$pdf->WriteHTML($result['proyek_tahun']);
	$pdf->Ln(7);

	$pdf->Indent(-7);
	$pdf->cell(7,5,"2.");
	$pdf->Indent(7);

	switch ($result['kon_bank']) {
		case '1': $temp = 'Bank Kaltim'; $result['kon_bank'] = 'Bank BPD Kaltim Cabang Utama Samarinda'; break;
		case '2': $temp = 'APBN PROVINSI KALIMANTAN UTARA'; break;
		default: $temp = ''; break;
	}

	$var['nilai'] = $result['proyek_nilai'] * 20/100;

	$pdf->WriteHTML("Untuk Pembayaran Uang Muka berdasarkan Jaminan Uang Muka dari <b>".$temp."</b> Nomor : <b>".$var['bayar']."</b> Tanggal <b>".changeDate($var['tanggal_bayar'], 'd/m/Y', 'd F Y', true)."</b> sebesar <b>Rp. ".number_format($var['nilai'], 2)." (".ucwords(numtoText($var['nilai'], true))." Rupiah)</b>");


$pdf->Indent(-14);
$pdf->Ln(7);
$pdf->cell(5,5,"III.");
$pdf->Indent(7);
$pdf->WriteHTML("Sesuai Kontrak maka <b>PIHAK KEDUA</b> berhak menerima pembayaran dari <b>PIHAK KESATU</b> dengan perincian sebagai berikut :");
$pdf->Ln(7);

	$pdf->cell(7,5,"1.");
	$pdf->cell(0,5,"Perhitungan Pembayaran");
	$pdf->Ln();

		$pdf->Indent(7);
		$pdf->cell(5,5,"a.",0,0,'C');
		$pdf->cell(113,5,"Nilai Pekerjaan s/d Bulan Ini (20 % x Rp. 3.098.892.000,00)");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->cell(5,5,"Rp. ",0,0);
		$pdf->cell(0,5,number_format($var['nilai'], 2),0,0,'R');
		$pdf->Ln();

		$pdf->cell(5,5,"b.",0,0,'C');
		$pdf->cell(113,5,"Nilai Pekerjaan s/d Bulan Lalu");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->cell(5,5,"Rp. ",'B',0);
		$pdf->cell(0,5,"-",'B',0,'R');
		$pdf->Ln();

		$pdf->cell(5,5,"c.",0,0,'C');
		$pdf->cell(113,5,"Nilai Kotor Pekerjaan Bulan ini ( a - b )");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->cell(5,5,"Rp. ",0,0);
		$pdf->cell(0,5,number_format($var['nilai'], 2),0,0,'R');
		$pdf->Ln();

		$pdf->cell(5,5,"d.",0,0,'C');
		$pdf->cell(113,5,"Potongan - Potongan");
		$pdf->Ln();

			$pdf->Indent(7);
			$pdf->cell(5,5,"(i)",0,0,'C');
			$pdf->cell(55,5,"Jaminan Pemeliharaan/Retensi ( 5% )");
			$pdf->cell(5,5,":",0,0,'C');
			$pdf->cell(5,5,"Rp. ",0,0);
			$pdf->cell(42,5,"-",0,0,'R');
			$pdf->Ln();

			$pdf->cell(5,5,"(ii)",0,0,'C');
			$pdf->cell(55,5,"Pemngembalian Uang Muka ( 20% )");
			$pdf->cell(5,5,":",0,0,'C');
			$pdf->cell(5,5,"Rp. ",'B',0);
			$pdf->cell(42,5,"-",'B',0,'R');
			$pdf->cell(5,5," (+)",0,0,'C');
			$pdf->Ln();

			$pdf->cell(5,5,"(iii)",0,0,'C');
			$pdf->cell(106,5,"Jumlah Potongan");
			$pdf->cell(5,5,":",0,0,'C');
			$pdf->cell(5,5,"Rp. ",'B',0);
			$pdf->cell(0,5,"-",'B',0,'R');
			$pdf->Ln();

		$pdf->Indent(-7);
		$pdf->cell(5,5,"e.",0,0,'C');
		$pdf->cell(113,5,"Jumlah Nilai Sertifikat ini (termasuk PPn 10%)");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->cell(5,5,"Rp. ",0,0);
		$pdf->cell(0,5,number_format($var['nilai'], 2),0,0,'R');
		$pdf->Ln();

		$pdf->cell(5,5,"f.",0,0,'C');
		$pdf->cell(113,5,"Pembulatan");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->SetFont('','B');
		$pdf->cell(5,5,"Rp. ",0,0);
		$pdf->cell(0,5,number_format(floor($var['nilai']), 2),0,0,'R');
		$pdf->SetFont('','');
		$pdf->Ln();

		$pdf->cell(5,5,"g.",0,0,'C');
		$pdf->cell(113,5,"Nilai Fisik Sertifikat ini ( Rp. ".number_format($var['nilai'], 2)." / 1,1)");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->cell(5,5,"Rp. ",0,0);
		$pdf->cell(0,5,number_format(round(floor($var['nilai']) * 100 / 110), 2),0,0,'R');
		$pdf->Ln();

		$pdf->cell(5,5,"h.",0,0,'C');
		$pdf->cell(113,5,"PPN 10 %  ( 10 %  X  g )");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->cell(5,5,"Rp. ",'B',0);
		$pdf->cell(0,5,number_format(round((floor($var['nilai']) * 100/110) * 10/100), 2),'B',0,'R');
		$pdf->Ln();

		$pdf->cell(5,5,"i.",0,0,'C');
		$pdf->cell(113,5,"Jumlah Nilai Sertifikat ini ( g + h )");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->cell(5,5,"Rp. ",0,0);
		$pdf->cell(0,5,number_format(floor($var['nilai']), 2),0,0,'R');
		$pdf->Ln(10);

$pdf->Indent(-14);
$pdf->SetFont('','B');
$pdf->cell(15,5,"Terbilang",0,0,'C');
$pdf->cell(5,5,":",0,0,'C');
$pdf->Indent(20);
$pdf->WriteHTML("(".trim(ucwords(numtoText(floor($var['nilai']))))." Rupiah)");
$pdf->Indent(-20);
