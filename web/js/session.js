/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
(function( $ ){    
    $(function(){
        $('body').ajaxSuccess(function(event, request, settings) {
            if (request.getResponseHeader('NOT_AUTHORIZED') === '499') {
                alert("Sesión terminada");
                window.location = 'login.php';
            }
        });
    });
})( jQuery );

