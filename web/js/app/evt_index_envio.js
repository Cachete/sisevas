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
        idper= i[3];

        if(td== 2 || td== 3)
        {
            var ventana=window.open('index.php?controller=recepcion&action=printer_ot&id='+id+'&idper='+idper, 'scrollbars=yes, status=yes,location=yes'); 
            ventana.focus();
        }        
        
        if(td== 1)
        {
            var ventana=window.open('index.php?controller=recepcion&action=printer_mem&id='+id+'&idper='+idper, 'scrollbars=yes, status=yes,location=yes'); 
            ventana.focus();
        }
        
        if(td== 4)
        {
            var ventana=window.open('index.php?controller=recepcion&action=printer_cartfec&id='+id+'&idper='+idper, 'scrollbars=yes, status=yes,location=yes'); 
            ventana.focus();
        }
        
        if(td== 5)
        {
            var ventana=window.open('index.php?controller=recepcion&action=printer_cartcum&id='+id+'&idper='+idper, 'scrollbars=yes, status=yes,location=yes'); 
            ventana.focus();
        }
        
        
        
    });

    
});