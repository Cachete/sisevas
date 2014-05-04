<?php
	foreach ($rows as $k => $row) 
	{
		?>
		<section>
			 <form id="form<?php echo $row['idaspecto'];?>" class="ac-custom ac-radio ac-fill" autocomplete="off"> 
				<h2><?php echo $row['descripcion'] ?></h2>				
				<ul>
					<?php
						$n = count($row['parametros']);
						foreach ($row['parametros'] as $key => $r) 
						{
							if($r['idvalor']==$r['idvalorr'])
							{
							?>
								<li><input id="r<?php echo $r['idvalor'] ?>" name="r-<?php echo $row['idaspecto'] ?>" type="radio" value="<?php echo $r['idvalor'] ?>" checked="checked"><label class="labels-parametros" for="r<?php echo $r['idvalor'] ?>"><?php echo $r['parametro'] ?></label></li>								
							<?php
							}
							else
							{
							?>
								<li><input id="r<?php echo $r['idvalor'] ?>" name="r-<?php echo $row['idaspecto'] ?>" type="radio" value="<?php echo $r['idvalor'] ?>"><label class="labels-parametros" for="r<?php echo $r['idvalor'] ?>"><?php echo $r['parametro'] ?></label></li>								
							<?php
							}
						}
					?>
				</ul>
			 </form> 
		</section>
		<?php
	}
?>
<section>
	<script src="js/svgcheckbx.js"></script>	
</section>