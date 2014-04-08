$(document).ready(function() {           
  $(document).on('click','.paginator',function(){           
      $.get('index.php?controller=modulo&action=index&q=&p=2','&',function(data){
          alert(data);
      });
  })
});
//document.oncontextmenu = function(){ return false; }