<?php
$result = array();
foreach ($value as $key => $val) {
      array_push($result, array(	
                                "id"=>$val['idpasajero'], 
                                "nrodocumento"=>$val['nrodocumento'],
                                "nombre"=>  strtoupper($val['nombre']),
                                 "direccion" => strtoupper($val['direccion'])
                            )
      			);
      if ( $key > 7 ) { break; }
}
print_r(json_encode($result));

?>