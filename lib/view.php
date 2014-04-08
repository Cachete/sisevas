<?php
/**
 * Description of view
 *
 * @author sophie
 */

class ViewException extends Exception{}
class View 
{
    private $data;
    private $template;
    private $layout;

    public function __construct() {}

    public function setData( $data ) 
    {
        if( !is_array( $data ) )
            throw new ViewException('$data se esperaba fuera un arreglo, se envio un ' . gettype( $data ));        
        $this->data = $data;
    }
    public function setLayout( $layout ) 
    {
        if( !file_exists( $layout ) )
            throw new ViewException("$layout  no es un archivo existente");        
        $this->layout = $layout;
    }
    public function setTemplate($template) 
    {
        if( !file_exists( $template ) )         
            throw new ViewException("$template no es un archivo existente");        
        $this->template = $template;
    }
    public function render() 
    {
        $content = $this->renderTemplate();
        include( $this->layout );
    }
    private function renderTemplate() {
        ob_start();
        @extract( $this->data, EXTR_OVERWRITE );
        include( $this->template );
        $content = ob_get_clean();
        return $content;
    }
    public function renderPartial() {
        ob_start();
        @extract( $this->data, EXTR_OVERWRITE );
        include( $this->template );
        $content = ob_get_clean();
        return $content;
    }
}
?>
