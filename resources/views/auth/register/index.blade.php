@extends('layouts.frontend-container')

@section('content')
<div class="registration">

	@if( $step == 'completed' )
    <div class="wrapper">
		@include('auth.register.4')
	</div>
    @else
    <h2 class="text-center">Registration</h2>
    <div class="wrapper">
        <h3 class="text-center">Sign up in 3 easy steps!</h3>

        <?php @extract(session('step-'.$step)); ?>
        <form method="POST" enctype="multipart/form-data">
        	{{ csrf_field() }}
        	<input type="hidden" name="save">
		    @include('auth.register.'.$step)
        </form>
    </div>    
    @endif
</div>

@endsection

@section('style')
@stop

@section('plugin_script')
@stop

@section('script')
<script type="text/javascript">
$(document).on('click', '.btn-back', function(e) {
	e.preventDefault();
	$('[name="save"]').val(1);
	$(this).closest('form').submit();
});
$(document).on('submit', function() {
	$('.o-loader').show();
});
$(document).on('click', '.btn-choose', function(e) {
	var id = $(this).attr('data-id');
	$('[name="membership"]').val(id);
});
$(document).on('click', '[name="same_as_home"]', function(e) {
	$('.delivery-address').toggle();
});



</script>
@stop
