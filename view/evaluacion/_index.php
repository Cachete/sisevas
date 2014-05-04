
<script type="text/javascript">
	$(document).ready(function(){
		$("#idcompetencia").addClass('cmb-c');
		$("#idcompetencia").change(function(){
			loadAspectos();
		});
		$("#save_as").click(function(){
			var v = new Array();
			$('form').each(function(i,j){
				var name_all = $(this).attr("id"),
					name = name_all.replace('form','r-');									
				valor = $("input[name='"+name+"']:checked").val();
				if(typeof(valor)!="undefined")
					v.push(valor);
			});
			var sendv = json_encode(v),
				idp   = $("#idpersonal").val();
			$.post('index.php','controller=evaluacion&action=save&v='+sendv+'&idp='+idp,function(data){
				if(data[0]=='1')
                	alert("Se ha grabado los cambios satisfactoriamente.");
                else
                	alert("Ha ocurrido un error, intentelo nuevamente.");
			},'json')
		});
	})
	function loadAspectos()
	{
		var idc = $("#idcompetencia").val(),
			idp = $("#idpersonal").val();
		if(idc!="")
		{
			clearSecctions();
			$.get('index.php','controller=evaluacion&action=getAspectos&idc='+idc+'&idp='+idp,function(data){				
				$(".container").append(data);
			});
		}
	}
	function clearSecctions(){$('section').remove();}

	
	//Funciones del svgcheckbx.js
	function draw( el, type ) 
	{
		var paths = [], pathDef, 
			animDef,
			svg = el.parentNode.querySelector( 'svg' );

		switch( type ) 
		{
			case 'cross': pathDef = pathDefs.cross; animDef = animDefs.cross; break;
			case 'fill': pathDef = pathDefs.fill; animDef = animDefs.fill; break;
			case 'checkmark': pathDef = pathDefs.checkmark; animDef = animDefs.checkmark; break;
			case 'circle': pathDef = pathDefs.circle; animDef = animDefs.circle; break;
			case 'boxfill': pathDef = pathDefs.boxfill; animDef = animDefs.boxfill; break;
			case 'swirl': pathDef = pathDefs.swirl; animDef = animDefs.swirl; break;
			case 'diagonal': pathDef = pathDefs.diagonal; animDef = animDefs.diagonal; break;
			case 'list': pathDef = pathDefs.list; animDef = animDefs.list; break;
		};
		
		paths.push( document.createElementNS('http://www.w3.org/2000/svg', 'path' ) );

		if( type === 'cross' || type === 'list' ) {
			paths.push( document.createElementNS('http://www.w3.org/2000/svg', 'path' ) );
		}
		
		for( var i = 0, len = paths.length; i < len; ++i ) {
			var path = paths[i];
			svg.appendChild( path );

			path.setAttributeNS( null, 'd', pathDef[i] );

			var length = path.getTotalLength();
			// Clear any previous transition
			//path.style.transition = path.style.WebkitTransition = path.style.MozTransition = 'none';
			// Set up the starting positions
			path.style.strokeDasharray = length + ' ' + length;
			if( i === 0 ) {
				path.style.strokeDashoffset = Math.floor( length ) - 1;
			}
			else path.style.strokeDashoffset = length;
			// Trigger a layout so styles are calculated & the browser
			// picks up the starting position before animating
			path.getBoundingClientRect();
			// Define our transition
			path.style.transition = path.style.WebkitTransition = path.style.MozTransition  = 'stroke-dashoffset ' + animDef.speed + 's ' + animDef.easing + ' ' + i * animDef.speed + 's';
			// Go!
			path.style.strokeDashoffset = '0';
		}
	}

	function reset( el ) {
		Array.prototype.slice.call( el.parentNode.querySelectorAll( 'svg > path' ) ).forEach( function( el ) { el.parentNode.removeChild( el ); } );
	}

	function resetRadio( el ) {
		Array.prototype.slice.call( document.querySelectorAll( 'input[type="radio"][name="' + el.getAttribute( 'name' ) + '"]' ) ).forEach( function( el ) { 
			var path = el.parentNode.querySelector( 'svg > path' );
			if( path ) {
				path.parentNode.removeChild( path );
			}
		} );
	}

</script>
<div class="container">
	<div class="codrops-top clearfix" style="background:#4D4D4C">
		<a class="codrops-icon codrops-icon-prev" href="#"><span>Regresar</span></a>
		<span class="right"><a class="codrops-icon codrops-icon-drop" href="#"><span>Ficha de Evaluacion de Desempeño del Personal - 2014-I</span></a></span>
	</div>
	<header>
		<input type="hidden" name="idpersonal" id="idpersonal" value="<?php echo $rows->idpersonal; ?>" />
		<h1><?php echo $rows->nombres." ".$rows->apellidos; ?>
			<span><?php echo strtoupper($rows->consult); ?></span>
		</h1>
		<h1>
			<span>
				<?php 
					echo $competencias;
				?>
			</span>
		</h1>
		<a href="#" id="save_as">Save as :D</a>
	</header>		
</div>