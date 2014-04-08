/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
(function( $ ){

  $.fn.validateradiobutton = function() {
    var nr = 0;
    var id = $(this).attr('id');
    $( ".error-bs" ).remove();
    $.each( $("#" + id + " input:radio"),function(){
        if($(this).attr("checked")){
            nr = nr + 1;
    }
    });
    if ( nr == 1){
        $( ".error-bs" ).remove();
        return true;
        
    }else {
       $( this ).append('<b class="error-bs" style="color:red;">*</b>');
       $("#" + id + " > input:radio").focus();
       return false;
       
    }
  };
})( jQuery );

