var captcha = $('.captcha');
generate_captcha();
captcha.after('<a href="" class="reload-captcha">Reload Captcha</a>');
$(document).on('click', '.reload-captcha', function(e){
  e.preventDefault();
  generate_captcha();
});
$(document).on('submit', function(){
  var c = captcha.css('content');
  $('form').prepend('<input type="hidden" name="recaptcha" value='+c+'>');
});
function generate_captcha() {
  var rand1 = Math.random().toString(36).substr(2, 3),
    rand2 = Math.random().toString(36).substr(2, 3),
    content = (rand1 + ' ' + rand2).toUpperCase();;
  $('.data-captcha').remove();
  $('form').prepend('<div class="data-captcha"><style>.captcha,.captcha:before{content:"'+content+'";}</style><div>');  
}