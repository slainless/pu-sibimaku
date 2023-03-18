<?php
require('fpdf181/fpdf.php');
require('extended.php');

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();

$pdf->AddFont('Tahoma','','tahoma.php');
$pdf->AddFont('Tahoma','B','tahomabd.php');

$mode = isset($_GET['mode']) ? $_GET['mode'] : 0;

switch ($mode):
	case '0':
		require 'UMa.php';
		require 'UMb.php';
	break;
	
	case '1':
		require 'MC1a.php';
		require 'MC1b.php';
	break;

	case '2':
		require 'MC2a.php';
		require 'MC2b.php';
	break;

	case '3':
		require 'MC3a.php';
		require 'MC3b.php';
	break;
endswitch;


$pdf->Output();
?>