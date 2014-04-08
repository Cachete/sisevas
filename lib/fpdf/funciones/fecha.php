<HTML>
<HEAD><TITLE></TITLE>
</HEAD>
<BODY>
	<?
		include("./librerias/conectar_bd.inc");

		function reconversion($fecha)
		{
			$tok = strtok ($fecha,"/");

			if ($tok!="$fecha")
			{
				$i=0;
				while ($tok) {
				$fecha10[$i]=$tok;
				$tok = strtok ("/");
					$i++;
				}
				$d=$fecha10[0];
				$m=$fecha10[1];
				$a=$fecha10[2];

				if ($d<'10')
				{
					if (strpos($d,"0")!="")
						$d="0".$d;
				}
				if ($m<'10')
				{
					if (strpos($m,"0")!="")
						$m="0".$m;
				}
				if ($a<'10')
					$a="20".$a;
				else
				{
					if ($a<'100')
						$a="19".$a;
				}
				$fecha=$a."-".$m."-".$d;
			}
			return $fecha;
		}
		$sql="select cod_modular,fecha_resolucion from encargaturas";
		$re_alfa=mysql_query($sql,$conexion);
		for ($i=0;$i<mysql_num_rows($re_alfa);$i++)
		{
			$codmod=mysql_result($re_alfa,$i,'cod_modular');
			$fechasu=reconversion(mysql_result($re_alfa,$i,'fecha_resolucion'));
			$sql="UPDATE encargaturas SET fecha_resolucion='$fechasu' WHERE cod_modular='$codmod'";
			mysql_query($sql,$conexion);
		}
		echo "todo bien".$i;
	?>
</BODY>
</HTML>