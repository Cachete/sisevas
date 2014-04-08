<form name="frm" id="frm" action="index.php" method="POST" enctype="multipart/form-data">    
    <input type="hidden" name="controller" value="produccion" />
    <input type="hidden" name="action" value="migrar" />
    <?php echo $almacend; ?>
    <input type="file" name="archivo" id="archivo" value="" />
    <input type="submit" name="exportar" id="exportar" value="exportar" />
</form>