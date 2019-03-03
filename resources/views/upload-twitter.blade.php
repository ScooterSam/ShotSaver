@extends('layouts.app-twitter')

@section('meta')

    <meta content="{{request()->fullUrl()}}" property="og:url" />
    @if($type == 'image')
        <meta name="twitter:card" content="summary_large_image" />
        <meta property="og:image" content="{{$file->link}}">
        <meta property="og:image:type" content="{{$file->mime_type}}">
        <meta property="og:type" content="image">

        @if($dimensions != null)
            <meta property="og:image:width" content="{{$dimensions['width']}}" />
            <meta property="og:image:height" content="{{$dimensions['height']}}" />
        @endif

        {{--@elseif($type == 'video')

            <meta property="og:video" content="{{$file->link}}">
            <meta property="og:video:width" content="1920">
            <meta property="og:video:height" content="1080">
            <meta property="og:video:type" content="application/mp4">
            <meta property="og:type" content="video.other">
            <meta property="og:image" content="{{$file->thumbnail_url}}">
        @else--}}
        <meta content="article" property="og:type" />
    @endif

    <meta name="theme-color" content="#0084da">
    <meta name="twitter:site" content="@ShotSaver" />
    <meta name="twitter:title" content="ShotSaver" />
    <meta name="og:site_name" content="ShotSaver" />
    <meta name="twitter:description" content="An {{$type}} uploaded to ShotSaver by {{$file->user->name}}" />
    <meta name="description" content="Check out this video on Streamable using your phone, tablet or desktop.">
    <meta property="og:description" content="An {{$type}} uploaded to ShotSaver by {{$file->user->name}}" />

    @if($type == 'video')
        <meta property="og:type" content="video.other">
        <meta property="og:image"
              content="{{$file->thumbnail_url}}" />
        <meta property="og:image:secure_url"
              content="{{$file->thumbnail_url}}" />
        <meta property="og:image:type" content="image/png" />
        <meta property="og:image:width" content="1280">
        <meta property="og:image:height" content="720">

        <meta property="og:updated_time" content="{{$file->created_at->toAtomString() }}" />
        <meta property="og:video"
              content="{{$file->link}}">
        <meta property="og:video:url"
              content="{{$file->link}}">
        <meta property="og:video:secure_url"
              content="{{$file->link}}">
        <meta property="og:video:type" content="video/mp4">
        <meta property="og:video:width" content="1280">
        <meta property="og:video:height" content="720">

        <meta name="twitter:card" content="player">
        <meta name="twitter:site" content="@ShotSaver">
        <meta name="twitter:image"
              content="{{$file->thumbnail_url}}">
        <meta name="twitter:player:width" content="1280">
        <meta name="twitter:player:height" content="720">
        <meta name="twitter:player" content="{{$file->link}}">
        <meta name="twitter:player:stream"
              content="{{$file->link}}">
        <meta name="twitter:player:stream:content_type" content="video/mp4">
    @endif



@endsection

@section('content')
    @if($type === 'image')
        <div class="preview">
            <img src="{{$file->link}}" alt="" class="img-responsive center-block">
            <div class="overlay">
                <a href="{{$file->link}}" target="_blank">
                    <i class="fa fa-external-link"></i>
                </a>
            </div>
        </div>
    @elseif($type === 'video')
        <video src="{{$file->link}}" style="width: 100%;" controls="true"></video>
    @elseif($type === 'audio')
        <audio src="{{$file->link}}" style="width: 100%;" controls="true"></audio>
    @elseif($type === 'compressed')
        <div class="text-center">
            <a href="{{$file->link}}" class="btn btn-lg btn-primary" download>
                <i class="fa fa-download"></i> Download File
            </a>
        </div>
    @endif

@endsection
