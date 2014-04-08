$(function() 
{	
	$("#list").on('click','.anular',function(){
		var i = $(this).attr("id");
		i = i.split('-');
		i = i[1];
		if(confirm('Realmente deseas anular este acabado con codigo '+i+'?'))
		{			
			$.post('index.php','controller=acabado&action=anular&i='+i,function(r)
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
		if(confirm('Realmente deseas dar por finalizado este acabado con codigo '+i+'?'))
		{			
			$.post('index.php','controller=acabado&action=end&i='+i,function(r)
			{
				if(r[0]==1)	gridReload();
					else alert('Ha ocurrido un error, vuelve a intentarlo.');
			},'json');
		}
	});	
});