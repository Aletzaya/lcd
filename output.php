<?php

	require_once('lib/mpdf57/mpdf.php');
	$mpdf = new mPDF('c', 'A4');
	$html='informes/bh.php?Orden=608791&Estudio=BH';
	
	$mpdf->WriteHTML($html);
	$mpdf->Output('reporte.pdf', 'I');

?>