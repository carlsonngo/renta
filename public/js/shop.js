$(document).on('click', '.remove-to-cart', function(e) {
    e.preventDefault();
    var id = $(this).attr('data-id'),
        url = $(this).attr('href'),
        single = $('div').hasClass('single-product');

    $('.o-loader').show();
    $.get(url, function(){
        $('.cart-'+id).fadeOut('fast', function(){
            $(this).remove();
            if( $('.summary-order').find('.cart-item:visible').length == 0 && single == false ) {
                location.href = cart_url;
            }       
            get_cart_totals();        
        });
    });
}); 

function get_cart_totals() {
    $.get(cart_totals_url, function(res){
        $('.cart-totals').html(res);
        $('.o-loader').hide();
        var qty = 0;
        $('.mini-cart .cart-item').each(function(){
            qty += parseInt($(this).attr('data-qty')); 
        });
        $('.mc-q-t').html(qty);
    });    
}

$(document).on('click', '.btn-apply-coupon', function(e) {
    e.preventDefault();
    var val = $('[name="coupon_code"]').val(), 
        url = $(this).data('url');

    $('.o-loader').show();
    $('.coupon-msg').html('');

    data = { coupon_code: val }
    $.get(url, data, function(res){
        res = JSON.parse(res);
        if(res.error) {            
            $('.coupon-msg').html(res.msg).removeClass('text-success').addClass('text-danger');
        } else {
            $('.coupon-msg').html(res.msg).removeClass('text-danger').addClass('text-success');            
            get_cart_totals();
            $('.coupon-form').hide();
        }
        $('.o-loader').hide();
    });

});  

$(document).on('click', '.apply-shipping', function() {
    var val = $(this).val();
    $('.o-loader').show();
    $.get(apply_shipping_url, { id: val }, function(res){
        console.log(res);
        get_cart_totals();
        $('.o-loader').hide();
    });

});  

$(document).on('click', '.btn-remove-coupon', function(e) {
    e.preventDefault();
    $('.o-loader').show();
    $.get($(this).attr('href'), function(){
        get_cart_totals();
        $('.coupon-form').show();
        $('[name="coupon_code"]').val('');
        $('.coupon-msg').html('');
    });
});

$(document).on('change', '.on-select', function() {
    $(this).closest('form').submit();
});

$(document).on('click', '.gallery-thumb', function(){
    var image = $(this).find('img').attr('src');
    $('.single-product .carousel-item').parent().zoom({'magnify':1.2, 'touch':true, 'url' : image});    
});

$(document).on('change', ".shop-calc", function(e) {
    e.preventDefault();
    shop_calc();
});

function arraysEqual(a, b) {
    return a.sort().toString() == b.sort().toString();
}

function shop_calc() {
    var fields     = $('.shop-calc').serializeArray(),
        variations = $('.shop-form').data('variations'),
        key        = $.map(fields, function(el) { return el.value }),
        add_cart   = $('.btn-add-cart');

    $.each( variations, function( i, field ) {

    var rp    = field.field.regular_price,
        sp    = field.field.sale_price,
        sku   = field.field.sku,
        price = sp ? sp : rp;

        // field.key.join('-') == key.join('-')
        if( arraysEqual(field.key, key)  ) {

            if( has_discount(field.field) ) {
                rp = field.field.sale_price;  
                sp = field.field.regular_price;  
            }

            if( price ) {
                add_cart.removeClass('btn-default')
                        .addClass('btn-primary')
                        .removeAttr('disabled');
            } else {
                add_cart.removeClass('btn-primary')
                        .addClass('btn-default')
                        .attr('disabled', 'disabled');
            }

            $('.p-price-1').html( currency_symbol + ' ' + Number(rp).toFixed(2) );

            if( has_discount(field.field) ) {
                $('.p-price-2').html( currency_symbol + ' ' + Number(sp).toFixed(2) );
            } else {
                $('.p-price-2').html('');                
            }

            $('.p-sku').html(sku);
            return;
        }
    });    
}

function has_discount(info) {
    if( ! info.sale_price ) return false;

    if( info.sale_date_start && info.sale_date_end ) {
        var sd = info.sale_date_start.split('-'),
            ed = info.sale_date_end.split('-');

        var start_date = Date.parse(sd[2]+'-'+sd[0]+'-'+sd[1]+' '+info.sale_time_start) / 1000,
            end_date = Date.parse(ed[2]+'-'+ed[0]+'-'+ed[1]+' '+info.sale_time_end) / 1000,
            today      = $('.shop-form').attr('data-sdt');

       return ( ( today >= start_date ) && ( today <= end_date ) );
    }
}

$(document).on('blur', ".checkout-form", function(e) {
    e.preventDefault();
    var formData = $(this),
        url      = formData.attr('action');

    $.ajax({
        url: url, 
        type: "POST",  
        data: new FormData(this),
        headers: { 'fill': true },
        contentType: false,  
        cache: false,         
        processData:false,    
        success: function(response) {

        }
    });
});

$(".shop-form").on('submit', function(e) {
    e.preventDefault();
    var formData = $(this),
        url      = formData.attr('action');
    $('.o-loader').show();
    $.ajax({
        url: url, 
        type: "POST",  
        data: new FormData(this),
        headers: { 'width': $(window).width() },
        contentType: false,  
        cache: false,         
        processData:false,    
        success: function(response) {
            try {
                var data = JSON.parse(response);
                $('.cm-qty').html(data.quantity);
                $('.cm-total').html(data.total);
                $('.mobi-cart span').html(data.quantity);
            } catch(err){
                $('.mini-cart').html(response);
                // $('.mini-cart .nav-item.dropdown, .mini-cart .dropdown-menu').addClass('show');
                $('.mini-cart .dropdown-toggle').click();
                $(".mini-cart .v-scroll").animate({ scrollTop: $('.mini-cart .v-scroll').prop("scrollHeight")}, 500);
            } 
            $('.o-loader').hide();
        }
    });
});
$(document).on('click', '.color-swatch', function(e){
    e.preventDefault();
    var val = $(this).attr('data-val');
    $('.color-swatch').removeClass('active');
    $(this).addClass('active');
    $(this).closest('div').find('input').val(val);
    shop_calc();
});

function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
}

$(document).on('keyup', '[name="coupon_code"]', function() {
    $(this).closest('div').find('[type=submit]').attr('disabled', 'disabled').removeClass('btn-primary');
    if( $(this).val() ) {
        $(this).closest('div').find('[type=submit]').removeAttr('disabled').addClass('btn-primary');
    }
});  