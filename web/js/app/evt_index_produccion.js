$(function() 
{	
	$("#list").on('click','.anular',function(){
		var i = $(this).attr("id");
		i = i.split('-');
		i = i[1];
		if(confirm('Realmente deseas anular esta produccion con codigo '+i+'?'))
		{			
			$.post('index.php','controller=produccion&action=anular&i='+i,function(r)
			{
				if(r[0]==1)	gridReload();
					else alert('Ha ocurrido un error, vuelve a intentarlo.');
			},'json');
		}
	});

	$("#list").on('click','.boton-hand',function(){
		var i = $(this).attr("id");
		i = i.split('-');
		i = i[1];
		if(confirm('Realmente deseas dar por finalizado esta produccion con codigo '+i+'?'))
		{			
			$.post('index.php','controller=produccion&action=end&i='+i,function(r)
			{
				if(r[0]==1)	gridReload();
					else alert('Ha ocurrido un error, vuelve a intentarlo.');
			},'json');
		}
	});	
});