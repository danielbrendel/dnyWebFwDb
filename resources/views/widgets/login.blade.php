{{--
    WebframeworkDB (dnyWebFwDb) developed by Daniel Brendel

    (C) 2022 by Daniel Brendel

    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

<div class="modal" :class="{'is-active': bShowRegister}">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head is-stretched">
            <p class="modal-card-title">{{ __('app.register') }}</p>
            <button class="delete" aria-label="close" onclick="vue.bShowRegister = false;"></button>
        </header>
        <section class="modal-card-body is-stretched">
            <form id="regform" method="POST" action="{{ url('/register') }}">
                @csrf

                <div class="field">
                    <label class="label">{{ __('app.register_username') }}</label>
                    <div class="control">
                        <input class="input" type="text" name="username" onchange="window.vue.showUsernameValidity(this.value, document.getElementById('reg-username-validity'));" onkeyup="window.vue.showUsernameValidity(this.value, document.getElementById('reg-username-validity'));" value="{{ old('username') }}" required>
                    </div>
                    <p id="reg-username-validity" class="help"></p>
                </div>

                <div class="field">
                    <label class="label">{{ __('app.register_email') }}</label>
                    <div class="control">
                        <input class="input" type="email" name="email" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">{{ __('app.register_password') }}</label>
                    <div class="control">
                        <input class="input" type="password" name="password" id="reg-password" onchange="window.vue.showPasswordMatching(document.getElementById('reg-password-confirm').value, this.value, document.getElementById('reg-password-matching'));" onkeyup="window.vue.showPasswordMatching(document.getElementById('reg-password-confirm').value, this.value, document.getElementById('reg-password-matching'));" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">{{ __('app.register_password_confirmation') }}</label>
                    <div class="control">
                        <input class="input" type="password" name="password_confirmation" id="reg-password-confirm" onchange="window.vue.showPasswordMatching(document.getElementById('reg-password').value, this.value, document.getElementById('reg-password-matching'));" onkeyup="window.vue.showPasswordMatching(document.getElementById('reg-password').value, this.value, document.getElementById('reg-password-matching'));" required>
                    </div>
                    <p id="reg-password-matching" class="help"></p>
                </div>

                <div class="field">
                    <label class="label">Captcha: {{ $captcha[0] }} + {{ $captcha[1] }} = ?</label>
                    <div class="control">
                        <input class="input" type="text" name="captcha" required>
                    </div>
                </div>

                <div class="field">
                    {!! \App\Models\AppModel::getRegInfo()  !!}
                </div>
            </form>
        </section>
        <footer class="modal-card-foot is-stretched">
            <span>
                <button class="button is-success" onclick="document.getElementById('regform').submit();">{{ __('app.register') }}</button>
                <button class="button" onclick="vue.bShowRegister = false;">{{ __('app.cancel') }}</button>
            </span>
        </footer>
    </div>
</div>

<div class="modal" :class="{'is-active': bShowRecover}">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head is-stretched">
            <p class="modal-card-title">{{ __('app.recover_password') }}</p>
            <button class="delete" aria-label="close" onclick="vue.bShowRecover = false;"></button>
        </header>
        <section class="modal-card-body is-stretched">
            <form method="POST" action="{{ url('/recover') }}" id="formResetPw">
                @csrf

                <div class="field">
                    <label class="label">{{ __('app.email') }}</label>
                    <div class="control">
                        <input type="email" onkeyup="javascript:invalidRecoverEmail()" onchange="javascript:invalidRecoverEmail()" onkeydown="if (event.keyCode === 13) { document.getElementById('formResetPw').submit(); }" class="input" name="email" id="recoveremail" required>
                    </div>
                </div>

                <input type="submit" id="recoverpwsubmit" class="is-hidden">
            </form>
        </section>
        <footer class="modal-card-foot is-stretched">
            <button class="button is-success" onclick="document.getElementById('recoverpwsubmit').click();">{{ __('app.recover_password') }}</button>
            <button class="button" onclick="vue.bShowRecover = false;">{{ __('app.cancel') }}</button>
        </footer>
    </div>
</div>

<div class="modal" :class="{'is-active': bShowLogin}">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head is-stretched">
            <p class="modal-card-title">{{ __('app.login') }}</p>
            <button class="delete" aria-label="close" onclick="vue.bShowLogin = false;"></button>
        </header>
        <section class="modal-card-body is-stretched">
            <form method="POST" action="{{ url('/login') }}">
                @csrf

                <div class="field">
                    <label class="label">{{ __('app.email') }}</label>
                    <div class="control has-icons-left has-icons-right">
                        <input class="input" type="email" name="email" placeholder="name@domain.tld" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                </div>

                <div class="field">
                    <label class="label">{{ __('app.password') }}</label>
                    <div class="control has-icons-left">
                        <input class="input" type="password" name="password" placeholder="{{ __('app.password') }}" required>
						<span class="icon is-small is-left">
                            <i class="fas fa-unlock"></i>
                        </span>
                    </div>
                </div>
				
				<div class="field">
					<div class="control">
						<a href="javascript:void(0);" onclick="window.vue.bShowRegister = true; window.vue.bShowLogin = false;">{{ __('app.no_account_yet') }}</a>
					</div>
				</div>

                <div class="home-userarea-login">
                    <div class="field is-inline-block">
                        <div class="control">
                            <button class="button is-link">{{ __('app.login') }}</button>
                        </div>
                    </div>

                    <div class="home-userarea-recover">
                        <a href="javascript:void(0)" onclick="window.vue.bShowLogin = false; window.vue.bShowRecover = true">{{ __('app.recover_password') }}</a>
                    </div>
                </div>
            </form>
        </section>
        <footer class="modal-card-foot is-stretched">
        </footer>
    </div>
</div>