<?php 
$setting =  App\Setting::get_settings();
$page = Request::path();
$seo = App\Post::where('post_name', $page)->site()->first(); 
if($seo) {
    foreach ($seo->postmetas as $seometa) {
        $seo[$seometa->meta_key] = $seometa->meta_value;
    }        
}
?>
@if( Request::is('/') )
    <title>{{ @$setting->site_title  }}</title>
    <meta name="description" content="{{ @$setting->meta_description }}">
    <meta name="keywords" content="{{ @$setting->meta_keywords }}">
    <meta name="robots" content="noodp"/>
    <link rel="canonical" href="{{ Request::url() }}">

    <meta property="og:locale" content="{{ @$setting->site_language }}_US">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ @$setting->facebook_title }}">
    <meta property="og:description" content="{{ @$setting->facebook_description }}">
    <meta property="og:url" content="{{ Request::url() }}">
    <meta property="og:site_name" content="{{ @$setting->site_title  }}"> 
    @if( @$setting->facebook_image )
    <meta property="og:image" content="{{ asset(@$setting->facebook_image) }}"/>
    <meta property="og:image:width" content="450"/>
    <meta property="og:image:height" content="298"/>
    @endif

    <meta name="twitter:card" content="summary">
    <meta name="twitter:description" content="{{ @$setting->twitter_description }}">
    <meta name="twitter:title" content="{{ @$setting->twitter_title }}">
    @if( @$setting->twitter_image )
    <meta name="twitter:image" content="{{ asset(@$setting->twitter_image) }}">
    @endif
@else
    <title>{{ (@$seo->meta_title ? $seo->meta_title : @$seo->post_title) }}</title>
    <meta name="description" content="{{ @$seo->meta_description }}">
    <meta name="keywords" content="{{ @$seo->meta_keywords }}">
    <meta name="robots" content="noodp"/>
    <link rel="canonical" href="{{ Request::url() }}">

    <meta property="og:locale" content="{{ @$setting->site_language }}_US">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ @$seo->facebook_title }}">
    <meta property="og:description" content="{{ @$seo->facebook_description }}">
    <meta property="og:url" content="{{ Request::url() }}">
    <meta property="og:site_name" content="{{ @$setting->site_title  }}"> 
    @if( @$seo->facebook_image || @$seo->image)
    <meta property="og:image" content="{{ (@$seo->facebook_image) ? asset($seo->facebook_image) : asset(@$seo->image) }}"/>
    <meta property="og:image:width" content="450"/>
    <meta property="og:image:height" content="298"/>
    @endif

    <meta name="twitter:card" content="summary">
    <meta name="twitter:description" content="{{ @$seo->twitter_description }}">
    <meta name="twitter:title" content="{{ @$seo->twitter_title }}">
    @if( @$seo->twitter_image || @$seo->image)
    <meta name="twitter:image" content="{{ (@$seo->twitter_image) ? asset($seo->twitter_image) : asset(@$seo->image) }}">
    @endif
@endif