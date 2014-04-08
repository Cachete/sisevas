<HTML>
<HEAD><TITLE></TITLE>
</HEAD>
<BODY>
	<?
		include("./librerias/conectar_bd.inc");

		$sql="select * from alfa1";
		$re_alfa1=mysql_query($sql,$conexion);
		for ($i=0;$i<mysql_num_rows($re_alfa1);$i++)
		{
			$codmod=mysql_result($re_alfa1,$i,'cod_modular');

			$sql="SELECT cod_modular FROM alfa2 WHERE cod_modular='$codmod'";
			$re_buscar=mysql_query($sql,$conexion);
			if (mysql_num_rows($re_buscar)==0)
			{
				$suscripcion=mysql_result($re_alfa1,$i,'fecha_suscripcion');
				$vigencia=mysql_result($re_alfa1,$i,'fecha_vigencia');
				$afp=mysql_result($re_alfa1,$i,'cod_afp');
				$afiliacion=mysql_result($re_alfa1,$i,'codigo_afiliacion');
				$regimen=mysql_result($re_alfa1,$i,'regimen');
				$sql="INSERT INTO alfa2 (cod_modular,cod_afp,codigo_afiliacion,fecha_suscripcion,fecha_vigencia,regimen) values ('$codmod','$afp','$afiliacion','$suscripcion','$vigencia','$regimen')";
				mysql_query($sql,$conexion);
			}
		}
		echo "todo bien".$i;
	?>
</BODY>
</HTML>