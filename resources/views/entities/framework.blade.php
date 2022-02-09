@extends('layouts.layout_view')

@section('title', ' - ' . $framework->name)

@section('content')
    <div class="container">
        <div class="columns">
            <div class="column is-1"></div>

            <div class="column is-10">
                <div class="framework-item-full">
                    <div class="framework-item-full-image" style="background-image: url('{{ url('/gfx/logos/' . $framework->logo) }}')"></div>

                    <div class="framework-item-full-about">
                        <div class="framework-item-full-about-title">
                            {{ $framework->name }}

                            @if (($user->admin) || ($user->id == $framework->userId))
                                <div class="is-inline-block is-pointer" title="{{ __('app.edit_framework') }}" onclick="location.href = '{{ url('/framework/' . $framework->id . '/edit') }}';"><i class="far fa-edit"></i></div>
                                <div class="is-inline-block is-pointer" title="{{ __('app.delete_framework') }}" onclick="window.vue.deleteFramework({{ $framework->id }});"><i class="fas fa-times"></i></div>
                            @endif

                            @if ($user->admin)
                                <div class="is-inline-block is-pointer" title="{{ __('app.lock_framework') }}" onclick="location.href = '{{ url('/admin/entity/lock/?id=' . $framework->id . '&type=ENT_FRAMEWORK') }}';"><i class="fas fa-lock"></i></div>
                            @endif
                        </div>

                        <div class="framework-item-full-about-hint">{{ $framework->summary }}</div>
                        <div class="framework-item-full-about-tags">
                            @foreach ($framework->tags as $tag)
                                @if (strlen($tag) > 0)
                                    <span><a href="{{ url('/') }}?tag={{ $tag }}">#{{ $tag }}</a>&nbsp;</span>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="framework-item-full-user">
                        {{ __('app.framework_creator', ['creator' => $framework->creator]) }} &bull; {!! __('app.framework_submitted_by', ['user' => $framework->userData->username, 'url' => url('/user/' . $framework->userData->username)]) !!}
                    </div>

                    <div class="framework-item-full-description is-wrap">{{ $framework->description }}</div>

                    <div class="framework-item-full-github">
                        @include('widgets.github', ['github' => $framework->github])
                    </div>

                    <div class="framework-item-full-links">
                        @if (($framework->twitter !== null) && (is_string($framework->twitter)) && (strlen($framework->twitter) > 0))
                        <div class="framework-item-full-links-twitter">
                            <i class="fab fa-twitter"></i>&nbsp;<a href="https://twitter.com/{{ $framework->twitter }}">{{ $framework->twitter }}</a>
                        </div>
                        @endif

                        @if (($framework->website !== null) && (is_string($framework->website)) && (strlen($framework->website) > 0))
                        <div class="framework-item-full-links-homepage">
                            <i class="fas fa-globe"></i>&nbsp;<a href="{{ $framework->website }}">{{ $framework->website }}</a>
                        </div>
                        @endif
                    </div>

                    <div class="framework-item-full-stats">
                        <div class="framework-item-full-stats-stars">
                            @for ($i = 0; $i < $framework->avg_stars; $i++)
                                <span class="review-star-color"><i class="fas fa-star"></i></span>
                            @endfor

                            @if ($framework->avg_stars < 5)
                                @for ($j = $framework->avg_stars; $j < 5; $j++)
                                    <span class="review-star-color"><i class="far fa-star"></i></span>
                                @endfor
                            @endif

                            {{ __('app.review_count', ['count' => $framework->review_count]) }}
                        </div>

                        <div class="framework-item-full-stats-views">
                            <i class="far fa-eye"></i>&nbsp;{{ $framework->views }}
                        </div>
                    </div>
                </div>

                @auth
                    @if ((!$user->admin) && ($user->id !== $framework->userId))
                        <div class="framework-item-full-report">
                            <a href="javascript:void(0);" onclick="window.vue.reportFramework({{ $framework->id }});">{{ __('app.report') }}</a>
                        </div>
                    @endif
                @endauth

                <div class="reviews">
                    <div class="reviews-hint">
                        {{ __('app.reviews') }}&nbsp;
                    
                        @for ($i = 0; $i < 5; $i++)
                            @if ($i < $framework->avg_stars)
                                <span class="review-star-color"><i class="fas fa-star"></i></span>
                            @else
                                <span class="review-star-color"><i class="far fa-star"></i></span>
                            @endif
                        @endfor
                    </div>

                    @if ($framework->user_review === null)
                    <div class="reviews-write">
                        <div class="reviews-write-title">{{ __('app.write_review') }}</div>

                        <form method="POST" action="{{ url('/framework/' . $framework->id . '/review/send') }}">
                            @csrf

                            <div class="field">
                                <div class="control">
                                    <textarea class="textarea" name="content" placeholder="{{ __('app.review_content_placeholder') }}"></textarea>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <span class="review-star-color is-pointer" onclick="window.vue.setRating(1);"><i id="review_rating_star_1" class="far fa-star"></i></span>
                                    <span class="review-star-color is-pointer" onclick="window.vue.setRating(2);"><i id="review_rating_star_2" class="far fa-star"></i></span>
                                    <span class="review-star-color is-pointer" onclick="window.vue.setRating(3);"><i id="review_rating_star_3" class="far fa-star"></i></span>
                                    <span class="review-star-color is-pointer" onclick="window.vue.setRating(4);"><i id="review_rating_star_4" class="far fa-star"></i></span>
                                    <span class="review-star-color is-pointer" onclick="window.vue.setRating(5);"><i id="review_rating_star_5" class="far fa-star"></i></span>

                                    <input type="hidden" name="rating" id="rating" value="0">
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <input type="submit" class="button is-link" value="{{ __('app.submit_review') }}">
                                </div>
                            </div>
                        </form>
                    </div>
                    @else
                        <div class="reviews-write">
                            <div class="reviews-write-title">{{ __('app.already_reviewed') }}</div>
                        </div>
                    @endif

                    <div class="reviews-content" id="review-content"></div>
                </div>

                @if (count($others) > 0)
                <div class="random-frameworks">
                    <div class="random-frameworks-hint">{{ __('app.random_frameworks_hint') }}</div>

                    <div class="random-frameworks-items">
                        @foreach ($others as $item)
                        <div class="framework-item is-pointer" onclick="location.href = '{{ url('/view/' . $item->slug) }}';">
                            <div class="framework-item-image" style="background-image: url('{{ url('/gfx/logos/' . $item->logo) }}')"></div>

                            <div class="framework-item-about">
                                <div class="framework-item-about-title">{{ $item->name }}</div>
                                <div class="framework-item-about-hint">{{ $item->summary }}</div>

                                <div class="framework-item-about-tags">
                                    @foreach ($item->tags as $tag)
                                        @if (strlen($tag) > 0)
                                            <span><a href="{{ url('/') }}?tag={{ $tag }}">#{{ $tag }}</a>&nbsp;</span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="framework-item-stats">
                                <div class="framework-item-stats-stars">
                                    @for ($i = 0; $i < $item->avg_stars; $i++)
                                        <span class="review-star-color"><i class="fas fa-star"></i></span>
                                    @endfor

                                    @if ($item->avg_stars < 5)
                                        @for ($j = $item->avg_stars; $j < 5; $j++)
                                            <span class="review-star-color"><i class="far fa-star"></i></span>
                                        @endfor
                                    @endif

                                    {{ __('app.review_count', ['count' => $item->review_count]) }}
                                </div>

                                <div class="framework-item-stats-views">
                                    <i class="far fa-eye"></i>&nbsp;{{ $item->views }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="column is-1"></div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        window.frameworkId = {{ $framework->id }};
        window.paginate = null;
        window.userReviewShown = false;

        @auth
            window.userId = {{ $user->id }};
        @elseguest
            window.userId = 0;
        @endauth

        @auth
            @if ($user->admin)
                window.isAdmin = true;
            @else
                window.isAdmin = false;
            @endif
        @elseguest
            window.isAdmin = false;
        @endauth

        window.queryReviews = function() {
            let content = document.getElementById('review-content');

            content.innerHTML += '<div id="spinner"><center><i class="fa fa-spinner fa-spin"></i></center></div>';

            let loadmore = document.getElementById('loadmore');
            if (loadmore) {
                loadmore.remove();
            }

            window.vue.ajaxRequest('post', '{{ url('/framework/query/reviews') }}', {
                frameworkId: window.frameworkId,
                paginate: window.paginate
            },
            function(response) {
                if (response.code == 200) {
                    if (!userReviewShown) {
                        if (response.user_review !== null) {
                            content.innerHTML += window.vue.renderReview(response.user_review, window.userId, window.isAdmin);
                        }

                        userReviewShown = true;
                    }

                    response.data.forEach(function(elem, index) {
                        if (response.user_review !== null) {
                            if (response.user_review.id === elem.id) {
                                return;
                            }
                        }

                        let html = window.vue.renderReview(elem, window.userId, window.isAdmin);

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
                        content.innerHTML += '<div id="loadmore"><center><br/><i class="fas fa-plus is-pointer" onclick="window.queryReviews();"></i></center></div>';
                    }
                } else {
                    console.error(response.msg);
                }
            });
        };

        document.addEventListener('DOMContentLoaded', function() {
            window.queryReviews();
        });
    </script>
@endsection
