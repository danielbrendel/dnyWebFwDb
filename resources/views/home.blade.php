@extends('layout')

@section('title', '')

@section('content')
    <div class="container">
        <div class="columns">
            <div class="column is-1"></div>

            <div class="column is-10">
                <div id="framework-content"></div>
            </div>

            <div class="column is-1"></div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        window.paginate = null;
        window.filterLang = '_all_';
        window.sorting = 'latest';
        window.filterText = null;
        window.filterTag = null;

        window.queryFrameworkItems = function() {
            let content = document.getElementById('framework-content');

            content.innerHTML += '<div id="spinner"><center><i class="fa fa-spinner fa-spin"></i></center></div>';

            let loadmore = document.getElementById('loadmore');
            if (loadmore) {
                loadmore.remove();
            }

            window.vue.ajaxRequest('post', '{{ url('/framework/query') }}', {
                paginate: window.paginate,
                lang: window.filterLang,
                sorting: window.sorting,
                text_search: window.filterText,
                tag: window.filterTag
            },
            function(response) {
                if (response.code == 200) {
                    response.data.forEach(function(elem, index) {
                        let html = window.vue.renderFrameworkItem(elem);

                        content.innerHTML += html;
                    });

                    if (response.data.length > 0) {
                        if (window.sorting === 'latest') {
                            window.paginate = response.data[response.data.length - 1].id;
                        } else if (window.sorting === 'hearts') {
                            window.paginate = response.data[response.data.length - 1].hearts;
                        }
                    }

                    let spinner = document.getElementById('spinner');
                    if (spinner) {
                        spinner.remove();
                    }

                    if (response.data.length === 0) {
                        content.innerHTML += '<div><br/>{{ __('app.no_more_items') }}</div>';
                    } else {
                        content.innerHTML += '<div id="loadmore"><center><br/><i class="fas fa-plus is-pointer" onclick="window.queryFrameworkItems();"></i></center></div>';
                    }
                } else {
                    console.error(response.msg);
                }
            });
        };

        document.addEventListener('DOMContentLoaded', function() {
            window.queryFrameworkItems();
        });
    </script>
@endsection