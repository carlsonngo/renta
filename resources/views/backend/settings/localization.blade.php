@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase">{{ $label }}</span>
<span class="badge text-uppercase p-2 mr-3">/ {{ trans('backend.settings') }} / <span class="text-primary">{{ $f }}</span></span>
<hr>
@stop

@section('content')

<div class="row align-items-center">
    <div class="col-auto mb-3 mb-md-0">
        <div class="dropdown">
          <button type="button" class="btn btn-sm btn-outline-dark dropdown-toggle" data-toggle="dropdown">
            {{ trans('backend.actions') }}
          </button>
          <div class="dropdown-menu">
                <a href="?f=backend" class="dropdown-item {{ $f=='backend' ? 'active' : '' }}">Backend</a>
                <a href="?f=messages" class="dropdown-item {{ $f=='messages' ? 'active' : '' }}">Messages</a>
                <a href="?f=select" class="dropdown-item {{ $f=='select' ? 'active' : '' }}">Select</a>        
                <a href="?f=validation" class="dropdown-item {{ $f=='validation' ? 'active' : '' }}">Validation</a>  
          </div>
        </div>        
    </div>
    <div class="col-auto mb-3 mb-md-0 px-0">
        <a href="{{ route('backend.settings.localization-export', query_vars()) }}" class="btn btn-outline-dark btn-sm">
                    <i class="fas fa-download mr-2"></i> {{ trans('backend.export') }}</a>
    </div>
    <div class="col-auto mb-3 mb-md-0">
        <form action="{{ route('backend.settings.localization-import', query_vars()) }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="input-group mt-3 mb-3">
          <input type="file" name="file" class="form-control form-control-sm" accept=".csv">
          <div class="input-group-prepend">
            <button type="submit" class="btn btn-sm" disabled>
                <i class="fas fa-upload mr-2"></i> {{ trans('backend.import') }}
            </button>
          </div>
        </div>            
        </form>
    </div>
    <div class="col-auto">
        <span>{{ trans('messages.filename_must_contain') }} <span class="text-danger">{{ $f }}.csv</span></span><br>   
        Do not change <span class="text-danger">:variable</span> and <span class="text-danger">:attribute</span>    
    </div>

</div>

    <p class="text-muted">{{ trans('messages.localization_help') }}</p>

    <div class="table-responsive no-enter">

    <table class="table table-hover table-bordered bg-white">
        <thead>
            <tr>
                <th width="200">{{ trans('backend.variable') }}</th>
                <?php $l=1; ?>
                @foreach(languages() as $lang_k => $lang_v)
                <th>{{ $lang_v }} 
                    <span class="small text-uppercase badge badge-primary">{{ ($locale==$lang_k) ? trans('backend.default') : '' }}</span> 
                    <span class="text-info small float-right text-uppercase">{{ trans('backend.editable') }}</span>
                </th>
                <?php $l++; ?>
                @endforeach
            </tr>
        </thead>
        <tbody>

            @foreach($trans as $tran_k => $tran_v)

                @if( is_array($tran_v) )
                <tr>
                    <td colspan="{{ $l }}" class="px-2 py-1 bg-info text-white font-weight-bold">{{ $tran_k }}</td>
                </tr>
                @foreach($tran_v as $l_k => $l_v)
                <tr>
                    <td class="text-muted">{{ $l_k }}</td>
                    @foreach(languages() as $lang_k => $lang_v)
                    <td contenteditable="true" data-lang="{{ $lang_k }}" data-key="{{ $tran_k.'.'.$l_k }}">{{ \Lang::get($f.'.'.$tran_k.'.'.$l_k, [], $lang_k) }}</td>
                    @endforeach
                </tr>
                @endforeach
                @else
                    <tr>
                    <td class="text-muted">{{ $tran_k }}</td>
                        @foreach(languages() as $lang_k => $lang_v)
                        <td contenteditable="true" data-lang="{{ $lang_k }}" data-key="{{ $tran_k }}">{{ \Lang::get($f.'.'.$tran_k, [], $lang_k) }}</td>    
                        @endforeach                                    
                    </tr>
                @endif

            @endforeach
        </tbody>
    </table>

    </div>


<div class="mb-5 pb-4"></div>

@include('backend.partials.media-modal')

@endsection

@section('style')
@stop

@section('plugin_script')
@stop

@section('script')
<script>
$(document).on('keypress', '[contenteditable="true"]', function(e){
if(e.which == 13) {
    var $this = $(this);
    $this.addClass('animated flash');
    data = { 
        '_token': $('[name="csrf-token"]').attr('content'),
        'key' : $(this).attr('data-key'),
        'lang' : $(this).attr('data-lang'),
        'val' : $(this).text(),
    }


    $.post('', data, function(d) {
        console.log(d);
        setTimeout(function(){ 
            $this.removeClass('animated flash');
         }, 1000);
    });
}
});  
$(document).on('change', '[name=file]', function(e){
    $(this).closest('.input-group')
           .find('.btn')
           .addClass('btn-outline-dark')
           .removeAttr('disabled');
}); 
</script>
@stop
