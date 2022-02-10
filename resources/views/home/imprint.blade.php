{{--
    WebframeworkDB (dnyWebFwDb) developed by Daniel Brendel

    (C) 2022 by Daniel Brendel

    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_view')

@section('content')
    <div class="container">
        <div class="columns">
            <div class="column is-2"></div>

            <div class="column is-8">
                <div class="page-content">
                    {!! $imprint !!}
                </div>
            </div>

            <div class="column is-2"></div>
        </div>
    </div>
@endsection
