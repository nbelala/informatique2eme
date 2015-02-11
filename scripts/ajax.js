/**
 * Created by mohamedamine on 1/4/15.
 */
$(document).ready(function(){
  $.ajaxSetup({
    type: "POST",
    timeout: 10000,
    statusCode: {
      404: function(){
        alert('page not found');
      }
    },
    complete: function(){
      console.log('ajax request completed');
    },
    error: function(){
      console.log('ajax resquest error');
    },
    success: function(){
      console.log('ajax request recieved seccess');
    }
  });
});
