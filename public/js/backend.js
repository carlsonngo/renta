$( ".sortable" ).sortable();
$( ".sortable" ).disableSelection();
$('.date-format').inputmask('99-99-9999');    
$('.time-format').inputmask('99:99');    

$(document).on('blur', '[data-type="slugy"]', function(){
    var val = $(this).val();
    slug = slugify(val);
    $(this).val(slug);
});

$(document).on('keyup', '[data-type="slug"]', function(){
    var val = $(this).val(),
        target = $(this).attr('data-slug');
    slug = slugify(val);
    $(target).val(slug);
});

function slugify(text){
  return text.toString().toLowerCase()
    .replace(/_+$/, '-')
    .replace(/\s+/g, '-')           // Replace spaces with -
    .replace(/[^\u0100-\uFFFF\w\-]/g,'-') // Remove all non-word chars ( fix for UTF-8 chars )
    .replace(/\-\-+/g, '-')         // Replace multiple - with single -
    .replace(/^-+/, '')             // Trim - from start of text
    .replace(/-+$/, '')            // Trim - from end of text
    .replace(/^_+/, '')             // Trim _ from start of text
    .replace(/_+$/, '');            // Trim _ from end of text
}

$(document).on('click', '[data-toggle="confirm-modal"]', function(e) {
  e.preventDefault();

    var target = $(this).data('target'),
        title  = $(this).data('title'),
        body   = $(this).data('body'),
        url    = $(this).attr('data-url'),
        modal  = $(target);

      modal.modal('show');
      modal.find('.modal-title').html(title);
      modal.find('.modal-body').html(body);
      modal.find('.btn-confirm').attr('href', url);
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

//On Click Check All
$(document).on('click change','input[id="check_all"]',function() {
    var checkboxes = $('.checkboxes'), 
        btn = $('.btn-apply-action');

    if ($(this).is(':checked')) {
        checkboxes.prop("checked" , true);
        checkboxes.closest('span').addClass('checked');
    } else {
        checkboxes.prop( "checked" , false );
        checkboxes.closest('span').removeClass('checked');
    }

    if( checkboxes.filter(":checked").length > 0 ){
        btn.addClass('btn-primary').removeAttr('disabled');
    } else {
        btn.removeClass('btn-primary').attr('disabled', true);
    }

});


//Children checkboxes
$('.checkboxes').click(function() {
    var a = $(".checkboxes"), 
        btn = $('.btn-apply-action');

    if(a.length == a.filter(":checked").length){
        $('#check_all').prop("checked" , true);
        $('#check_all').closest('span').addClass('checked');
    } else {
        $('#check_all').prop("checked" , false);
        $('#check_all').closest('span').removeClass('checked');     
    }

    if( a.filter(":checked").length > 0 ){
        btn.addClass('btn-primary').removeAttr('disabled');
    } else {
        btn.removeClass('btn-primary').attr('disabled', true);
    }
});

$(document).on('click', '.btn-img-preview', function(e){
    e.preventDefault();
    var title = $(this).data('title'),
        href  = $(this).attr('href');
    
    $('#img-preview').modal('show');
    $('#img-preview .modal-title').html(title);
    $('#img-preview img').attr('src', href);
    $('[type="url"]').val(href);
});

tinymce.init({ 
    selector: '.tinymce',
    height: 500,
    theme: 'modern',
    convert_urls: false,
    plugins: 'code print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools  contextmenu colorpicker textpattern help',
    toolbar1: 'code | formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
    image_advtab: true,
    content_css: [
        '//fonts.googleapis.com/css?family=Montserrat:400,600,700',
        $('[name="site-url"]').attr('content')+'/plugins/tinymce/codepen.min.css'
    ]
});

$(document).on('click', '.filemanager', function(e){
    e.preventDefault();
    var src = $(this).attr('data-href');
    $('#media-modal iframe').attr('src', src);
    $('#media-modal').modal('show');    
});

function getSearchParams(k){
    var p={};
    location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(s,k,v){p[k]=v})
    return k?p[k]:p;
}

$(document).on('change', '.switch-lang', function(){
    $(this).closest('form').submit();
});

if( $('.switch-lang').is(':visible') ) {
    var querystring = 'lang='+ $('.switch-lang').val();
    $('a:not([data-toggle="tab"], [href="#"], [data-toggle="modal"], [data-toggle="pill"], [href="javascript:"], [data-toggle="collapse"], [data-toggle="modal-img"])').each(function() {
        var href = $(this).attr('href');

        if (href && href.indexOf('lang=') < 1) {
            href += (href.match(/\?/) ? '&' : '?') + querystring;
            $(this).attr('href', href);
        }
    });
}

$(document).on('click', '.delete-media', function(e){
    e.preventDefault();
    $(this).closest('li').remove();   
    $(this).closest('.m-list').remove();   
});

$('[data-toggle="tooltip"]').tooltip(); 

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

$(document).on('keyup', '.get-price', function(){
    var sp = $('[name="sale_price"]').val(),    
    rp = $('[name="regular_price"]').val(),    
    p = $('[name="price"]');  
    p.val( sp ? sp : rp );
});

$(document).on('click', '.btn-add-note', function(){
    var url = $(this).data('url'), 
    notes = $('.order-notes'),
    note  = $('#note'),
    token = $('[name="_token"]').val();
    if(!note.val()) return false;
    $.post(url, { '_token' : token, 'note' : note.val() }, function(res){
        notes.append(res);
        notes.animate({ scrollTop: $('.order-notes').prop("scrollHeight")}, 500);
        note.val('');
    });
});
$(document).on('click', '.delete-note', function(e){
    e.preventDefault();
    var url = $(this).data('url'), 
    id = $(this).attr('data-id');
    $.get($(this).data('url'), function(res){
        $('#note-'+id).slideUp();
    });
});
$('.order-notes').animate({ scrollTop: $('.order-notes').prop("scrollHeight")}, 0);

$('.datepicker').datepicker({
    autoclose: true,
    todayBtn: true,
    clearBtn: true,
    format: "mm-dd-yyyy"
});
$('.timepicker').TimePickerAlone({ inputFormat: "HH:mm" });

$(document).on('click', '.datepicker', function(){
    var date = $(this).val().replace(/-/g, '/');
    $(this).datepicker({
        dateFormat: "mm-dd-yyyy"
    }).datepicker("setDate", date);
});

function number_format(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

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

$('[data-toggle="tooltip"]').tooltip();

$(document).ready(function() {
    $('.select2').select2();
});

function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
}

$(document).on('change', '.file-import', function(){
    $('.btn-import').addClass('btn-primary').removeAttr('disabled');
});

$(document).on('click', '[data-target="#import-data"]', function(){
    var url = $(this).attr('data-href'), 
        title = $(this).html();
    $('#import-data').find('form').attr('action', url);
    $('#import-data').find('.modal-title').html(title);
});
