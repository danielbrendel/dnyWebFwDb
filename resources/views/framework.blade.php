@extends('layout')

@section('title', ' - ' . $framework->name)

@section('content')
    <div class="container">
        <div class="columns">
            <div class="column is-1"></div>

            <div class="column is-10">
                <div class="framework-item-full">
                    <div class="framework-item-full-image" style="background-image: url('{{ url('/gfx/logos/' . $framework->logo) }}')"></div>

                    <div class="framework-item-full-about">
                        <div class="framework-item-full-about-title">{{ $framework->name }}</div>
                        <div class="framework-item-full-about-hint">{{ $framework->summary }}</div>
                        <div class="framework-item-full-about-tags">{{ $framework->tags }}</div>
                    </div>

                    <div class="framework-item-full-description">
                        {{ $framework->description }}
                    </div>

                    <div class="framework-item-full-github">
                        @include('github', ['github' => $framework->github])
                    </div>

                    <div class="framework-item-full-links">
                        <div class="framework-item-full-links-twitter">
                            <i class="fab fa-twitter"></i>&nbsp;<a href="https://twitter.com/{{ $framework->twitter }}">{{ $framework->twitter }}</a>
                        </div>

                        <div class="framework-item-full-links-homepage">
                            <i class="fas fa-globe"></i>&nbsp;<a href="{{ $framework->website }}">{{ $framework->website }}</a>
                        </div>
                    </div>

                    <div class="framework-item-full-stats">
                        <div class="framework-item-full-stats-hearts">
                            <i class="fas fa-heart"></i>&nbsp;{{ $framework->hearts }}
                        </div>

                        <div class="framework-item-full-stats-views">
                            <i class="far fa-eye"></i>&nbsp;{{ $framework->views }}
                        </div>
                    </div>
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
                                <div class="framework-item-about-tags">{{ $item->tags }}</div>
                            </div>

                            <div class="framework-item-stats">
                                <div class="framework-item-stats-hearts">
                                    <i class="fas fa-heart"></i>&nbsp;{{ $item->hearts }}
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
