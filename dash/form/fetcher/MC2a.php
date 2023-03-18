<?php
$pdf->setMargins(13,5);
$pdf->SetIndent(13);
$margin = $pdf->GetMargins();

$pdf->AddPage();
// PAGE #1
// [# HEADER #]
$pdf->Image('logo.png',$margin['left'] + 5,$margin['top'],16);

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
$pdf->Cell(0,2,"Nomor : 620/25.08/BAP/PU-BM/IV/2017",0,1,'C');
$pdf->Ln(8);

$pdf->cell(5,5,"I.");

$pdf->Indent(7);
$pdf->WriteHTML("Pada hari ini <b>Selasa</b> Tanggal <b>Dua Puluh Lima</b> Bulan <b>April</b> Tahun <b>Dua Ribu Tujuh Belas</b>, Kami yang bertanda tangan dibawah ini :");
	$pdf->Ln(7);

	$pdf->cell(7,5,"1.");

		$pdf->cell(20,5,"Nama");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->SetFont('','B');
		$pdf->cell(20,5,"SUDJADI, ST. MT");
		$pdf->Ln();

		$pdf->Indent(7);
		$pdf->SetFont('','');
		$pdf->cell(20,5,"N I P");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->cell(20,5,"19700622 199803 1 005");
		$pdf->Ln();

		$pdf->SetFont('','');
		$pdf->cell(20,5,"Jabatan");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->Indent(25);
		$pdf->WriteHTML("Pejabat Pembuat Komitmen Dinas Pekerjaan Umum dan Tata Ruang Provinsi Kalimantan Utara");
		$pdf->Indent(-25);
		$pdf->Ln();
		
		$pdf->SetFont('','');
		$pdf->cell(20,5,"Alamat");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->cell(20,5,"Jalan Agatis, Tanjung Selor",0,2);
		$pdf->WriteHTML("untuk selanjutnya disebut <b>PIHAK PERTAMA</b>");
	$pdf->Ln(7);

	$pdf->Indent(-7);
	$pdf->cell(7,5,"2.");

		$pdf->cell(20,5,"Nama");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->SetFont('','B'); $pdf->cell(20,5,"Ir. SUMADYO");
		$pdf->Ln();

		$pdf->Indent(7);
		$pdf->SetFont('','');
		$pdf->cell(20,5,"Jabatan");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->WriteHTML("Direktur PT. Duta Mega Perkasa");
		$pdf->Ln();

		$pdf->SetFont('','');
		$pdf->cell(20,5,"Alamat");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->Indent(25);
		$pdf->cell(20,5,"Perumahan Bumi Rengganis Blok 3A No. 93 Balikpapan - Kalimantan Timur",0,2);
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
		$pdf->cell(60,5,"Nomor DPA  SKPD TA. 2017");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->WriteHTML("1.03.1.03.01.15.03<br>");

		$pdf->Indent(12);
		$pdf->cell(60,5,"Tanggal");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->WriteHTML("13 Januari 2017<br>");

		$pdf->Indent(-5);
		$pdf->cell(5,5,"b)",0,0,'C');
		$pdf->cell(60,5,"Nama dan Kode Pekerjaan");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->Indent(70);
		$pdf->WriteHTML("Pembangunan Jalan Penghubung Kecamatan Sembakung Kabupaten Nunukan<br>");
		$pdf->WriteHTML("1.03.1.03.01.15.03.5.2.3.59.02<br>");
		$pdf->Indent(-70);

		$pdf->cell(5,5,"c)",0,0,'C');
		$pdf->cell(60,5,"Nomor Kontrak");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->WriteHTML("620/11.09/KNTRK/PU-BM/IV/2017<br>");

		$pdf->Indent(5);

		$pdf->cell(60,5,"Addendum Kontrak 01");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->WriteHTML("620/15.02/PPK/ADD.01-SEMBAKUNG-KTT/V/2017<br>");

		$pdf->cell(60,5,"Addendum Kontrak 02");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->WriteHTML("620/15.02/PPK/ADD.01-SEMBAKUNG-KTT/V/2017<br>");

		$pdf->Indent(-5);

		$pdf->cell(5,5,"d)",0,0,'C');
		$pdf->cell(60,5,"Tanggal Kontrak");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->WriteHTML("11 April 2017<br>");

		$pdf->Indent(5);

		$pdf->cell(60,5,"Tanggal Kontrak 01");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->WriteHTML("15 Mei 2017<br>");

		$pdf->cell(60,5,"Tanggal Kontrak 01");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->WriteHTML("15 Mei 2017<br>");

		$pdf->Indent(-5);

		$pdf->cell(5,5,"e)",0,0,'C');
		$pdf->cell(60,5,"Nilai Kontrak");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->SetFont('','B');
		$pdf->cell(10,5,"Rp.",0,0);
		$pdf->SetFont('','');
		$pdf->WriteHTML("<b>3,098,892,000.00</b><br>");

		$pdf->Indent(5);

		$pdf->cell(60,5,"Nilai Kontrak 01");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->SetFont('','B');
		$pdf->cell(10,5,"Rp.",0,0);
		$pdf->SetFont('','');
		$pdf->WriteHTML("<b>3,098,892,000.00</b><br>");

		$pdf->cell(60,5,"Nilai Kontrak 01");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->SetFont('','B');
		$pdf->cell(10,5,"Rp.",0,0);
		$pdf->SetFont('','');
		$pdf->WriteHTML("<b>3,098,892,000.00</b><br>");

		$pdf->Indent(-5);

		$pdf->cell(5,5,"f)",0,0,'C');
		$pdf->cell(60,5,"Lokasi");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->WriteHTML("Kabupaten Tana Tidung<br>");

		$pdf->cell(5,5,"g)",0,0,'C');
		$pdf->cell(60,5,"Sumber Dana");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->WriteHTML("APBD PROVINSI KALIMANTAN UTARA<br>");

		$pdf->cell(5,5,"h)",0,0,'C');
		$pdf->cell(60,5,"Tahun Anggaran");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->WriteHTML("2017");
	$pdf->Ln(7);

	$pdf->Indent(-7);
	$pdf->cell(7,5,"2.");
	$pdf->Indent(7);
	$pdf->WriteHTML("Sertifikat Bulanan <b>(MC) No. 03 (Juni 2017)</b> yang menyatakan pekerjaan telah mencapai <b>100 %</b> atau sebesar <b>Rp. 3.098.892.000,00 (Tiga Milyar Sembilan Puluh Delapan Juta Delapan Ratus Sembilan Puluh Dua Ribu Rupiah)</b>");
	$pdf->Ln(7);

	$pdf->Indent(-7);
	$pdf->cell(7,5,"3.");
	$pdf->Indent(7);
	$pdf->WriteHTML("Berita Acara Serah Terima Pertama Pekerjaan (PHO) Nomor : <b>620/18.02/PPK/BA.PHO-PEMB.SEMBAKUNG-KTT/VII/2017</b> tanggal <b>18 Juli 2017</b>");


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
		$pdf->cell(113,5,"Nilai Pekerjaan s/d Penagihan Ini");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->cell(5,5,"Rp. ",0,0);
		$pdf->cell(0,5,"619,778,400.00",0,0,'R');
		$pdf->Ln();

		$pdf->cell(5,5,"b.",0,0,'C');
		$pdf->cell(113,5,"Nilai Pekerjaan s/d Penagihan Tahap Sebelumnya");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->cell(5,5,"Rp. ",'B',0);
		$pdf->cell(0,5,"-",'B',0,'R');
		$pdf->Ln();

		$pdf->cell(5,5,"c.",0,0,'C');
		$pdf->cell(113,5,"Nilai Kotor Pekerjaan pada Penagihan ini ( a - b )");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->cell(5,5,"Rp. ",0,0);
		$pdf->cell(0,5,"619,778,400.00",0,0,'R');
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
		$pdf->cell(113,5,"Jumlah");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->cell(5,5,"Rp. ",0,0);
		$pdf->cell(0,5,"619,778,400.00",0,0,'R');
		$pdf->Ln();

		$pdf->cell(5,5,"f.",0,0,'C');
		$pdf->cell(113,5,"Kekurangan Pembayaran Akibat Pembulatan MC");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->SetFont('','B');
		$pdf->cell(5,5,"Rp. ",'B',0);
		$pdf->cell(0,5,"619,778,400.00",'B',0,'R');
		$pdf->SetFont('','');
		$pdf->Ln();

		$pdf->cell(5,5,"g.",0,0,'C');
		$pdf->cell(113,5,"Jumlah Nilai Sertifikat ini (termasuk PPn 10%)");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->cell(5,5,"Rp. ",0,0);
		$pdf->cell(0,5,"563,434,909,00",0,0,'R');
		$pdf->Ln();

		$pdf->SetFont('','B');
		$pdf->cell(5,5,"h.",0,0,'C');
		$pdf->cell(113,5,"Pembulatan");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->cell(5,5,"Rp. ",0,0);
		$pdf->cell(0,5,"56,343,491,00",0,0,'R');
		$pdf->SetFont('','');
		$pdf->Ln();

		$pdf->cell(5,5,"i.",0,0,'C');
		$pdf->cell(113,5,"Nilai Fisik Sertifikat ini ( Rp. 619.778.400,00  / 1,1)");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->cell(5,5,"Rp. ",0,0);
		$pdf->cell(0,5,"563,434,909,00",0,0,'R');
		$pdf->Ln();

		$pdf->cell(5,5,"j.",0,0,'C');
		$pdf->cell(113,5,"PPN 10 %  ( 10 %  X  g )");
		$pdf->cell(5,5,":",0,0,'C');
		$pdf->cell(5,5,"Rp. ",'B',0);
		$pdf->cell(0,5,"56,343,491,00",'B',0,'R');
		$pdf->Ln();

		$pdf->SetFont('','B');
		$pdf->cell(5,7,"k.",0,0,'C');
		$pdf->cell(113,7,"Jumlah Nilai Sertifikat ini ( g + h )");
		$pdf->cell(5,7,":",0,0,'C');
		$pdf->cell(5,7,"Rp. ",'B',0);
		$pdf->cell(0,7,"56,343,491,00",'B',0,'R');
		$pdf->SetFont('','');
		$pdf->Ln(10);

$pdf->Indent(-14);
$pdf->SetFont('','B');
$pdf->cell(15,5,"Terbilang",0,0,'C');
$pdf->cell(5,5,":",0,0,'C');
$pdf->Indent(20);
$pdf->WriteHTML("(Enam Ratus Sembilan Belas Juta Tujuh Ratus Tujuh Puluh Delapan Ribu Empat Ratus Rupiah)");
$pdf->Indent(-20);
