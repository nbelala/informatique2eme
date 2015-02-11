$(document).ready(function(){
	$('.text').focus(function(){
        $(this).css({
            opacity : '1',
            border : '1px solid red'
        });
    })
        .blur(function(){
            $(this).css({
                opacity : '0.7',
                border : ''
            });
        });
  $('.add').click(function() {
    $(".here").append('<input type="file" name="file[]" placeholder="select your file" /><br />');
  });
});