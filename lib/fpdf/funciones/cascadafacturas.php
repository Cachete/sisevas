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
	
	Autores: Pedro Obregón Mejías
			 Rubén D. Mancera Morán
	Versión: 1.0
	Fecha Liberación del código: 13/07/2004
	Galopín para gnuLinEx 2004 -- Extremadura		 
	
	*/
	
//include ("convertirfechas.php");
$fecha1=conversion($fecha1);
$fecha2=conversion($fecha2);



if (!empty($codigo))
   { $consulta = $consulta. " " . $consulta2 . " " . "and facturas.codcliente='$codigo'"; 
   if (!empty($factura))
      { $consulta = $consulta. " " . "and codfactura='$factura'";  }
} else {
if (!empty($factura))
      { $consulta = $consulta. " " .$consulta2. " " . "and codfactura='$factura'";  }
}

if ((empty($codigo)) && (empty($factura))) {
$consulta = $consulta . " " .$consulta2. " " . "and fecha >= '$fecha1' and fecha <= '$fecha2'"; 
} else {
$consulta = $consulta . " " . "and fecha >= '$fecha1' and fecha <= '$fecha2'"; 
}

?>
