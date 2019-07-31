@extends('layouts.app')

@section('pageHeader')
    <span class="uk-text-large">Welcome { ADMIN_NAME }</span>
@endsection

@section('content')
    <div style="height: 800px">
        <div class="uk-child-width-1-3@m uk-grid-small uk-grid-match" uk-grid>
            <div>
                <div class="uk-card uk-card-default uk-card-body">
                    <h3 class="uk-card-title">Maybe some</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                </div>
            </div>
            <div>
                <div class="uk-card uk-card-primary uk-card-body">
                    <h3 class="uk-card-title">Site statistics</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                </div>
            </div>
            <div>
                <div class="uk-card uk-card-secondary uk-card-body">
                    <h3 class="uk-card-title">Here??</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                </div>
            </div>
        </div>
        <hr>
        <div class="uk-card uk-card-default uk-width-1-2@m">
            <div class="uk-card-header">
                <div class="uk-grid-small uk-flex-middle" uk-grid>
                    <div class="uk-width-auto">
                        <img class="uk-border-circle" width="40" height="40" src="http://placehold.it/40x40">
                    </div>
                    <div class="uk-width-expand">
                        <h3 class="uk-card-title uk-margin-remove-bottom">Korean student</h3>
                        <p class="uk-text-meta uk-margin-remove-top"><time datetime="2019-07-31T19:00">July 31, 2019</time></p>
                    </div>
                </div>
            </div>
            <div class="uk-card-body">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
            </div>
            <div class="uk-card-footer">
                <a href="#" class="uk-button uk-button-text">Read more</a>
            </div>
        </div>
    </div>
@endsection
