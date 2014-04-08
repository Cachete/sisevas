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

        $this->Rect(14, 9 ,181, $h+2);
        $this->Image('../web/images/logo.jpg',18,10,30,$h);
        $this->MultiCellp(10, $h, '', 0, 'C', false);

        $this->MultiCell(1, $h, '', 0, 'C', false);                
        $nh = $this->nLineaBreak(20, $h, 'SAPOSOA', 'C');
        $this->MultiCell(20, $h/$nh, '', 0, 'C');                

        $this->MultiCellp(1, $h, '', 0, 'C', false);
        $nh = $this->nLineaBreak(75, $h, $_SESSION['empresa'], 'C');
        $this->MultiCell(75, $h/$nh, 'CARPINTERIA ROMERO E.I.R.L', 0, 'C', true);

        $this->SetFillColor(208, 217, 218);
        $this->SetFont('Arial','B',8);
        $this->MultiCell(1, $h, '', 0, 'C', false);                
        $nh = $this->nLineaBreak(30, $h, 'PROVINCIA DE TARAPOTO', 'C');
        $this->MultiCell(30, $h/$nh, 'PROVINCIA DE TARAPOTO', 0, 'C', true);                

        $this->SetFillColor(208, 217, 218);
        $this->MultiCell(1, $h, '', 0, 'C', false);                
        $nh = $this->nLineaBreak(30, $h, utf8_decode('REGION SAN MARTIN PERÚ'), 'C');
        $this->MultiCell(30, $h/$nh, utf8_decode('REGION SAN MARTIN PERÚ'), 0, 'C', true);                

        $this->Ln(15);
       
    }
    
    function cuerpo($cabecera,$detalle,$IdProforma,$FechaPro)
    {
        $nrorecibo= $cabecera[0]['serie'].' - '.$cabecera[0]['numero'];
        $this->Ln(10);
        $this->SetFont('Arial','B',8);
	    $this->Cell(18,5,utf8_decode('SEÑOR (A):'),0,0,'L');
        $this->Cell(55,5,utf8_decode(strtoupper($cabecera[0]['nomcliente'])),0,0,'L');
        
        $this->Cell(16,5,utf8_decode('RUC / DNI:'),0,0,'R');
        $this->Cell(25,5,utf8_decode($cabecera[0]['dni']),0,0,'L');
        
        $this->Cell(16,5,utf8_decode('N° RECIBO:'),0,0,'R');
        $this->Cell(0,5,$nrorecibo,0,1,'L');
        //2° linea
        $this->Cell(18,5,utf8_decode('DIRECCION:'),0,0,'L');
        $this->Cell(55,5,utf8_decode(strtoupper($cabecera[0]['direccion'])),0,0,'L');
        
        $this->Cell(16,5,utf8_decode('FECHA:'),0,0,'R');
        $this->Cell(0,5,utf8_decode($cabecera[0]['fecha']),0,1,'L');
        $this->Ln(5);

        $this->Cell(10,5,utf8_decode('ITEM'),1,0,'L');
        $this->Cell(20,5,utf8_decode('TIPO PAGO'),1,0,'L');
        $this->Cell(50,5,utf8_decode('PRODUCTO'),1,0,'L');
        $this->Cell(18,5,utf8_decode('CANTIDAD'),1,0,'L');
        $this->Cell(15,5,utf8_decode('PRECIO'),1,0,'L');
        $this->Cell(15,5,utf8_decode('INICIAL'),1,0,'L');
        $this->Cell(15,5,utf8_decode('N° MESES'),1,0,'L');
        $this->Cell(15,5,utf8_decode('CUOTA'),1,0,'L');
        $this->Cell(20,5,utf8_decode('IMPORTE'),1,1,'L');

        $h = 6;
        $fill = false;
        $border = 'LB';
        $this->SetFont('Arial','',8);
        $this->SetLineWidth(.1);
        $this->SetTextColor(0);
        $cont = 0;

        $total=0;
        foreach($detalle as $rows)
        {
            $cont ++;
            $tipo = $rows['tipo'];
            $cant= $rows['cantidad'];
            $pre= $rows['preciocash'];
            $ini= $rows['inicial'];
            $cuo= $rows['cuota'];
            $nro= $rows['nromeses'];
            
            $this->Cell(10, $h, $cont, $border, 0, 'C', $fill);
            $this->Cell(20, $h, $rows['descripcion'], $border, 'C', $fill);
            $this->Cell(50, $h, $rows['producto'], $border, 'C', $fill);
            $this->Cell(18, $h, $rows['cantidad'], $border, 'C', $fill);
            $this->Cell(15, $h, $rows['preciocash'], $border, 'C', $fill);
            $this->Cell(15, $h, $rows['inicial'], $border, 'C', $fill);
            $this->Cell(15, $h, $rows['nromeses'], $border, 'C', $fill);
            $this->Cell(15, $h, $rows['cuota'], $border, 'C', $fill);
            
            if($tipo==1)
            {
                $sub= $pre * $cant;
                $this->Cell(20, $h, $sub , 1, 1,'R', $fill);
            }else
                {
                    $sub= ($cuo * $nro) + $ini;
                    $this->Cell(20, $h, $sub , 1, 1,'R', $fill);
                }
            $total=$total+$sub;
        }
        $this->Cell(158,5,utf8_decode('TOTAL'),1,0,'R', $fill);
        $this->Cell(20,5,$total,1,1,'R', $fill);
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

$nombre = $cabecera[0]['nombres'];
$IdProforma = $_GET['id'];
//print_r($detalle);
//print_r($cabecera); die;

$FechaPro= $_GET['fecha'];

$pdf = new PDF();
$pdf->SetTitle($title);
$pdf->SetMargins(15,10,20);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->cuerpo($cabecera,$detalle,$IdProforma,$FechaPro);
$pdf->Output();	

//$pdf->Output($ruta, 'F');

?>