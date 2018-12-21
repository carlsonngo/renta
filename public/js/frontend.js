$('.date-format').inputmask('99-99-9999');    

$('.datepicker').datepicker({
    autoclose: true,
    todayBtn: true,
    clearBtn: true,
    format: "mm-dd-yyyy"
});

$(document).on('click', '.datepicker', function(){
    var date = $(this).val().replace(/-/g, '/');
    $(this).datepicker({
        dateFormat: "mm-dd-yyyy"
    }).datepicker("setDate", date);
});

$(window).scroll(function() {
    if ($(this).scrollTop() >= 50) {        // If page is scrolled more than 50px
        $('#return-to-top').fadeIn(200);    // Fade in the arrow
    } else {
        $('#return-to-top').fadeOut(200);   // Else fade out the arrow
    }
});
$('#return-to-top').click(function() {      // When arrow is clicked
    $('body,html').animate({
        scrollTop : 0                       // Scroll to top of body
    }, 500);
});     

$(document).on('change', '.switch-lang', function(){
	location.href = '?lang='+$(this).val();
});

function get_query_string(k){
    var p={};
    location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(s,k,v){p[k]=v})
    return k?p[k]:p;
}

if( $('.switch-lang').is(':visible') ) {
    var querystring = 'lang='+ (get_query_string('lang')==undefined ? $('.switch-lang').val() : get_query_string('lang'));
    if(querystring!='lang=undefined') {
        $('a:not([data-toggle="tab"], [href="#"], [data-toggle="modal"], [role="button"], [href="javascript:"], .carousel-control-prev, .carousel-control-next, .page-link)').each(function() {
            var href = $(this).attr('href');
            if (href) {
                href += (href.match(/\?/) ? '&' : '?') + querystring;
                $(this).attr('href', href);
            }
        });    
    }
}

$(window).on('scroll', function (event) {
    $.each($('[data-spy="classy"]'), function(){
        var classy = $(this), 
            classes = classy.attr('data-class'),
            top = classy.attr('data-top'),
            target = classy.attr('data-target'),
            width = classy.attr('data-width');

        var scrollValue = $(window).scrollTop();
        var offset = classy.attr('data-offset-top');

        if (scrollValue > offset) {
            classy.addClass(classes);
            var width = width ? width : classy.parent().width();
            $(target).css({'width' : width, 'top': top});
        } else{
            classy.removeClass(classes);
            $(target).css({'width' : width, 'top': ''});
        }
    });
}); 
$(document).on("keypress", ".numeric", function( event ){
    if(event.which != 13) {
        if(event.which < 46 || event.which >= 58 || event.which == 47) {
            event.preventDefault();
        }
        if(event.which == 46 && $(this).val().indexOf('.') != -1) {
            event.preventDefault();
        }
    }
});
$(document).on('keypress', '.no-enter', function(e) {
    if (e.keyCode == 13) {
        event.preventDefault();
    }
});

$(document).on('keypress', '.no-space', function(e) {
    if (e.keyCode == 32) {
        event.preventDefault();
    }
});

$(document).on('keyup', '.to-uppercase', function(evt){
    $(this).val($(this).val().toUpperCase());
});

$('[data-toggle="tooltip"]').tooltip();

$(document).ready(function() {
    $('.select2').select2();
});

