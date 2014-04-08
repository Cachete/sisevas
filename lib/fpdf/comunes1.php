<?

    /*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	Autores: Pedro Obreg� Mej�s
			 Rub� D. Mancera Mor�
	Versi�: 1.0
	Fecha Liberaci� del c�igo: 13/07/2004
	Galop� para gnuLinEx 2004 -- Extremadura

	*/
class PDF extends FPDF
{
	//Cabecera de p�ina
	function Header()
	{
		//Logo
		/*$this->Image('../img/logo.jpg',6,4,18);
		$this->Ln(6);
		$this->SetFont('Arial','',8);
		$this->SetY(4);
		$this->SetX(24);
		$this->Cell(60,4,'UNIVERSIDAD NACIONAL DE SAN MARTIN',0,0,'C');
		$this->Ln();
		$this->SetX(24);
		$this->Cell(60,4,'UNIDAD DE TESORERIA',0,0,'C');
		$this->Ln();
		$this->SetX(24);
		$this->Cell(60,4,'TARAPOTO',0,0,'C');*/
	}

	//Pie de p�ina
	function Footer()
	{
		//Posici�: a 1,5 cm del final
		$this->SetY(-21);
		//Arial italic 8
		$this->SetFont('Arial','',8);
		//Nmero de p�ina
		$this->Cell(0,10,'',0,0,'R');

		//Posici�: a 1,5 cm del final
		$this->SetY(-18);
		//Arial italic 8
		$this->SetFont('Arial','',8);
		//Nmero de p�ina
		$this->Cell(0,10,'',0,0,'R');

		//Posici�: a 1,5 cm del final
		$this->SetY(-12);
		//Arial italic 8
		$this->SetFont('Arial','',8);
		//Nmero de p�ina
		$this->Cell(0,10,'Pag.'.$this->PageNo()." de {nb}",0,0,'R');
	}
}
?>
