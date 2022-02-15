{{--
    WebframeworkDB (dnyWebFwDb) developed by Daniel Brendel

    (C) 2022 by Daniel Brendel

    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
--}}

@extends('layouts.layout_view')

@section('content')
    <div class="container">
        <div class="columns">
            <div class="column is-2"></div>

            <div class="column is-8">
                <div class="framework-submit-title">{{ __('app.submit_framework_item') }}</div>

                <div class="framework-submit-form">
                    <form method="POST" action="{{ url('/submit') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="field">
                            <label class="label">{{ __('app.framework_name') }}</label>
                            <div class="control">
                                <input class="input" type="text" name="name" placeholder="{{ __('app.framework_name_placeholder') }}" value="{{ old('name') }}" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.framework_logo') }}</label>
                            <div class="control">
                                <input class="input" type="file" name="logo" data-role="file" data-button-title="{{ __('app.select_logo') }}" required>
                            </div>
                            <p class="help">{{ __('app.framework_logo_hint') }}</p>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.framework_language') }}</label>
                            <div class="control">
                                <select class="input" name="lang" required>
                                    <option value="" selected>{{ __('app.framework_select_language') }}</option>

                                    @foreach (\App\Models\LanguageModel::getLanguages() as $item)
                                        <option value="{{ $item->id }}">{{ $item->language }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.framework_input_creator') }}</label>
                            <div class="control">
                                <input class="input" type="text" name="creator" placeholder="{{ __('app.framework_creator_placeholder') }}" value="{{ old('creator') }}" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.framework_summary') }}</label>
                            <div class="control">
                                <input class="input" type="text" name="summary" placeholder="{{ __('app.framework_summary_placeholder') }}" value="{{ old('summary') }}" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.framework_description') }}</label>
                            <div class="control">
                                <textarea name="description" placeholder="{{ __('app.framework_description_placeholder') }}" required>{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.framework_tags') }}</label>
                            <div class="control">
                                <input class="input" type="text" name="tags" placeholder="{{ __('app.framework_tags_placeholder') }}" value="{{ old('tags') }}">
                            </div>
                            <p class="help">{{ __('app.framework_tags_hint') }}</p>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.framework_github') }}</label>
                            <div class="control">
                                <input class="input" type="text" name="github" placeholder="{{ __('app.framework_github_placeholder') }}" value="{{ old('github') }}" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.framework_twitter') }}</label>
                            <div class="control">
                                <input class="input" type="text" name="twitter" placeholder="{{ __('app.framework_twitter_placeholder') }}" value="{{ old('twitter') }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.framework_website') }}</label>
                            <div class="control">
                                <input class="input" type="text" name="website" placeholder="{{ __('app.framework_website_placeholder') }}" value="{{ old('website') }}">
                            </div>
                        </div>

                        <div class="field">
                            <input class="button is-info is-top-5" type="submit" value="{{ __('app.submit') }}">
                        </div>
                    </form>
                </div>
            </div>

            <div class="column is-2"></div>
        </div>
    </div>
@endsection
