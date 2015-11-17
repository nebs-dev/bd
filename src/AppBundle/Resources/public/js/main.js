$(function(){
   $('.availabilitySelect').each(function(item){
      if($(this).val() != 1) {
         $(this).parent().parent().find('.priceDiv').hide();
      }
   });

   $('.availabilitySelect').on('change', function (e) {
      switch ($(this).val()) {
         // no
         case '0':
            $(this).parent().parent().find('.priceDiv :input').val(0);
            $(this).parent().parent().find('.priceDiv').hide();
            break;
         // paid
         case '1':
            $(this).parent().parent().find('.priceDiv').show();
            $(this).parent().parent().find('.priceDiv :input').val(1);
            break;
         // free
         case '2':
            $(this).parent().parent().find('.priceDiv :input').val(0);
            $(this).parent().parent().find('.priceDiv').hide();
            break;
      }
   });

});