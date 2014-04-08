<?php
session_start();
require("../lib/fpdf/fpdf.php");
class PDF extends FPDF
{
    function Header()
    {
        global $title;	
        global $a;
        global $title;	
        global $a;
        $this->SetLineWidth(.1);
        $this->SetFont('Arial','B',10);
        $this->SetFillColor(208, 217, 218);
        $this->SetTextColor(0);        
        $this->SetDrawColor(208, 217, 218);
        $h = 18;

        $this->Rect(14, 9 ,255, $h+2);
        $this->Image('../web/images/logo.jpg',18,10,35,$h);
        $this->MultiCellp(10, $h, '', 0, 'C', false);

        $this->MultiCell(1, $h, '', 0, 'C', false);                
        $nh = $this->nLineaBreak(20, $h, 'SAPOSOA', 'C');
        $this->MultiCell(30, $h/$nh, '', 0, 'C');                

        $this->MultiCellp(1, $h, '', 0, 'C', false);
        $nh = $this->nLineaBreak(75, $h, $_SESSION['empresa'], 'C');
        $this->MultiCell(70, $h/$nh, 'CARPINTERIA ROMERO E.I.R.L', 0, 'C', true);

        $this->SetFillColor(208, 217, 218);
        $this->SetFont('Arial','B',8);
        $this->MultiCell(1, $h, '', 0, 'C', false);                
        $nh = $this->nLineaBreak(30, $h, 'PROVINCIA DE TARAPOTO', 'C');
        $this->MultiCell(35, $h/$nh, 'PROVINCIA DE TARAPOTO', 0, 'C', true);                

        $this->SetFillColor(208, 217, 218);
        $this->MultiCell(1, $h, '', 0, 'C', false);                
        //$nh = $this->nLineaBreak(30, $h, utf8_decode('REGION SAN MARTIN PERÚ'), 'C');
        $this->MultiCell(100, $h/$nh, utf8_decode('NUESTRA CALIDAD Y ATENSIÓN ES SÓLO SUPERADO POR NOSOTROS MISMOS'), 1, 'C', true);                

       

        $this->Ln(15);
       
    }
    
    function cuerpo($rows,$fechad,$fechah,$idpersonal)
    {
        $this->Ln(10);
        /*$nrorecibo= $cabecera[0]['serie'].' - '.$cabecera[0]['numero'];
        $this->Ln(10);
        $this->SetFont('Arial','B',8);
	    $this->Cell(18,5,utf8_decode('SEÑOR (A):'),0,0,'L');
        $this->Cell(55,5,utf8_decode(strtoupper($cabecera[0]['nomcliente'])),0,0,'L');
        
        $this->Cell(16,5,utf8_decode('RUC / DNI:'),0,0,'R');
        $this->Cell(25,5,utf8_decode($cabecera[0]['dni']),0,0,'L');
        
        $this->Cell(16,5,utf8_decode('N° RECIBO:'),0,0,'R');
        $this->Cell(0,5,$nrorecibo,0,1,'L');
        
        $this->Cell(18,5,utf8_decode('DIRECCION:'),0,0,'L');
        $this->Cell(55,5,utf8_decode(strtoupper($cabecera[0]['direccion'])),0,0,'L');
        
        $this->Cell(16,5,utf8_decode('FECHA:'),0,0,'R');
        $this->Cell(0,5,utf8_decode($cabecera[0]['fecha']),0,1,'L');
        $this->Ln(5);*/

        //$this->Cell(10,5,utf8_decode('ITEM'),1,0,'L');
        $this->Cell(40,5,utf8_decode('SUCURSAL'),1,0,'C');
        $this->Cell(20,5,utf8_decode('SERIE'),1,0,'C');
        $this->Cell(18,5,utf8_decode('NUMERO'),1,0,'C');
        $this->Cell(55,5,utf8_decode('CLIENTE'),1,0,'C');
        $this->Cell(25,5,utf8_decode('FECHA'),1,0,'C');
        $this->Cell(55,5,utf8_decode('VENDEDOR'),1,0,'C');
        $this->Cell(35,5,utf8_decode('ESTADO'),1,1,'C');
        //$this->Cell(20,5,utf8_decode('IMPORTE'),1,1,'L');

        $h = 6;
        $fill = false;
        $border = 'LB';
        $this->SetFont('Arial','',7);
        $this->SetLineWidth(.1);
        $this->SetTextColor(0);
        $cont = 0;

        $total=0;
        foreach($rows as $rowsd)
        {
            $cont ++;
                        
            //$this->Cell(10, $h, $cont, $border, 0, 'C', $fill);
            $this->Cell(40, $h, strtoupper(utf8_decode($rowsd['descripcion'])), $border, 'C', $fill);
            $this->Cell(20, $h, $rowsd['serie'], $border, 'C', $fill);
            $this->Cell(18, $h, $rowsd['numero'], $border, 'C', $fill);
            $this->Cell(55, $h, strtoupper(utf8_decode($rowsd['nomcliente'])), $border, 'C', $fill);
            $this->Cell(25, $h, $rowsd['fecha'], 1,0, 'C', $fill);
            $this->Cell(55, $h, strtoupper(utf8_decode($rowsd['vendedor'])), $border, 'C', $fill);
            $this->Cell(35, $h, $rowsd['estado'],1,1,'R', $fill);
            
            /*if($tipo==1)
            {
                $sub= $pre * $cant;
                $this->Cell(20, $h, $sub , 1, 1,'R', $fill);
            }else
                {
                    $sub= ($cuo * $nro) + $ini;
                    $this->Cell(20, $h, $sub , 1, 1,'R', $fill);
                }
            $total=$total+$sub;*/
        }
       /* $this->Cell(158,5,utf8_decode('TOTAL'),1,0,'R', $fill);
        $this->Cell(20,5,$total,1,1,'R', $fill);*/
    } 

    function Footer()
    {
        $this->SetY(-20);		
        $this->SetFont('Arial','I',8);		
        $this->SetTextColor(0);

        $this->SetLineWidth(.1);
        $this->Cell(0,.1,"",1,1,'C',true);
        $this->Ln(2);
        $this->Cell(0,10,'Pag. '.$this->PageNo().'/{nb}',0,0,'C');
    }

    
}

//$r= $rows['descripcion'];
//print_r($rows);
$fechad= $_GET['fechad'];
$fechah= $_GET['fechah'];
$idpersonal= $_GET['idpersonal'];

$pdf = new PDF('L','mm','A4');
$pdf->SetTitle($title);
$pdf->SetMargins(15,10,20);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->cuerpo($rows,$fechad,$fechah,$idpersonal);
$pdf->Output();	

//$pdf->Output($ruta, 'F');

?>