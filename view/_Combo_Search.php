<select name="criterio" id="criterio" class="text ui-widget-content ui-corner-all" style="width: 20% ">    
    <?php         
        foreach ($options as $key => $value) { 
            $select = "";
            if($_GET['criterio']==$key) {$select = "selected";}                
       ?>        
        <option value="<?php echo $key; ?>" <?php echo $select; ?> > <?php echo strtoupper($value) ?> </option>
    <?php } ?>
</select>