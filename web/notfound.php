<!DOCTYPE html>
<html>
    <head>
        <title>Mensaje</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body style="background: #fafafa">
        <p style="width: 100%; text-align: center; margin-top: 100px;font-style: italic;">
            No se encontro el archivo especificado  "<?php echo $_GET['f'] ?>". 
            <br/>
            <?php $desde = $_SERVER['HTTP_REFERER']; ?>
            <a href="<?php echo $desde; ?>">Regresar</a>
        </p>
    </body>
</html>