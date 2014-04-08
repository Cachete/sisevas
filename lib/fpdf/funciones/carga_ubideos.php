<html>
<body>
<?php

	$base="temporal";
	$base_p="base_escalafon";
	$conexion=mysql_connect("localhost","root");			
	mysql_select_db($base,$conexion);
	$tabla="ubigeo_final";
	$consulta="SELECT * FROM $tabla WHERE (cod_provincia='00') and (cod_distrito='00')";
	$resultado=mysql_query($consulta,$conexion);	
	$veces=mysql_num_rows($resultado);
	for ($i=0; $i< $veces; $i++)
	{
		$codigo[$i]=mysql_result($resultado,$i,'cod_departamento');
		$descripcion[$i]=mysql_result($resultado,$i,'descripcion');				
	}
	$tabla2="departamentos";	
	mysql_select_db($base_p,$conexion);	
	for ($i=0; $i< $veces; $i++)
	{		
		$sql="INSERT INTO $tabla2 (cod_departamento, descripcion) VALUES ('$codigo[$i]','$descripcion[$i]')";
		mysql_query($sql,$conexion);
	}
	mysql_select_db($base,$conexion);
	$consulta="SELECT * FROM $tabla WHERE cod_provincia<>'00' and cod_distrito='00'";
	$resultado=mysql_query($consulta,$conexion);
	$veces=mysql_num_rows($resultado);		
	for ($i=0; $i< $veces; $i++)
	{
		$codigod=mysql_result($resultado,$i,'cod_departamento');
		$codigop=mysql_result($resultado,$i,'cod_provincia');
		$codigo[$i]=$codigod.$codigop;
		$descripcion[$i]=mysql_result($resultado,$i,'descripcion');
	}
	$tabla2="provincias";
	mysql_select_db($base_p,$conexion);
	for ($i=0; $i< $veces; $i++)
	{		
		$sql="INSERT INTO $tabla2 (cod_provincia, descripcion) VALUES ('$codigo[$i]','$descripcion[$i]')";
		mysql_query($sql,$conexion);
	}
	mysql_select_db($base,$conexion);
	$consulta="SELECT * FROM $tabla WHERE cod_provincia<>'00' and cod_distrito<>'00'";
	$resultado=mysql_query($consulta,$conexion);
	$veces=mysql_num_rows($resultado);		
	for ($i=0; $i< $veces; $i++)
	{
		$codigot=mysql_result($resultado,$i,'cod_distrito');
		$codigod=mysql_result($resultado,$i,'cod_departamento');
		$codigop=mysql_result($resultado,$i,'cod_provincia');
		$codigo[$i]=$codigod.$codigop.$codigot;
		$descripcion[$i]=mysql_result($resultado,$i,'descripcion');				
	}
	$tabla2="distritos";
	mysql_select_db($base_p,$conexion);
	for ($i=0; $i< $veces; $i++)
	{			
		$sql="INSERT INTO $tabla2 (cod_distrito, descripcion) VALUES ('$codigo[$i]','$descripcion[$i]')";
		mysql_query($sql,$conexion);
	}
?>

</body>
</html>