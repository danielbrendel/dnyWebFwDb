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

        <center>
            <div class="navbar-item has-dropdown is-hoverable is-inline-block">
                <a class="navbar-link" href="javascript:void(0);" onclick="window.vue.toggleDropdown(document.getElementById('language-dropdown'));">
                    {{ __('app.select_language') }}
                </a>

                <div class="navbar-dropdown is-dropdown-aligned is-hidden" id="language-dropdown">
                    @foreach (\App\Models\LanguageModel::getLanguages() as $item)
                        <a class="navbar-item" href="#">{{ $item->language }}</a>
                    @endforeach
                </div>
            </div>

            <div class="control has-icons-right is-inline-block">
                <input class="input is-border-rounded" type="text" placeholder="{{ __('app.search_framework') }}" onkeypress="if (event.which === 13) location.href='{{ url('/') }}';">

                <span class="icon is-small is-right">
                    <i class="fas fa-search"></i>
                </span>
            </div>
        </center>

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