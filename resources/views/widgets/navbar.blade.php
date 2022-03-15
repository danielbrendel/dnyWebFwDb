{{--
    WebframeworkDB (dnyWebFwDb) developed by Daniel Brendel

    (C) 2022 by Daniel Brendel

    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

<nav class="navbar has-border-bottom is-dark" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item is-title-font" href="{{ url('/') }}">{{ env('APP_NAME') }}</a>

        <a id="navbarBurger" role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarMenu" onclick="window.menuVisible = !document.getElementById('navbarMenu').classList.contains('is-active');">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span id="burger-notification"></span>
        </a>
    </div>

    <div id="navbarMenu" class="navbar-menu">
        <div class="navbar-start"></div>

        @if ((isset($fw_item_filter)) && ($fw_item_filter))
        <div class="navbar-options">
            <center>
                <div class="navbar-item has-dropdown is-hoverable is-inline-block">
                    <a class="navbar-link is-top-5" href="javascript:void(0);" onclick="window.vue.toggleDropdown(document.getElementById('language-dropdown'));">
                        {{ __('app.select_language') }}
                    </a>

                    <div class="navbar-dropdown is-dropdown-aligned is-hidden" id="language-dropdown">
                        @foreach (\App\Models\LanguageModel::getLanguages() as $item)
                            <a class="navbar-item" href="{{ url('/') }}?lang={{ $item->slug }}">{{ $item->language }}</a>
                        @endforeach
                    </div>
                </div>

                <div class="control has-icons-right is-inline-block is-mobile-top-5">
                    <input class="input is-border-rounded is-input-navbar" type="text" placeholder="{{ __('app.search_framework') }}" value="@if (isset($_GET['text_search'])) {{ $_GET['text_search'] }} @endif" onkeypress="if (event.which === 13) location.href='{{ url('/') }}?text_search=' + this.value;">

                    <span class="icon is-small is-right">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </center>
        </div>
        @endif

        <div class="navbar-end">
            @guest
                <div class="navbar-item">
                    <div class="buttons">
                        <a class="button is-primary is-bold" href="javascript:void(0);" onclick="vue.bShowRegister = true; if (window.menuVisible) { document.getElementById('navbarMenu').classList.remove('is-active'); document.getElementById('navbarBurger').classList.remove('is-active'); }">
                            {{ __('app.register') }}
                        </a>
                        <a class="button is-light" href="javascript:void(0);" onclick="vue.bShowLogin = true; if (window.menuVisible) { document.getElementById('navbarMenu').classList.remove('is-active'); document.getElementById('navbarBurger').classList.remove('is-active'); }">
                            {{ __('app.login') }}
                        </a>
                    </div>
                </div>
            @endguest

            @auth
            <div class="navbar-item">
                <div>
                    <i class="fas fa-upload fa-lg is-pointer is-color-nav-bright" title="{{ __('app.submit_framework') }}" onclick="location.href='{{ url('/submit') }}';"></i>&nbsp;<span class="is-mobile-like-screen-width"><a class="is-color-grey" href="javascript:void(0);" onclick="location.href='{{ url('/submit') }}';">{{ __('app.submit_framework') }}</a></span>
                </div>
            </div>

            <div class="navbar-item">
                <div>
                    <i class="far fa-bell fa-lg is-pointer notification-badge" title="{{ __('app.notifications') }}" onclick="window.vue.toggleOverlay('notifications'); document.getElementById('navbar-notify-wrapper').classList.add('is-hidden'); document.getElementById('burger-notification').style.display = 'none'; window.vue.markSeen(); if (window.menuVisible) { document.getElementById('navbarMenu').classList.remove('is-active'); document.getElementById('navbarBurger').classList.remove('is-active'); }">
                        <span class="notify-badge is-hidden" id="navbar-notify-wrapper"><span class="notify-badge-count" id="navbar-notify-count"></span></span>
                    </i>
                    
                    <span class="is-mobile-like-screen-width">
                        <a class="is-color-grey" href="javascript:void(0);" onclick="window.vue.toggleOverlay('notifications'); document.getElementById('navbar-notify-wrapper').classList.add('is-hidden'); document.getElementById('burger-notification').style.display = 'none'; window.vue.markSeen(); if (window.menuVisible) { document.getElementById('navbarMenu').classList.remove('is-active'); document.getElementById('navbarBurger').classList.remove('is-active'); }">{{ __('app.notifications') }}</a>
                    </span>
                </div>
            </div>

            <div class="navbar-item">
                <div class="is-mobile-like-left-5-n @if ((!isset($metro)) || ($metro === false)) is-top-5 @endif">
                    <img class="avatar is-pointer" src="{{ asset('gfx/avatars/' . $user->avatar) }}" title="{{ __('app.profile') }}"  onclick="location.href='{{ url('/profile') }}';">&nbsp;<span class="is-mobile-like-screen-width is-mobile-like-top"><a class="is-color-grey" href="javascript:void(0);" onclick="location.href='{{ url('/profile') }}';">{{ __('app.profile') }}</a></span>
                </div>
            </div>

            @if ($user->admin)
            <div class="navbar-item">
                <div>
                    <i class="fas fa-tools is-pointer is-color-nav-bright" title="{{ __('app.admin_area') }}"  onclick="location.href='{{ url('/admin') }}';"></i>&nbsp;<span class="is-mobile-like-screen-width"><a class="is-color-grey" href="javascript:void(0);" onclick="location.href='{{ url('/admin') }}';">{{ __('app.admin_area') }}</a></span>
                </div>
            </div>
            @endif

            <div class="navbar-item">
                <div>
                    <i class="fas fa-sign-out-alt fa-lg is-pointer is-color-nav-bright" title="{{ __('app.logout') }}"  onclick="location.href='{{ url('/logout') }}';"></i>&nbsp;<span class="is-mobile-like-screen-width"><a class="is-color-grey" href="javascript:void(0);" onclick="location.href='{{ url('/logout') }}';">{{ __('app.logout') }}</a></span>
                </div>
            </div>
            @endauth
        </div>
    </div>
</nav>