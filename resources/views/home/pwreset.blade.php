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
                <h1>{{ __('app.password_reset') }}</h1>

                <div>
                    <form method="POST" action="{{ url('/reset?hash=' . $hash) }}">
                        @csrf

                        <div class="field">
                            <label class="label">{{ __('app.password') }}</label>
                            <p class="control has-icons-left">
                                <input class="input" type="password" name="password" placeholder="{{ __('app.password') }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </p>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.password_confirmation') }}</label>
                            <p class="control has-icons-left">
                                <input class="input" type="password" name="password_confirm" placeholder="{{ __('app.password_confirmation') }}">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </p>
                        </div>

                        <div class="field">
                            <input class="button is-info" type="submit" value="{{ __('app.reset') }}">
                        </div>
                    </form>
                </div>
            </div>

            <div class="column is-2"></div>
        </div>
    </div>
@endsection
