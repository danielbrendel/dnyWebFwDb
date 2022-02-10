{{--
    WebframeworkDB (dnyWebFwDb) developed by Daniel Brendel

    (C) 2022 by Daniel Brendel

    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_view')

@section('title', ' - ' . $profile->name)

@section('content')
    <div class="container">
        <div class="columns">
            <div class="column is-1"></div>

            <div class="column is-10">
                <div class="profile">
                    <div class="profile-header">
                        <div class="profile-header-left">
                            <img src="{{ asset('gfx/avatars/' . $profile->avatar) }}" width="64" height="64"/>
                        </div>

                        <div class="profile-header-right">
                            <div class="profile-header-right-name">
                                {{ $profile->username }}
                            </div>

                            <div class="profile-header-right-location">
                                <i class="fas fa-map-marker-alt"></i>&nbsp;{{ $profile->location }}
                            </div>
                        </div>
                    </div>

                    <div class="profile-bio">
                        {{ $profile->bio }}
                    </div>

                    @if ((is_string($profile->twitter)) && (strlen($profile->twitter) > 0))
                        <div class="profile-footer">
                            <i class="fab fa-twitter"></i>&nbsp;<a href="https://twitter.com/{{ $profile->twitter }}">&#64;{{ $profile->twitter }}</a>
                        </div>
                    @endif

                    @auth
                        @if ($profile->id === $user->id)
                            <div class="edit-profile">
                                <a href="javascript:void(0);" onclick="window.vue.bShowEditProfile = true;">{{ __('app.edit_profile') }}</a>
                            </div>
                        @else
                            <div class="report-profile">
                                <a href="javascript:void(0);" onclick="window.vue.reportUser({{ $profile->id }});">{{ __('app.report') }}</a>
                            </div>
                        @endif
                    @endauth
                </div>

                <div class="profile-framework-items-hint">{{ __('app.items_by_user') }}</div>
                <div id="framework-content"></div>

                <div class="reviews">
                    <div class="reviews-hint">
                        {{ __('app.reviews_by_user') }}
                    </div>

                    <div class="reviews-content" id="review-content"></div>
                </div>

                @auth
                    @if ($profile->id === $user->id)
                        <div class="modal" :class="{'is-active': bShowEditProfile}">
                            <div class="modal-background"></div>
                            <div class="modal-card">
                                <header class="modal-card-head is-stretched">
                                    <p class="modal-card-title">{{ __('app.edit_profile') }}</p>
                                    <button class="delete" aria-label="close" onclick="vue.bShowEditProfile = false;"></button>
                                </header>
                                <section class="modal-card-body is-stretched">
                                    <form method="POST" action="{{ url('/profile/save') }}" id="formEditProfile" enctype="multipart/form-data">
                                        @csrf

                                        <div class="field">
                                        <label class="label">{{ __('app.avatar') }}</label>
                                            <div class="control">
                                                <input type="file" class="input" name="avatar" data-role="file" data-button-title="{{ __('app.select_avatar') }}">
                                            </div>
                                            <p class="help">{{ __('app.profile_avatar_hint') }}</p>
                                        </div>

                                        <div class="field">
                                            <label class="label">{{ __('app.location') }}</label>
                                            <div class="control">
                                                <input type="text" class="input" name="location" value="{{ $profile->location }}">
                                            </div>
                                        </div>

                                        <div class="field">
                                            <label class="label">{{ __('app.bio') }}</label>
                                            <div class="control">
                                                <textarea name="bio" class="input">{{ $profile->bio }}</textarea>
                                            </div>
                                        </div>

                                        <div class="field">
                                            <label class="label">{{ __('app.twitter') }}</label>
                                            <div class="control">
                                                <input type="text" class="input" name="twitter" value="{{ $profile->twitter }}">
                                            </div>
                                        </div>

                                        <hr/>

                                        <div class="field">
                                            <label class="label">{{ __('app.password') }}</label>
                                            <div class="control">
                                                <input type="password" class="input" name="password">
                                            </div>
                                        </div>

                                        <div class="field">
                                            <label class="label">{{ __('app.password_confirmation') }}</label>
                                            <div class="control">
                                                <input type="password" class="input" name="password_confirmation">
                                            </div>
                                        </div>

                                        <hr/>

                                        <div class="field">
                                            <label class="label">{{ __('app.email') }}</label>
                                            <div class="control">
                                                <input type="email" class="input" name="email" value="{{ $profile->email }}">
                                            </div>
                                        </div>

                                        <hr/>

                                        <div class="field">
                                            <div class="control">
                                                <input type="checkbox" name="newsletter" value="1" @if ($user->newsletter) {{ 'checked' }} @endif>
                                                <label for="newsletter">{{ __('app.subscribe_newsletter') }}</label>
                                            </div>
                                        </div>

                                        <input type="submit" id="editprofilesubmit" class="is-hidden">
                                    </form>

                                    <hr/>

                                    <div class="field">
                                        <div class="control">
                                            <button class="button is-danger" onclick="window.vue.deleteAccount();">{{ __('app.delete_account') }}</button>
                                        </div>
                                    </div>
                                </section>
                                <footer class="modal-card-foot is-stretched">
                                    <button class="button is-success" onclick="document.getElementById('editprofilesubmit').click();">{{ __('app.save') }}</button>
                                    <button class="button" onclick="vue.bShowEditProfile = false;">{{ __('app.cancel') }}</button>
                                </footer>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>

            <div class="column is-1"></div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        window.user = {{ $profile->id }};
        window.paginate = null;

        @auth
            @if ($user->admin)
                window.isAdmin = true;
            @else
                window.isAdmin = false;
            @endif
        @elseguest
            window.isAdmin = false;
        @endauth

        window.queryUserFrameworks = function() {
            let content = document.getElementById('framework-content');

            content.innerHTML += '<div id="spinner"><center><i class="fa fa-spinner fa-spin"></i></center></div>';

            let loadmore = document.getElementById('loadmore');
            if (loadmore) {
                loadmore.remove();
            }

            window.vue.ajaxRequest('post', '{{ url('/framework/query/user') }}', {
                user: window.user,
                paginate: window.paginate
            },
            function(response) {
                if (response.code == 200) {
                    response.data.forEach(function(elem, index) {
                        let html = window.vue.renderFrameworkItem(elem);

                        content.innerHTML += html;
                    });

                    if (response.data.length > 0) {
                        window.paginate = response.data[response.data.length - 1].id;
                    }

                    let spinner = document.getElementById('spinner');
                    if (spinner) {
                        spinner.remove();
                    }

                    if (response.data.length === 0) {
                        content.innerHTML += '<div><br/><center>{{ __('app.no_more_items') }}</center></div>';
                    } else {
                        content.innerHTML += '<div id="loadmore"><center><br/><i class="fas fa-plus is-pointer" onclick="window.queryUserFrameworks();"></i></center></div>';
                    }
                } else {
                    console.error(response.msg);
                }
            });
        };

        window.queryUserReviews = function() {
            let content = document.getElementById('review-content');

            content.innerHTML += '<div id="spinner2"><center><i class="fa fa-spinner fa-spin"></i></center></div>';

            let loadmore = document.getElementById('loadmore2');
            if (loadmore) {
                loadmore.remove();
            }

            window.vue.ajaxRequest('post', '{{ url('/user/query/reviews') }}', {
                userId: window.user,
                paginate: window.paginate
            },
            function(response) {
                if (response.code == 200) {
                    response.data.forEach(function(elem, index) {
                        let html = window.vue.renderReview(elem, window.userId, window.isAdmin);

                        content.innerHTML += html;
                    });

                    if (response.data.length > 0) {
                        window.paginate = response.data[response.data.length - 1].id;
                    }

                    let spinner = document.getElementById('spinner2');
                    if (spinner) {
                        spinner.remove();
                    }

                    if (response.data.length === 0) {
                        content.innerHTML += '<div><br/><center>{{ __('app.no_more_items') }}</center></div>';
                    } else {
                        content.innerHTML += '<div id="loadmore2"><center><br/><i class="fas fa-plus is-pointer" onclick="window.queryUserReviews();"></i></center></div>';
                    }
                } else {
                    console.error(response.msg);
                }
            });
        };

        document.addEventListener('DOMContentLoaded', function() {
            window.queryUserFrameworks();
            window.queryUserReviews();
        });
    </script>
@endsection