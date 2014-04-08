$(function() 
{	
	$("#list").on('click','.anular',function(){
		var i = $(this).attr("id");
		i = i.split('-');
		i = i[1];
		if(confirm('Realmente deseas anular esta solicitud con codigo '+i+'?'))
		{			
			$.post('index.php','controller=verificacion&action=anular&i='+i,function(r)
			{
				if(r[0]==1)	gridReload();
					else alert('Ha ocurrido un error, vuelve a intentarlo.');
			},'json');
		}
	});

	$("#list").on('click','.evaluar',function(){
		var i = $(this).attr("id");
		i = i.split('-');
		i = i[1];
		/*if(confirm('Realmente deseas dar por finalizado esta produccion con codigo '+i+'?'))
		{			
			$.post('index.php','controller=produccion&action=end&i='+i,function(r)
			{
				if(r[0]==1)	gridReload();
					else alert('Ha ocurrido un error, vuelve a intentarlo.');
			},'json');
		}*/
		$.get('index.php','controller=proformas&action=load_productos&idproforma='+i,function(r){
      
	      $("#div-detalle").empty().append(r);

	    });
	});	
});