$.fn.DataTable.ext.pager.simple_numbers_no_ellipses = function(page, pages){
    var numbers = [];
    var buttons = $.fn.DataTable.ext.pager.numbers_length;
    var half = Math.floor( buttons / 2 );
  
    var _range = function ( len, start ){
       var end;
     
       if ( typeof start === "undefined" ){
          start = 0;
          end = len;
  
       } else {
          end = start;
          start = len;
       }
  
       var out = [];
       for ( var i = start ; i < end; i++ ){ out.push(i); }
     
       return out;
    };
      
  
    if ( pages <= buttons ) {
       numbers = _range( 0, pages );
  
    } else if ( page <= half ) {
       numbers = _range( 0, buttons);
  
    } else if ( page >= pages - 1 - half ) {
       numbers = _range( pages - buttons, pages );
  
    } else {
       numbers = _range( page - half, page + half + 1);
    }

    /*
    
    if(localStorage.getItem('contact_page') !== null){
        var pushNumber = parseInt(localStorage.getItem('contact_page')) - 1;
        numbers.push(pushNumber);
    }else if(localStorage.getItem('deal_page') !== null){
         var pushNumber = parseInt(localStorage.getItem('deal_page')) - 1;
         numbers.push(pushNumber);
    }else if(localStorage.getItem('user_page') !== null){
         var pushNumber = parseInt(localStorage.getItem('user_page')) - 1;
         numbers.push(pushNumber);
    }else if(localStorage.getItem('company_page') !== null){
         var pushNumber = parseInt(localStorage.getItem('company_page')) - 1;
         numbers.push(pushNumber);
    }else{
        numbers = numbers;
    }
        */
    
    var myNumbers = numbers.filter(function(elem, index, self) {
       return index === self.indexOf(elem);
   });
   numbers = myNumbers;
   numbers.DT_el = 'span';
    return [ 'previous', numbers, 'next' ];
 };