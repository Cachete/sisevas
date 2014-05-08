<?php
session_start();
require("../lib/fpdf/fpdf.php");
class PDF extends FPDF
{
    function Header()
    {
        //global ;
        /*$this->Line(7, 17, 142, 17);         
        //$this->Image('../../images/logos.jpg',7,4,30,12);
        $this->SetFont('Arial','B',8); 
        $this->SetXY(40, 19);
        //$this->Cell(0,5,'MANUAL DE BUENAS PRÁCTICAS DE MANUFACTURA Y GESTIÓN',1,1,'L');
        /*$this->MultiCell(55,4,utf8_decode('MANUAL DE BUENAS PRÁCTICAS DE MANUFACTURA Y GESTIÓN'),0,'C');
        $this->SetXY(105, 18);
        $this->SetFont('Arial','I',6);
        //$this->Cell(12,4,'CODIGO :',0,0,'L');
        //$this->Cell(0,4,$cab[0]['codigo'],0,1,'L');
        $this->SetXY(105, 21);
        $this->Cell(12,4,'VERSION :',0,0,'L');
        $this->Cell(0,4,'1.0',0,1,'L');
        $this->Line(7, 28, 142, 28);  */          
        
        $this->Ln(15);       
       
    }
    
    function cuerpo($cab)
    {
        $hoy = date("Y"); 
        //N° de documento
        $this->SetFont('Arial','B',9);
        $this->Cell(0, 5, strtoupper(utf8_decode('TARAPOTO, 12 de MAYO del '.$hoy)), 0, 1, 'R');
        $this->Ln(4);
               
        $this->SetFont('Arial','',9);
        $this->Cell(14, 5, 'Sr (a) :', 0, 1, 'L');
        //$this->Cell(2, 5, ':', 0, 0, 'R');
        $this->SetFont('Arial','B',10);
        $this->Cell(10, 5, strtoupper(utf8_decode($cab[0]['destinatario'])), 0, 1, 'L');
        $this->Ln(3);
        /*$this->SetX(26);
        $this->SetFont('Arial','',8);
        $this->Cell(0, 5, strtoupper(utf8_decode($cab[0]['cargo_d'])), 0, 1, 'L');*/

        //SOLICITANTE
        $this->SetFont('Arial','B',8);
        $this->Cell(14, 5, 'TARAPOTO', 0, 0, 'L');
        /*$this->Cell(2, 5, ':', 0, 0, 'R');
        $this->SetFont('Arial','B',8);
        $this->Cell(10, 5, strtoupper(utf8_decode($cab[0]['remitente'])), 0, 1, 'L');
        $this->SetX(26);
        $this->SetFont('Arial','',8);
        $this->Cell(0, 5, strtoupper(utf8_decode($cab[0]['cargo_r'])), 0, 1, 'L');*/

        //ASUNTO
        /*$this->Cell(14, 5, 'ASUNTO', 0, 0, 'L');
        $this->Cell(2, 5, ':', 0, 0, 'R');
        $this->SetFont('Arial','B',8);
        //$this->Cell(2, 5, utf8_decode($rowPaso['asunto_origen']), 0, 1, 'J');
        $this->MultiCell(0,4,strtoupper(utf8_decode($cab[0]['asunto'])),0,'J');*/
        $this->Ln(4);
        
        //FECHA DE EMISION
        $this->SetFont('Arial','',7);
        $this->Cell(14, 5, 'FECHA', 0, 0, 'L');
        $this->Cell(2, 5, ':', 0, 0, 'R');
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 5, utf8_decode($cab[0]['fecha']), 0, 0, 'L');
        $this->SetFont('Courier','',6);
        $this->Cell(0, 5, $correlativo, 0, 1, 'R');
        $this->Cell(0,0,'',1,1,'C'); 
        $this->Ln(4);

        $this->SetFont('Arial','',9);
        $this->MultiCell(0,4,utf8_decode($cab[0]['problema']),0,'J');
    } 

    function Footer()
    {
         $this->SetY(-10);
        $this->SetFont('Arial','',6);
        $this->Cell(0, 4, utf8_decode('Prohibida la Reproducción Total o Parcial de este documento sin la autorización del Representante de la Dirección.'), 0, 1, 'C');
       
    }

    
}

//$nombre = $cabecera[0]['nombres'];
//print_r( $cab);
$pdf= new PDF('P','mm', 'A5');
$pdf->SetAutoPageBreak(1 ,0.5);
//$pdf->SetTitle($title);
$pdf->SetTitle(':: CMSM TRAMITE DOCUMENTARIO ::');
//$pdf->SetMargins(2,3,7);
$pdf->AliasNbPages();
$pdf->AddPage('P','A5');
$pdf->AliasNbPages();
//$pdf->AddPage();
$pdf->cuerpo($cab);
//$pdf->Header($cab);
$pdf->Output();	

//$pdf->Output($ruta, 'F');

?>