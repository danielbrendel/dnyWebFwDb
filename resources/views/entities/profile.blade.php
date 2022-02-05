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

                    <div class="profile-footer">
                        <i class="fab fa-twitter"></i>&nbsp;<a href="https://twitter.com/{{ $profile->twitter }}">&#64;{{ $profile->twitter }}</a>
                    </div>
                </div>

                <div class="profile-framework-items-hint">{{ __('app.items_by_user') }}</div>
                <div id="framework-content"></div>
            </div>

            <div class="column is-1"></div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        window.user = {{ $profile->id }};
        window.paginate = null;

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
                        content.innerHTML += '<div><br/>{{ __('app.no_more_items') }}</div>';
                    } else {
                        content.innerHTML += '<div id="loadmore"><center><br/><i class="fas fa-plus is-pointer" onclick="window.queryUserFrameworks();"></i></center></div>';
                    }
                } else {
                    console.error(response.msg);
                }
            });
        };

        document.addEventListener('DOMContentLoaded', function() {
            window.queryUserFrameworks();
        });
    </script>
@endsection