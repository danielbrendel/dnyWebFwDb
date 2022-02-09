{{--
    WebframeworkDB (dnyWebFwDb) developed by Daniel Brendel

    (C) 2022 by Daniel Brendel

    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

<div class="footer">
    <div class="columns">
        <div class="column is-4"></div>

        <div class="column is-4">
            <div class="footer-frame">
                <div class="footer-content">
                    &copy; {{ date('Y') }} by {{ env('APP_AUTHOR') }} | <span><a href="{{ url('/imprint') }}">{{ __('app.imprint') }}</a></span>&nbsp;&bull;&nbsp;<span><a href="{{ url('/tos') }}">{{ __('app.terms_of_service') }}</a></span> | <span class="is-pointer" title="GitHub" onclick="window.open('{{ env('APP_LINK_GITHUB') }}');"><i class="fab fa-github"></i></span>&nbsp;&nbsp;&nbsp;<span class="is-pointer" title="Twitter" onclick="window.open('{{ env('APP_LINK_TWITTER') }}');"><i class="fab fa-twitter"></i></span>
                </div>
            </div>
        </div>

        <div class="column is-4"></div>
    </div>
</div>