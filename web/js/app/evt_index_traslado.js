$(function() 
{	
	$("#list").on('click','.anular',function(){
		var i = $(this).attr("id");
		i = i.split('-');
		i = i[1];
		if(confirm('Realmente deseas anular este Traslado con codigo '+i+'?'))
		{			
			$.post('index.php','controller=traslado&action=anular&i='+i,function(r)
			{
				if(r[0]==1)	gridReload();
					else alert('Ha ocurrido un error, vuelve a intentarlo.');
			},'json');
		}
	});
	
});