<?php
session_start();
require("../lib/fpdf/fpdf.php");
class PDF extends FPDF
{    
    function Header()
    {
        global $conexion,$idtramite;
        $codigo= $row['codigo'];
        $tpdoc= $row['descripcion'];

        $this->Line(7, 25,202,25);        
        //$this->Image('../../images/logos.jpg',15,9,30,12);
        $this->SetFont('Arial','B',10); 
        $this->SetXY(50, 27);
        //$this->Cell(0,5,'MANUAL DE BUENAS PRÁCTICAS DE MANUFACTURA Y GESTIÓN',1,1,'L');
        $this->MultiCell(125,4,utf8_decode('MANUAL DE BUENAS PRÁCTICAS DE MANUFACTURA Y GESTIÓN'),0,'C');
        $this->SetXY(50, 32);
        $this->SetFont('Arial','',8);
        $this->Cell(125,4,strtoupper($tpdoc),0,0,'C');
        $this->SetXY(175, 29);
        $this->SetFont('Arial','I',7);
        $this->Cell(12,4,'CODIGO :',0,0,'L');
        $this->Cell(0,4,$cab[0]['codigo'],0,1,'L');
        $this->SetXY(175, 32);
        $this->Cell(12,4,'VERSION :',0,0,'L');
        $this->Cell(0,4,'1.0',0,1,'L');
        $this->Line(7,40,202, 40);            
        
        $this->Ln(10);       
    }

    function cuerpo($cab,$rowsc)
    {
        global $conexion,$idtramite;
        
        /* Primer rectangulo */
        $this->Rect(7, 50, 195, 6, 'D');
        $this->SetXY(9, 51);
        $this->SetFont('Arial','',9);
        $this->Cell(0,4,'INFORME DE RECLAMOS',0,0,'C');

        /* Segundo Rectangulo */
        $this->Rect(7, 56, 195, 15, 'D');
        $this->SetXY(7, 56);
        $this->Cell(80,15,'OFICINA DE CALIDAD',1,0,'C');

            $this->SetXY(87, 56);
            $this->Cell(30,5,utf8_decode('N° Informe'),1,0,'L');
            $this->Cell(85,5,$cab[0]['codigo'],1,0,'L');
            
            $this->SetXY(87, 61);
            $this->Cell(30,5,utf8_decode('Fecha'),1,0,'L');
            $this->Cell(85,5,$cab[0]['fechainicio'],1,0,'L');

            $this->SetXY(87, 66);
            $this->Cell(30,5,utf8_decode('Hora'),1,0,'L');
            $this->Cell(85,5,$cab[0]['horainicio'],1,0,'L');
        
        /* TERCER RECTANGULO */
        $this->Rect(7, 71, 195, 85,'D');
        $this->SetXY(9, 72);
        
        $this->Cell(5,5,'1.- ',0,0,'L');
        $this->SetFont('Arial','U',8);
        $this->Cell(80,5,'DATOS DEL CLIENTE :',0,1,'L');

            $this->SetXY(14, 78);
            $this->SetFont('Arial','',8);
            $this->Cell(25,5,utf8_decode('NOMBRE'),0,0,'L');
            $this->SetFont('Arial','',9);
            $this->Cell(85,5,': '.$cab[0]['paciente'],0,1,'L');

            $this->SetXY(14, 83);
            $this->SetFont('Arial','',8);
            $this->Cell(25,5,utf8_decode('DOMICILIO'),0,0,'L');
            $this->SetFont('Arial','',9);
            $this->Cell(85,5,': '.$cab[0]['direccion'],0,1,'L');

            $this->SetXY(14, 89);
            $this->SetFont('Arial','',8);
            $this->Cell(25,5,utf8_decode('DNI'),0,0,'L');
            $this->SetFont('Arial','',9);
            $this->Cell(85,5,': '.$cab[0]['nrodocumento'],0,1,'L');
        
        $this->SetXY(9, 98);
        
        $this->Cell(5,5,'2.- ',0,0,'L');
        $this->SetFont('Arial','U',8);
        $this->Cell(80,5,'DESCRIPCION :',0,1,'L');

            $this->SetXY(14, 104);
            $this->SetFont('Arial','',10);
            //$this->Cell(25,5,utf8_decode($row['problema']),0,0,'L');
            $this->MultiCell(185,4,utf8_decode($cab[0]['problema']),0,'J');

        /* CUARTO RECTANGULO */
        $this->Rect(7, 156, 195, 8, 'D');
        $this->SetFont('Arial','',9);
        $this->SetXY(9, 158);
        $this->Cell(25,5,utf8_decode('Área o Servicio :'),0,0,'L');
        if ($cab[0]['idtipo_problema']==1) {
            
            $this->Cell(40,5,$cab[0]['areai'],0,0,'L');

        }else
            {
                $this->Cell(40,5,$cab[0]['remitente'],0,0,'L');

            }
            
        
        $this->SetXY(120, 158);
        $this->Cell(22,5,utf8_decode('Fecha y Firma'),0,0,'L');

        /* QUINTO RECTANGULO */
        $this->Rect(7, 164, 195, 60, 'D');
        $this->SetXY(9, 166);
        $this->Cell(5,5,'3.- ',0,0,'L');
        $this->SetFont('Arial','U',8);
        $this->Cell(80,5,utf8_decode('RESULTADOS DE LA INVESTIGACIÓN REALIZADA :'),0,1,'L');

            $this->SetXY(14, 171);
            $this->SetFont('Arial','',10);
            //$this->Cell(25,5,utf8_decode($row['resultados']),1,1,'L');
            $this->MultiCell(185,4,utf8_decode($cab[0]['resultados']),0,'J');
          
        /*SEXTO RECTANGULO*/
        $this->Rect(7, 224, 195, 8, 'D');
        $this->SetXY(9, 226);
        $this->SetFont('Arial','',9);
        $this->Cell(25,5,utf8_decode('Área o Servicio :'),0,0,'L');
        $this->Cell(40,5,$cab[0]['destinatario'],0,0,'L');
        
        $this->SetXY(120, 226);
        $this->Cell(22,5,utf8_decode('Fecha y Firma'),0,0,'L');

        /* SEPTIMO RECTANGULO */
        $this->Rect(7, 232, 195, 30, 'D');
        $this->SetXY(9, 235);
        $this->Cell(5,5,'4.- ',0,0,'L');
        $this->SetFont('Arial','U',8);
        $this->Cell(80,5,utf8_decode('CIERRE DE LA RECLAMACIÓN :'),0,1,'L');

            $this->SetXY(14, 240);
            $this->SetFont('Arial','',9);            
            
            foreach( $rowsc as $asigna=>$var )
                {
                    //echo $cab[0]['idcierre'];
                    if ($var['idcierre']== $cab[0]['idcierre']) {
                        $this->Cell(6,5,utf8_decode('[ X ]'),0,0,'L');
                        $this->Cell(26,5,utf8_decode($var[1]),0,0,'L');
                    }
                    else
                        {
                            $this->Cell(6,5,utf8_decode('[   ]'),0,0,'L');
                            $this->Cell(26,5,utf8_decode($var[1]),0,0,'L');
                        }
                }
        /**/
        $this->Rect(7, 262, 195, 8, 'D');
        $this->SetXY(7, 262);
        $this->Cell(50,8,utf8_decode('Fecha : ').$cab[0]['fechainicio'],1,0,'L');
        //$this->Cell(40,8,': OFICINA DE CALIDAD',1,0,'L');
        
        //$this->SetXY(120, 237);
        $this->Cell(22,8,utf8_decode('FIRMA DE GERENCIA GENERAL :'),0,0,'L');
    }

    function Footer()
    {
        global $conexion;        
        $this->SetY(-10);
        $this->SetFont('Arial','',7);
        $this->Cell(0, 4, utf8_decode('Prohibida la Reproducción Total o Parcial de este documento sin la autorización del Representante de la Dirección.'), 0, 1, 'C');
       

    }
    
}

//print_r($cab);
$pdf= new PDF('P','mm', 'A4');
$pdf->SetAutoPageBreak(1 ,0.5);
//$pdf->SetTitle($title);
$pdf->SetTitle(':: CMSM TRAMITE DOCUMENTARIO ::');
//$pdf->SetMargins(2,3,7);
$pdf->AliasNbPages();
$pdf->AddPage('P','A4');
//$pdf->cabecera($idtramite);
$pdf->header($cab);
$pdf->cuerpo($cab,$rowsc);
//$pdf->Footer($idtramite);
$pdf->Output();

?>
