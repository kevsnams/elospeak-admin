@extends('layouts.app')

@section('pageHeader')
    <span class="uk-text-large">Welcome { ADMIN_NAME }</span>
@endsection

@section('content')
    <div style="height: 800px" class="uk-text-center">
        <img src="<?php echo url('/splash.png') ?>">
        <div class="uk-text-lead" style="font-weight: 200">Coming Soon</div>
    </div>
@endsection
