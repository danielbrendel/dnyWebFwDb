{{--
    WebframeworkDB (dnyWebFwDb) developed by Daniel Brendel

    (C) 2022 by Daniel Brendel

    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="author" content="{{ env('APP_AUTHOR') }}">
        <meta name="description" content="{{ env('APP_DESCRIPTION') }}">
        <meta name="tags" content="{{ env('APP_TAGS') }}">

        <title>{{ env('APP_NAME') }}@yield('title')</title>

        <link rel="stylesheet" type="text/css" href="{{ asset('css/bulma.css') }}">
        @if ((isset($metro)) && ($metro))
        <link rel="stylesheet" type="text/css" href="{{ asset('css/metro.datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/metro-all.min.css') }}">
        @endif
        <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">

        <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

        <script src="{{ asset('js/fontawesome.js') }}"></script>
        @if (env('APP_ENV') === 'production')
        <script src="{{ asset('js/vue.min.js') }}"></script>
        @else
        <script src="{{ asset('js/vue.js') }}"></script>
        @endif
        @if ((isset($metro)) && ($metro))
        <script src="{{ asset('js/metro.min.js') }}"></script>
        @endif

        {!! \App\Models\AppModel::getHeadCode() !!}
    </head>

    <body>
        <main id="main">
            @include('widgets.navbar')

            <div id="cookie-consent" class="cookie-consent has-text-centered is-top-53">
                <div class="cookie-consent-inner">
                    {!! \App\Models\AppModel::getCookieConsent() !!}
                </div>

                <div class="cookie-consent-button">
                    <a class="is-color-grey" href="javascript:void(0)" onclick="window.vue.clickedCookieConsentButton()">{{ __('app.cookie_consent_close') }}</a>
                </div>
            </div>

            <div class="content">
                @if ($errors->any())
                    <div id="error-message-1" class="is-z-index-3">
                        <article class="message is-danger">
                            <div class="message-header">
                                <p>{{ __('app.error') }}</p>
                                <button class="delete" aria-label="delete" onclick="document.getElementById('error-message-1').style.display = 'none';"></button>
                            </div>
                            <div class="message-body">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}<br/>
                                @endforeach
                            </div>
                        </article>
                        <br/>
                    </div>
                @endif

                @if (Session::has('error'))
                    <div id="error-message-2" class="is-z-index-3">
                        <article class="message is-danger">
                            <div class="message-header">
                                <p>{{ __('app.error') }}</p>
                                <button class="delete" aria-label="delete" onclick="document.getElementById('error-message-2').style.display = 'none';"></button>
                            </div>
                            <div class="message-body">
                                {!! Session::get('error') !!}
                            </div>
                        </article>
                        <br/>
                    </div>
                @endif

                <div class="flash is-flash-error" id="flash-error">
                    <p id="flash-error-content">
                        @if (Session::has('flash.error'))
                            {!! Session::get('flash.error') !!}
                        @endif
                    </p>
                </div>

                <div class="flash is-flash-success" id="flash-success">
                    <p id="flash-success-content">
                        @if (Session::has('flash.success'))
                            {!! Session::get('flash.success') !!}
                        @endif
                    </p>
                </div>

                @if (Session::has('notice'))
                    <div id="notice-message" class="is-z-index-3">
                        <article class="message is-info">
                            <div class="message-header">
                                <p>{{ __('app.notice') }}</p>
                                <button class="delete" aria-label="delete" onclick="document.getElementById('notice-message').style.display = 'none';"></button>
                            </div>
                            <div class="message-body">
                                {!! Session::get('notice') !!}
                            </div>
                        </article>
                        <br/>
                    </div>
                @endif

                @if (Session::has('success'))
                    <div id="success-message" class="is-z-index-3">
                        <article class="message is-success">
                            <div class="message-header">
                                <p>{{ __('app.success') }}</p>
                                <button class="delete" aria-label="delete" onclick="document.getElementById('success-message').style.display = 'none';"></button>
                            </div>
                            <div class="message-body">
                                {!! Session::get('success') !!}
                            </div>
                        </article>
                        <br/>
                    </div>
                @endif

                <div class="app-overlay" id="notifications">
                    <center><div class="overlay-arrow-up"></div></center>

                    <div>
                        <div class="is-inline-block"></div>
                        <div class="is-inline-block float-right overlay-close-action is-pointer" onclick="window.vue.toggleOverlay('notifications'); if (window.menuVisible) { document.getElementById('navbarMenu').classList.remove('is-active'); document.getElementById('navbarBurger').classList.remove('is-active'); }">{{ __('app.close') }}</div>
                    </div>

                    <div class="overlay-content" id="notification-content"></div>
                </div>

                @yield('content')
            </div>

            @include('widgets.about')
            @include('widgets.footer')
            @include('widgets.login')
        </main>

        <script src="{{ asset('js/app.js') }}"></script>
        <script>
            window.gotNewNotifications = false;
            window.fetchNotifications = function() {
                window.vue.ajaxRequest('get', '{{ url('/notifications/list?mark=0') }}', {}, function(response){
                    if (response.code === 200) {
                        if (response.data.length > 0) {
                            window.gotNewNotifications = true;

                            let noyet = document.getElementById('no-notifications-yet');
                            if (noyet) {
                                noyet.remove();
                            }

                            let indicator = document.getElementById('navbar-notify-wrapper');
                            if (indicator) {
                                indicator.classList.remove('is-hidden');

                                count = document.getElementById('navbar-notify-count');
                                if (count) {
                                    count.innerHTML = response.data.length;
                                }
                            }

                            let burgerSpan = document.getElementById('burger-notification');
                            if (burgerSpan) {
                                burgerSpan.style.display = 'unset';
                            }

                            response.data.forEach(function(elem, index) {
                                if (document.getElementById('notification-item-' + elem.id) === null) {
                                    let html = window.vue.renderNotification(elem, true); 
                                    document.getElementById('notification-content').innerHTML = html + document.getElementById('notification-content').innerHTML;
                                }
                            });
                        }
                    }
                });

                setTimeout('fetchNotifications()', 50000);
            };

            window.notificationPagination = null;
            window.fetchNotificationList = function() {
                document.getElementById('notification-content').innerHTML += '<center><i id="notification-spinner" class="fas fa-spinner fa-spin"></i></center>';

                let loader = document.getElementById('load-more-notifications');
                if (loader) {
                    loader.remove();
                }

                window.vue.ajaxRequest('get', '{{ url('/notifications/fetch') }}' + ((window.notificationPagination) ? '?paginate=' + window.notificationPagination : ''), {}, function(response) {
                    if (response.code === 200) {
                        if (response.data.length > 0) {
                            let noyet = document.getElementById('no-notifications-yet');
                            if (noyet) {
                                noyet.remove();
                            }

                            response.data.forEach(function(elem, index) {
                                let html = window.vue.renderNotification(elem);

                                document.getElementById('notification-content').innerHTML += html;
                            });

                            window.notificationPagination = response.data[response.data.length-1].id;

                            document.getElementById('notification-content').innerHTML += '<center><i id="load-more-notifications" class="fas fa-plus is-pointer" onclick="fetchNotificationList()"></i></center>';
                            document.getElementById('notification-spinner').remove();
                        } else {
                            if ((window.notificationPagination === null) && (!window.gotNewNotifications)) {
                                document.getElementById('notification-content').innerHTML = '<div id="no-notifications-yet"><center><i>{{ __('app.no_notifications_yet') }}</i></center></div>';
                            }

                            let loader = document.getElementById('load-more-notifications');
                            if (loader) {
                                loader.remove();
                            }

                            let spinner = document.getElementById('notification-spinner');
                            if (spinner) {
                                spinner.remove();
                            }
                        }
                    }
                });
            };

            document.addEventListener('DOMContentLoaded', function() {
                window.vue.initNavbar();

                @if (Session::has('flash.error'))
                    setTimeout('window.vue.showError()', 500);
                @endif

                @if (Session::has('flash.success'))
                    setTimeout('window.vue.showSuccess()', 500);
                @endif

                @auth
                    setTimeout('fetchNotifications()', 100);
                    setTimeout('fetchNotificationList()', 200);
                @endauth

                window.vue.handleCookieConsent();
            });
        </script>
        @yield('javascript')
    </body>
</html>