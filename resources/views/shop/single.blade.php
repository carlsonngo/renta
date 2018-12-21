@extends('layouts.frontend-fullwidth')

@section('content')
<div class="single-product">
	<div class="product">
		<div class="container">
			<div class="row">
				<div class="col-sm">

    <div class="carousel slide main-img mb-2" id="lightbox" data-interval="false">

        @if( has_discount($info) )
        <div class="discount bg-orange text-white h4 px-4 rounded">{{ $discount }}</div>
        @endif

        @if( @$info->gallery )
        <div class="carousel-inner text-center rounded border">
            <?php $i=0; ?>
            @foreach( json_decode($info->gallery) as $gallery )
            <div class="carousel-item <?php echo $i==0 ? 'active' : ''; ?>">
                <div class="img-container" style="height: 500px;">
                    <img src="{{ has_image($gallery) }}" class="rounded img-fluid">                
                </div>
            </div>
            <?php $i++; ?>
            @endforeach
        </div>
        @else
        <img src="{{ has_image($info->image) }}" class="rounded img-fluid w-100">
        @endif


    </div>
    
    @if( @$info->gallery )
    <div class="mx-2">
        <div class="row">
        <?php $i=0; ?>
        @foreach( json_decode($info->gallery) as $gallery )
        <div class="col-lg-2 col-md-3 col-sm-3 col-4 p-1">
            <div class="img-container rounded d-block" style="max-height: 100px;">
                <a href="#" data-target="#lightbox" data-slide-to="<?php echo $i; ?>" class="gallery-thumb">
                    <img src="{{ has_image($gallery) }}">
                </a>
            </div>
        </div>
        <?php $i++; ?>
        @endforeach
        </div>    
    </div>
    @endif

				</div>
				<div class="col-sm">
					<h2>{{ $product_title = trans_post($info, 'post_title', '_title') }}</h2>

                    @if( @$info->short_description )
					<p class="item">Item Description</p>
					<p class="item-description text-justify">{{ @$info->short_description }}</p>
                    @endif


		@if( $info->extra )
		@foreach( json_decode($info->extra) as $ex => $extra)
		@if( $extra->title )
		<div class="size">
			<div class="description-wrapper">
				<div class="clearfix">
					<p>{{ $extra->title }}</p>
					<button class="collapse-arrow" type="button" data-toggle="collapse" data-target="#extra-{{ $ex }}" aria-expanded="false" aria-controls="extra-{{ $ex }}"><i class="fa fa-angle-down" aria-hidden="true"></i></button>
				</div>

				<div class="collapse multi-collapse" id="extra-{{ $ex }}">
					<div class="collapse-description">
						{!! $extra->description !!}
					</div>
				</div>

			</div>
			<div class="dropdown-divider"></div>
		</div>
		@endif
		@endforeach
		@endif



        <form method="POST" action="{{ route('shop.add_to_cart') }}" class="shop-form" enctype="multipart/form-data" 
            data-variations="{{ json_encode(json_decode(@$info->variation_data, true )) }}"
            data-sdt="{{ strtotime(date('Y-m-d H:i:s')) }}">
            {{ csrf_field() }}
            <input type="hidden" name="add-to-cart" value="{{ $info->id }}">

            @if( $info->product_type == 'variable' )
    			@include('shop.variations')
            @endif

 
            @if( ! $info->sold_individually )
            <label class="text-uppercase small">Quantity</label>
            @endif

            @if( $info->sold_individually )
            <input type="hidden" name="qty" class="numeric" value="1" min="1">      
            @else
            <div class="row">
                <div class="col-auto">
                    <div class="form-group">
                        <input type="number" name="qty" class="form-control numeric" value="1" min="1">        
                    </div>                    
                </div>
            </div>
            @endif     



        @if( @$info->sku )
        <p class="text-muted p-sku">sku : {{ $info->sku }}</p>
        @endif

        @if( @$info->regular_price )
        <h3 class="p-price-1">
            @if( has_discount($info) )
                {{ amount_formatted($info->sale_price) }}
            @else
                {{ amount_formatted($info->regular_price) }}
            @endif
        </h3>
        @endif


        <s class="p-price-2">
        @if( has_discount($info) )
            {{ amount_formatted($info->regular_price) }}
        @endif 
        </s>

        @if( Auth::check() )
        @if( get_membership() == 'premium' || ! @$info->premium )

            @if( @$info->rental_product )
    		<div id="datepicker" class="mb-3"></div>
    		<input type="hidden" name="delivery_date">
    		<input type="hidden" name="return_date">
    		<div class="btn-calendar py-3" style="display:none;"></div>
            @endif

            <?php $rental = json_decode(@$info->rental); ?>

            <button type="submit" class="btn-add-to-cart btn-block btn-add-cart">Add To Cart</button>  
        @else
            <a href="" class="btn btn-block btn-primary btn-lg">Upgrade to Premium</a>
        @endif
        @else
            <div class="border p-3 h5 text-center bg-light">
                Please <a href="{{ route('auth.login') }}">login</a> or <a href="{{ route('auth.register') }}">register</a> to rent this product
            </div>
        @endif

        </form>






				</div>
			</div>
			<div class="section dropdown-divider"></div>


<!-- BEGIN  REVIEWS -->
<ul class="nav nav-tabs mt-4  border-bottom-0">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#description">{{ trans('backend.description') }}</a>
  </li>

  @if( @$info->enable_reviews )
  <li class="nav-item">
    <?php $mr = @$my_review ? 1 : 0; ?>
    <a class="nav-link" data-toggle="tab" href="#review">Reviews ({{ $count_reviews = count($reviews) + $mr }})</a>
  </li>
  @endif
</ul>
<div class="tab-content border">
  <div class="tab-pane container active" id="description">
      <p class="text-justify">
    {!! trans_post($info, 'post_content', '_content') !!} 
    </p>
  </div>

  @if( @$info->enable_reviews )
  <div class="tab-pane container fade py-4" id="review">
    <h5><b>Ratings & Reviews of</b> {{ $product_title }}</h5>
    <hr>
<div class="row">
    <div class="col-md-4 mb-4">
        <h2>
            <?php $ratings_count = $ratings->count() ? $ratings->count() : 1; ?>
            <b>{{ $overall_rates = number_format($ratings->sum('post_title') / $ratings_count, 1) }}</b> 
            <span class="text-muted small">/ 5</span>
        </h2>
        <h4>
            <div class="rates-o">
                <span>{{ stars_review(5) }}</span>
                <span class="text-warning">{{ stars_review($overall_rates) }}</span>
            </div>
        </h4>
        
        <span class="text-muted">{{ array_sum($rate) }} Ratings</span>        
    </div>
    <div class="col">
        @foreach(range(5, 1) as $star)
        <div class="row">
            <div class="col-md-auto col-sm-3">
                <span class="rates-o">
                    <span>{{ stars_review(5) }}</span>
                    <span class="text-warning">{{ stars_review($star) }}</span>
                </span>  
                <span class="float-right d-sm-none d-inline-block">{{ @$rate[$star] ?? 0 }}</span>                      
            </div>
            <div class="col-md-5 col-sm-6 mb-3">
                <div class="progress"><?php 
                $s = @$rate[$star] ?? 0;
                $rate_sum = array_sum($rate) ? array_sum($rate) : 1;
                $progress = number_format( ($s * 100) / $rate_sum, 1); ?>
                  <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
               
                </div>                    
            </div>
            <div class="col-md-3 col-sm-3 d-none d-sm-block">{{ @$rate[$star] ?? 0 }}</div>
        </div>
        @endforeach    

    </div>
</div>

<hr>

    @if( Auth::check() ) 
    <div class="my-review">
    @include('shop.customer.product-review')
    @if( !$count_reviews && !$my_review)
        <div class="alert alert-info">There are no reviews yet.</div>    
    @endif
    </div>

    <div class="review-form" style="{{ @$my_review ? 'display:none;' : ''  }}">  
        <div class="form-group">
            <label>Rating</label>
            <div class="rating" data-v="{{ @$my_review->post_title ?? 0 }}"></div>        
        </div>
        <div class="form-group">
            <label>Your review</label>
            <textarea id="comment" class="form-control check-r-form" rows="5">{{ @$my_review->post_content }}</textarea>      
        </div>
        <button type="button" class="btn btn-review" data-url="{{ route('backend.shop.review') }}" disabled>Submit Review</button>        
    </div>
    @else
        <a href="{{ route('login', ['intended' => route('shop.single', @$info->post_name)]) }}#review" class="text-primary"><b>Login</b></a> to post a review.
    @endif


        @if($count_reviews || $my_review)
        <hr>
        <h5 class="mt-4">Customer Reviews</h5>
        <iframe src="{{ route('shop.reviews', $info->id) }}" width="100%" frameborder="0" scrolling="no" onload="resizeIframe(this)"></iframe>
        @endif
  </div>
  @endif
</div>

</div>
<!-- END REVIEWS -->


		</div>

	</div>






	<div class="ratings-reviews">
		<div class="container">
			<h2>Ratings & Reviews</h2>
			<div class="row text-center">
				<div class="col-lg">
					<img src="{{ asset('assets/img/05-winter.jpg') }}" class="img-fluid" alt="">
					<div class="stars">
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="far fa-star"></i>
					</div>
					<p class="star-review-text">4 of 5 Stars</p>
				</div>
				<div class="col-lg">
					<img src="{{ asset('assets/img/05-winter.jpg') }}" class="img-fluid" alt="">
					<div class="stars">
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="far fa-star"></i>
					</div>
					<p class="star-review-text">4 of 5 Stars</p>
				</div>
				<div class="col-lg">
					<img src="{{ asset('assets/img/05-winter.jpg') }}" class="img-fluid" alt="">
					<div class="stars">
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="far fa-star"></i>
					</div>
					<p class="star-review-text">4 of 5 Stars</p>
				</div>
				<div class="col-lg">
					<img src="{{ asset('assets/img/05-winter.jpg') }}" class="img-fluid" alt="">
					<div class="stars">
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="far fa-star"></i>
					</div>
					<p class="star-review-text">4 of 5 Stars</p>
				</div>
				<div class="col-lg">
					<img src="{{ asset('assets/img/05-winter.jpg') }}" class="img-fluid" alt="">
					<div class="stars">
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="far fa-star"></i>
					</div>
					<p class="star-review-text">4 of 5 Stars</p>
				</div>
				<div class="col-lg">
					<img src="{{ asset('assets/img/05-winter.jpg') }}" class="img-fluid" alt="">
					<div class="stars">
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="far fa-star"></i>
					</div>
					<p class="star-review-text">4 of 5 Stars</p>
				</div>
			</div>
			<div class="section dropdown-divider"></div>
		</div>
	</div>
	<div class="products">
		<div class="container">
			<h2>Similar Items</h2>






<div id="product" class="product-list carousel slide" data-ride="carousel" data-interval="false">

  @if( count($rows) > 1 )
  <div class="carousel-control text-right">
    <a class="carousel-control-prev" href="#product" data-slide="prev">
      <span class="carousel-control-prev-icon p-3"></span>
    </a>
    <a class="carousel-control-next" href="#product" data-slide="next">
      <span class="carousel-control-next-icon p-3"></span>
    </a>    
  </div>
  @endif

  <!-- The slideshow -->
  <div class="carousel-inner mt-4" style="overflow: inherit;">
    <?php $r=1;?>
    @foreach($rows as $row)
    <div class="carousel-item <?php echo $r==1 ? 'active' : ''; ?>">
      <div class="row">
        @foreach($row as $related)
        <?php $postmeta = get_meta( $related->postMetas()->get() ); ?>

        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <a href="{{ route('shop.single', $related->post_name) }}">
					<div class="product-image restricted">
						<div class="mask"></div>
						 <img src="{{ has_image($postmeta->image) }}" class="img-fluid w-100">
					</div>
					<div class="product-info">
						<h5>{{ trans_post($related, 'post_title', '_title') }}</h5>


                <div class="row">

                    @if( @$postmeta->regular_price )
                    <div class="col"><h6>
                        @if( has_discount($postmeta) )
                            {{ amount_formatted($postmeta->sale_price) }}
                        @else
                            {{ amount_formatted($postmeta->regular_price) }}
                        @endif
                    </h6></div>
                    @endif

                    @if( has_discount($postmeta) )
                    <div class="col text-right"><small>
                        <s class="text-orange">{{ amount_formatted($postmeta->regular_price) }}</s></small> 
                    </div>
                    @endif 

                </div>  


					</div>
            </a>  


 

        </div>
        @endforeach
      </div>
    </div>
    <?php $r++; ?>
    @endforeach
  </div>

</div>






		</div>
	</div>
</div>


@endsection


@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/date-rental/bootstrap-datepicker.css') }}">
<style>
.btn-primary { 
	background-color: #7c7f81; 
	border-color: #7c7f81; 
	border: none;
}	
.btn-primary:hover, .btn-primary:active, .btn-primary:focus { 
	background-color: #717375; 
	box-shadow: none !important;
	border: none !important;
}	

.datepicker-inline .datepicker { width: 100%; }
.datepicker--cell.-in-range- { background: rgba(182, 210, 197, 0.5); }
.datepicker--cell.-selected-.-focus-,
.datepicker--cell.-selected- { background: #b6d2c5; }
.datepicker--cell.-disabled-.-focus-,
.datepicker--cell.-selected-.-disabled-, 
.datepicker--cell.-in-range-.-disabled- {
	background-color: red !important;
	color: #fff !important;
}
.datepicker--cell.-disabled-:not(.-other-month-) {
    cursor: default;
    color: #ffffff;
    background-color: #ffa2a291;
    border-radius: 0;
}
.btn-primary.disabled:hover, 
.btn-primary.disabled, 
.btn-primary:disabled {
    background-color: #babbbc;
    border-color: #babbbc;
    cursor: not-allowed;
} 
.datepicker--nav-title {
    pointer-events: none;    
}
</style>
@stop

@section('plugin_script')
<script src="{{ asset('plugins/date-rental/bootstrap-datepicker.js') }}?{{ date('Ymdhis') }}"></script>

<script type="text/javascript">

$.fn.datepicker.language['en'] = {
    days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
    daysShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
    daysMin: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
    months: ['January','February','March','April','May','June', 'July','August','September','October','November','December'],
    monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    today: 'Today',
    clear: 'Clear',
    dateFormat: 'mm-dd-yyyy',
    timeFormat: 'hh:ii aa',
    firstDay: 0
}

Date.prototype.selectDays = function(curDate,type) {
    var date = new Date(curDate);
    if (type == 'add') {
	    date.setDate(date.getDate() + {{ @$rental->min_days - 1 }});
    } else {
	    date.setDate(date.getDate() - 1);
    }
    return date;
}

var disableddates = <?php echo $blocked; ?>;
$('#datepicker').unbind();

$('#datepicker').datepicker({
	range: true,
	rangec: true,
	rangeCount: {{ @$rental->min_days ?? 0 }},
	language: 'en',
	minDate: new Date(),
	onSelect: function(dateText,date,ins) {
		var date = new Date();
		if (ins.selectedDates.length < 2) {
			$("[name=delivery_date], [name=return_date]").val('');
			$('.btn-add-cart').removeAttr('disabled');   

			var selectedDate = ins.lastSelectedDate;
			ins.selectDate(date.selectDays(selectedDate,'add'));
			var dates =  ins._prevOnSelectValue.split(','),
			s = dates[0].trim(),
			e = dates[1].trim();

	        if( $('.datepicker--cell.-selected-').hasClass('-disabled-') ) {
				$('.btn-add-cart').attr('disabled', 'disabled');         
				return;
	        }

			if( s != e ) {
				$("[name=delivery_date]").val(s);
				$("[name=return_date]").val(e);		

				var ds = new Date(s).toLocaleString('en-US', {month: "short"}) + ' ' + new Date(s).getDate();
				var de = new Date(e).toLocaleString('en-US', {month: "short"}) + ' ' + new Date(e).getDate();

				$('.btn-calendar').html( ds +' to '+ de ).show();		
			}
		}

		$('.datepicker--cell').removeClass(['-selected-','-range-from-','-in-range-','-range-to-']);
		if( !$("[name=delivery_date]").val() && !$("[name=return_date]").val() ) {
			$('.btn-calendar').hide();			
		}
	},
    onRenderCell: function(date, cellType) {
        var m = date.getMonth();
        var d = date.getDate();
        var y = date.getFullYear();

        var currentdate = y + '-' + ("0" + (m + 1)).slice(-2) + '-' + ("0" + d).slice(-2);

        for (var i = 0; i < disableddates.length; i++) {
            if ($.inArray(currentdate, disableddates) != -1 ) {
                return {
                    disabled: true
                }
             }
         }

    }
    /*,
	onRenderCell: function(date, cellType) {
        if (cellType == 'day' && blockDates.indexOf(date.toLocaleDateString("en-US")) > -1) {
            return {
                disabled: true
            }
        }
    }*/
});
</script>



<script src="{{ asset('plugins/zoom/jquery.zoom.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('plugins/ratings/jquery.rateyo.min.css') }}">
<script src="{{ asset('plugins/ratings/jquery.rateyo.min.js') }}"></script>
@stop

@section('script')
<script>
$(document).on('click', '.btn-review', function(){
   var comment = $('#comment'),
        rating = $('.rating').attr('data-v'),
        review = $('.my-review'),
        pid = $('[name="add-to-cart"]').val(),
        token = $('[name="_token"]').val(),
        url = $(this).data('url');
    if( comment.val() && pid && rating ) {
        $('.o-loader').show();
        data = { '_token':token, 'comment':comment.val(), 'rating':rating, 'pid':pid }
        $.post(url, data, function(res){
            $('.my-review').show();
            $('.review-form').hide();
            review.html(res);
            $('.o-loader').hide();
        });        
    }
});
$(document).on('click', '.edit-review', function(e){
    e.preventDefault();
    $('.review-form, .my-review').toggle();
});
$(document).on('click', '.delete-review', function(e){
    e.preventDefault();
    pid = $('[name="add-to-cart"]').val(),
    url = $(this).data('url'),
    token = $('[name="_token"]').val();

    if (confirm('Are you sure you want to delete your review?')) {
        $('.o-loader').show();
        data = { '_token':token, 'comment':'', 'pid':pid }
        $.post(url, data, function(res){
            $('.review-form, .my-review').toggle();
            $('#comment').val('');
            init_new_rating();
            $('.o-loader').hide();
        });
    } 
});

function init_new_rating() {
  $(".rating").rateYo('destroy');
  var $rateYo = $(".rating").rateYo({
    rating: 0,
    fullStar: true,
    numStars: 5,    
    spacing: "5px",
    starWidth: "25px",
  }).on("rateyo.set", function (e, data) {
    $('.rating').attr('data-v', data.rating);
  });
  $rateYo.rateYo("option", "multiColor", true);
}
function init_rating() {
  var $rateYo = $(".rating").rateYo({
    rating: {{ @$my_review->post_title ?? 0 }},
    fullStar: true,
    numStars: 5,    
    spacing: "5px",
    starWidth: "25px",
  }).on("rateyo.set", function (e, data) {
    $('.rating').attr('data-v', data.rating);
    check_review_form();
  });
  $rateYo.rateYo("option", "multiColor", true);
  check_review_form();

    var hash = window.location.hash;
    if(hash) {
        $('[href="'+hash+'"]').click();
        scroll_to_id(hash);        
    }
}
init_rating();

$(document).on('keyup', '.check-r-form', function(e){
    check_review_form();
});

function check_review_form() {
    $('.btn-review').removeClass('btn-primary').attr('disabled', 'disabled');
    if( $('#comment').val() && $('.rating').attr('data-v') != 0 ) {
        $('.btn-review').addClass('btn-primary').removeAttr('disabled');
    }
}

function scroll_to_id(target) {
    $('html, body').animate({
        scrollTop: $(target).offset().top - 100
    }, 1000);    
}

$(document).on('click', '[href="#review"]', function(){
    resizeIframe($('iframe')[0]);
});

if( $(window).width() > 767 ) { 
    $('.single-product .carousel-item').parent().zoom({'magnify':2, 'touch':true});   
}
shop_calc();
</script>
@stop
