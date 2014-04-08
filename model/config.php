<?php
include_once("../lib/class.upload.php");
include_once("Main.php");
class config extends Main
{   
    function edit($id ) {
        $stmt = $this->db->prepare("SELECT * FROM config WHERE idconfig = 1");
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject();
    }
    public function getEmpresa()
    {
        $stmt = $this->db->prepare("SELECT * FROM config WHERE idconfig = 1");
        $stmt->bindParam(':id', $id , PDO::PARAM_STR);
        $stmt->execute();
        $obj = $stmt->fetchObject();
        return $obj->descripcion;
    }
    function update($_P ) 
    {
        $extension=$_FILES['logo']['type'];
        $ext = explode("/",$extension);
        $ext = $ext[1];        
        if($ext=="jpeg")
        {
            $ext = "jpg";
        }
        if($extension!="")        
        {
            $name_foto="logo_".date('ymdHis');        
            $fo = new Upload($_FILES['logo']);
            if ($fo->uploaded)
            {
                $fo->file_new_name_body = $name_foto;
                $fo->Process('config/logo/');
                if($fo->processed){$subio=true;$fo->clean();}
                else { $subio=false; }
                
//                $fo->file_new_name_body = $name_foto."_2";
//                $fo->image_resize = true;                
//                $fo->image_convert = jpg;
//                $fo->image_background_color = '#FFFFFF';
//                $fo->image_y = 270;
//                $fo->image_ratio_x = true;
//                $fo->Process('config/logo/');
//                if($fo->processed){$subio=true;}
//                 else { $subio=false; }
            }
            $file_photo=$name_foto.".".$ext;
        }
        else {$subio=true; $file_photo = $_P['f'];}   
        
        if($subio)
        {
            $stmt = $this->db->prepare("update config set descripcion = :p1, logo = :p2 where idconfig = 1");
            $stmt->bindParam(':p1', $_P['descripcion'] , PDO::PARAM_STR);
            $stmt->bindParam(':p2', $file_photo , PDO::PARAM_STR);            
            $p1 = $stmt->execute();
            $p2 = $stmt->errorInfo();
            return array($p1 , $p2[2]);
        }
        else { return array(false,'Error al subir el logo');}
        
    }
    
}
?>