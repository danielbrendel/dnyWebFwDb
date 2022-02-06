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
        <center>
            <div class="navbar-item has-dropdown is-hoverable is-inline-block">
                <a class="navbar-link is-top-5" href="javascript:void(0);" onclick="window.vue.toggleDropdown(document.getElementById('language-dropdown'));">
                    {{ __('app.select_language') }}
                </a>

                <div class="navbar-dropdown is-dropdown-aligned is-hidden" id="language-dropdown">
                    @foreach (\App\Models\LanguageModel::getLanguages() as $item)
                        <a class="navbar-item" href="#">{{ $item->language }}</a>
                    @endforeach
                </div>
            </div>

            <div class="navbar-item has-dropdown is-hoverable is-inline-block">
                <a class="navbar-link is-top-5" href="javascript:void(0);" onclick="window.vue.toggleDropdown(document.getElementById('sorting-dropdown'));">
                    {{ __('app.select_sorting') }}
                </a>

                <div class="navbar-dropdown is-dropdown-aligned is-hidden" id="sorting-dropdown">
                    <a class="navbar-item" href="#">{{ __('app.sorting_latest') }}</a>
                    <a class="navbar-item" href="#">{{ __('app.sorting_hearts') }}</a>
                </div>
            </div>

            <div class="control has-icons-right is-inline-block is-mobile-top-5">
                <input class="input is-border-rounded is-input-navbar" type="text" placeholder="{{ __('app.search_framework') }}" onkeypress="if (event.which === 13) location.href='{{ url('/') }}';">

                <span class="icon is-small is-right">
                    <i class="fas fa-search"></i>
                </span>
            </div>
        </center>
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
                    <i class="fas fa-upload fa-lg is-pointer" title="{{ __('app.submit_framework') }}" onclick="location.href='{{ url('/submit') }}';"></i>&nbsp;<span class="is-mobile-like-screen-width"><a class="is-color-grey" href="javascript:void(0);" onclick="location.href='{{ url('/submit') }}';">{{ __('app.submit_framework') }}</a></span>
                </div>
            </div>

            <div class="navbar-item">
                <div>
                    <i id="notification-indicator" class="far fa-heart fa-lg is-pointer" onclick="clearPushIndicator(this, document.getElementById('burger-notification')); toggleNotifications('notifications'); window.markSeen(); if (window.menuVisible) {document.getElementById('navbarMenu').classList.remove('is-active'); document.getElementById('navbarBurger').classList.remove('is-active'); }" title="{{ __('app.notifications') }}"></i>&nbsp;<span class="is-mobile-like-screen-width"><a class="is-color-grey" href="javascript:void(0);" onclick="clearPushIndicator(this, document.getElementById('burger-notification')); toggleNotifications('notifications'); if (window.menuVisible) {document.getElementById('navbarMenu').classList.remove('is-active'); document.getElementById('navbarBurger').classList.remove('is-active'); }">{{ __('app.notifications') }}</a></span>
                </div>
            </div>

            <div class="navbar-item">
                <div class="is-top-5">
                    <img class="avatar is-pointer" src="{{ asset('gfx/avatars/' . $user->avatar) }}" title="{{ __('app.profile') }}"  onclick="location.href='{{ url('/profile') }}';">&nbsp;<span class="is-mobile-like-screen-width"><a class="is-color-grey" href="javascript:void(0);" onclick="location.href='{{ url('/profile') }}';">{{ __('app.profile') }}</a></span>
                </div>
            </div>

            @if ($user->admin)
            <div class="navbar-item">
                <div>
                    <i class="fas fa-tools is-pointer" title="{{ __('app.admin_area') }}"  onclick="location.href='{{ url('/admin') }}';"></i>&nbsp;<span class="is-mobile-like-screen-width"><a class="is-color-grey" href="javascript:void(0);" onclick="location.href='{{ url('/admin') }}';">{{ __('app.admin_area') }}</a></span>
                </div>
            </div>
            @endif

            <div class="navbar-item">
                <div>
                    <i class="fas fa-sign-out-alt fa-lg is-pointer" title="{{ __('app.logout') }}"  onclick="location.href='{{ url('/logout') }}';"></i>&nbsp;<span class="is-mobile-like-screen-width"><a class="is-color-grey" href="javascript:void(0);" onclick="location.href='{{ url('/logout') }}';">{{ __('app.logout') }}</a></span>
                </div>
            </div>
            @endauth
        </div>
    </div>
</nav>