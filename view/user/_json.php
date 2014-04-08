<?php
$result = array();
foreach ($value as $key => $val) {
      array_push($result, array(	
                                "id"=>$val['idempleado'], 
                                "name"=>strtoupper($val['nombre']),                                
                                 "alias" => strtoupper($val['aleas'])
                            )
      			);
      if ( $key > 7 ) { break; }
}
print_r(json_encode($result));

?>