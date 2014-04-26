$(function() 
{	
    $("#list").on('click', '.recepcion', function() {
        var i = $(this).attr("id");
        i = i.split('-');
        i = i[1];
        if (confirm('Realmente deseas recepcionar este documento?'))
        {
            $.post('index.php', 'controller=recepcion&action=recibir&i=' + i, function(r)
            {
                if (r[0] == 1)
                    gridReload();
                else
                    alert('Ha ocurrido un error, vuelve a intentarlo.');
            }, 'json');
        }
    });
    
    $("#list").on('click', '.printer', function() {
        var i = $(this).attr("id");
        i = i.split('-');
        id = i[1];
        td = i[2];
//        $.post('index.php', 'controller=recepcion&action=printer&i=' + i+'&t='+t, function(r)
//            {
//                if (r[0] == 1)
//                    gridReload();
//                else
//                    alert('Ha ocurrido un error, vuelve a intentarlo.');
//            }, 'json');

        if(td!= '' & td== 1)
        {
            var ventana=window.open('index.php?controller=recepcion&action=printer_mem&id='+id, 'Imprimir Proforma, resizable=no, scrollbars=yes, status=yes,location=yes'); 
            ventana.focus();
        }else
            {
                var ventana=window.open('index.php?controller=recepcion&action=printer_ot&id='+id, 'Imprimir Proforma, resizable=no, scrollbars=yes, status=yes,location=yes'); 
                ventana.focus();
            }
        
        
    });

	
});