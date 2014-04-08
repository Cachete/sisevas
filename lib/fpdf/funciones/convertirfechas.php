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
	
	Autores: Pedro Obreg�n Mej�as
			 Rub�n D. Mancera Mor�n
	Versi�n: 1.0
	Fecha Liberaci�n del c�digo: 13/07/2004
	Galop�n para gnuLinEx 2004 -- Extremadura		 
	
	*/
	
function conversion($fecha)
{
	$tok = strtok ($fecha,"/");
	$i=0;
	while ($tok) {
		$fecha10[$i]=$tok;
		$tok = strtok ("/");
		$i++;
	}
	$d=$fecha10[0];
	$m=$fecha10[1];
	$a=$fecha10[2];
	$fecha=$a."-".$m."-".$d;
	return $fecha;
}
?>