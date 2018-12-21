@extends('layouts.backend')

@section('title')
<span class="h4 font-weight-normal text-uppercase">{{ $label }}</span>
<span class="badge text-uppercase p-2 mr-3">/ {{ trans('backend.settings') }}</span>
<hr>
@stop

@section('content')
<form method="POST">


    <div class="form-row mb-4">
        <div class="col-md-3 col-md-4 col-sm-6 mb-sm-0 mb-3">
            <label>{{ trans('backend.theme') }}</label>
        {{ Form::select('theme', themes(), Input::get('theme'), ['class ' => 'form-control', 'id' => 'select', 'onchange' => "selectTheme()"]) }}
        </div>
        <div class="col-md-3 col-md-4 col-sm-6">
            <label>{{ trans('backend.code_editor') }}</label>
        {{ Form::select('css_theme', css_themes(), @$css_theme->value, ['class ' => 'form-control', 'id' => 'select', 'onchange' => "selectTheme()"]) }}
        </div>
    </div>

    {{ csrf_field() }}

    <p class="text-danger alert alert-warning small p-2">{{ trans('messages.do_not_edit_theme') }} <span class="float-right">Learn at <a href="https://www.w3schools.com/css/default.asp" target="_blank"><b>w3schools.com</b></a></span></p>

    <div class="form-group">
        <div class="code-m">
            <textarea name="code" class="code-editor" id="code-editor">{!! $style !!}</textarea>            
        </div>
    </div>

    <div class="form-action">
        <div class="form-container">
            <span class="mr-2 small pb-2">
                <i class="far fa-calendar"></i> {{ date_formatted($date) }}
            </span>
            <span class="mr-2 small pb-2">
                <i class="far fa-clock"></i> {{ time_ago($date) }}    
            </span>
            <button class="btn btn-sm btn-primary"><i class="fas fa-check mr-2 small"></i> {{ trans('backend.save_changes') }}</button>            
        </div>
    </div>
        
</form>

@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('plugins/codemirror/lib/codemirror.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/codemirror/addon/hint/show-hint.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/codemirror/theme/monokai.css') }}" class="theme-css">
@stop

@section('plugin_script')
<script src="{{ asset('plugins/codemirror/lib/codemirror.js') }}"></script>
<script src="{{ asset('plugins/codemirror/mode/css/css.js') }}"></script>
<script src="{{ asset('plugins/codemirror/addon/hint/show-hint.js') }}"></script>
<script src="{{ asset('plugins/codemirror/addon/hint/css-hint.js') }}"></script>
@stop

@section('script')
<script>
var editor = CodeMirror.fromTextArea(document.getElementById("code-editor"), {
    lineNumbers: true,
    styleActiveLine: true,
    matchBrackets: true
});

function selectTheme() {
    var theme = $('[name=css_theme]').val();
    var url = $('[name=site-url]').attr('content')+"/plugins/codemirror/theme/"+theme+".css";
    $('.theme-css').attr('href', url );

    editor.setOption("theme", theme);
    location.hash = "#" + theme;
}

var choice = $('[name=css_theme]').val();

if (choice && choice != 'default') {
    var url = $('[name=site-url]').attr('content')+"/plugins/codemirror/theme/"+choice+".css";
    $('.theme-css').attr('href', url );
    editor.setOption("theme", choice);
}
CodeMirror.on(window, "hashchange", function() {
    var theme = location.hash.slice(1);
    if (theme) { choice = theme; selectTheme(); }
});

$(document).on('change', '[name=theme]', function(){
    location.href = '?theme='+$(this).val();
});
</script>
@stop
