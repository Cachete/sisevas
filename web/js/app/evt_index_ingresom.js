$(function() 
{	
	$("#list").on('click','.anular',function(){
		if(confirm('Realmente deseas anular este ingreso?'))
		{
			var i = $(this).attr("id");
			i = i.split('-');
			i = i[1];
			$.post('index.php','controller=ingresom&action=anular&i='+i,function(r)
			{
				if(r[0]==1)	gridReload();
					else alert('Ha ocurrido un error, vuelve a intentarlo.');
			},'json');
		}
	});
});