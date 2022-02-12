{{--
    WebframeworkDB (dnyWebFwDb) developed by Daniel Brendel

    (C) 2022 by Daniel Brendel

    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_email')

@section('title')
    {{ __('app.mail_item_approved_title') }}
@endsection

@section('body')
    {{ __('app.mail_salutation', ['name' => $username]) }}
    <br/><br/>
    {{ __('app.mail_item_approved_body', ['name' => $name]) }}
@endsection

@section('action')
    <a class="button" href="{{ $url }}">{{ __('app.mail_view_item') }}</a>
@endsection