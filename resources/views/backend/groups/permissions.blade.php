@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase">{{ $label }}</span>
<span class="badge text-uppercase p-2 mr-3">/ {{ trans('backend.manage_permissions') }}</span>

@if( has_access('groups', ['add_edit']) )
<div class="float-right">
    <a href="{{ URL::route($view.'.index', query_vars()) }}" class="btn float-right btn-sm btn-outline-dark"><i class="fa fa-long-arrow-left"></i> {{ trans('backend.all_groups') }}</a>
</div>
@endif

<hr>
@stop

@section('content')
<h5>{{ $info->post_title }}</h5>
<p>{{ $info->description }}</p>

<form method="POST">
    {{ csrf_field() }}
            
        <?php 
        $mods = json_decode($info->post_content, true);
        ?>
        	<div class="table-responsive border border-top-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                       <th width="1" colspan="2">

                           <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" id="check_all" name="" type="checkbox" value="1">
                                <label class="custom-control-label mt-1 ml-2" for="check_all">All Modules</label> 
                            </div>
      
                        </th>
                        <th>Roles</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($modules as $module => $mod)
                    <tr>
                        <td width="1">
                            <?php $checked = @$mods[$module] ? 'checked' : ''; ?>
                            <!-- Front Parent -->
                           <div class="custom-control custom-checkbox">
                                <input class="custom-control-input parent_checkbox checkboxes" id="{{ $module}}" name="ids[]" type="checkbox" value="{{ $module }}" {{ $checked }}>
                                <label class="custom-control-label" for="{{ $module }}"></label> 
                            </div>
                            <!-- Backend Parent -->  
                        </td>
                        <td width="200" class="px-0">
                            <label for="{{ $module }}" class="mt-1">
                            {{ ucwords(str_replace('-', ' ', $module)) }}<br>
                            <small class="text-muted">{{ @$mod['note'] }}</small>
                            </label>
                        </td>
                        <td>
                            @foreach($mod as $roles => $role)
                            <?php
                                $checked = '';
                                if(@$mods[$module]) {
                                    $checked = in_array($roles, @$mods[$module]) ? 'checked' : ''; 
                                }
                            ?>
                           <div class="custom-control custom-checkbox">
                                <input class="custom-control-input {{ $module }} checkboxes" id="{{ $module.'-'.$roles }}" name="{{ $module }}[]" type="checkbox" value="{{ $roles }}" data-name="{{ $module }}" {{ $checked }}>
                                <label class="custom-control-label mt-1 {{ $module.'-'.$roles }}" for="{{ $module.'-'.$roles }}">{{ $role }}</label> 
                            </div>

                            @endforeach
                        </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>  
            </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">{{ trans('backend.save_changes') }}</button>                     
    </div>
</form>

<div class="mb-5 pb-4"></div>

@include('backend.partials.media-modal')

@endsection

@section('style')
@stop

@section('plugin_script')
@stop

@section('script')
<script>
//On Click Check All
$(document).on('click change','input[id="check_all"]',function() {
    
    var checkboxes = $('.checkboxes');

    if ($(this).is(':checked')) {
        checkboxes.prop("checked" , true);
        checkboxes.closest('span').addClass('checked');
    } else {
        checkboxes.prop( "checked" , false );
        checkboxes.closest('span').removeClass('checked');
    }
});

//Document ready Check All
$(document).ready(function(){
    
    //Hide all main checkboxes
    $('.main_modules').hide();

    var a = $(".checkboxes");
    if(a.length == a.filter(":checked").length){
        $('#check_all').prop("checked" , true);
        $('#check_all').closest('span').addClass('checked');
    }
});

//Parent checkboxes
$('.parent_checkbox').click(function() {
    $class = $(this).attr('id');
    var checkboxes = $('.' + $class);
    if ($(this).is(':checked')) {
        checkboxes.prop("checked" , true);
        checkboxes.closest('span').addClass('checked'); 
    } else {
        checkboxes.prop( "checked" , false );
        checkboxes.closest('span').removeClass('checked');
    }
    if($('.parent_checkbox').filter(":checked").length == $('.parent_checkbox').length){
        $('#check_all').prop("checked" , true);
        $('#check_all').closest('span').addClass('checked');
    } else {
        $('#check_all').prop("checked" , false);
        $('#check_all').closest('span').removeClass('checked');
    }
});


//Children checkboxes
$('.checkboxes').click(function() {
    var name = $(this).data('name');
    var $parent = $('input#' + name);
    var a = $('.' + name);        
    if(a.filter(":checked").length > 0){
        $parent.prop("checked" , true);
        $parent.closest('span').addClass('checked');
    } else {
        $parent.prop( "checked" , false );
        $parent.closest('span').removeClass('checked');
    }
});
</script>
@stop
