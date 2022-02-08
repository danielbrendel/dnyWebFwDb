@extends('layouts.layout_view')

@section('content')
    <div class="container">
        <div class="columns">
            <div class="column is-2"></div>

            <div class="column is-8">
                <div class="framework-edit-title">{{ __('app.edit_framework_item') }}</div>

                <div class="framework-edit-form">
                    <form method="POST" action="{{ url('/framework/' . $framework->id . '/edit') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="field">
                            <label class="label">{{ __('app.framework_logo') }}</label>
                            <div class="control">
                                <input class="input" type="file" name="logo" data-role="file" data-button-title="{{ __('app.select_logo') }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.framework_language') }}</label>
                            <div class="control">
                                <select class="input" name="lang">
                                    @foreach (\App\Models\LanguageModel::getLanguages() as $item)
                                        <option value="{{ $item->id }}" @if ($item->id === $framework->langId) {{ 'selected' }} @endif>{{ $item->language }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.framework_input_creator') }}</label>
                            <div class="control">
                                <input class="input" type="text" name="creator" placeholder="{{ __('app.framework_creator_placeholder') }}" value="{{ $framework->creator }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.framework_summary') }}</label>
                            <div class="control">
                                <input class="input" type="text" name="summary" placeholder="{{ __('app.framework_summary_placeholder') }}" value="{{ $framework->summary }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.framework_description') }}</label>
                            <div class="control">
                                <textarea name="description" placeholder="{{ __('app.framework_summary_placeholder') }}">{{ $framework->description }}</textarea>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.framework_tags') }}</label>
                            <div class="control">
                                <input class="input" type="text" name="tags" placeholder="{{ __('app.framework_tags_placeholder') }}" value="{{ $framework->tags }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.framework_github') }}</label>
                            <div class="control">
                                <input class="input" type="text" name="github" placeholder="{{ __('app.framework_github_placeholder') }}" value="https://github.com/{{ $framework->github }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.framework_twitter') }}</label>
                            <div class="control">
                                <input class="input" type="text" name="twitter" placeholder="{{ __('app.framework_twitter_placeholder') }}" value="{{ $framework->twitter }}">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">{{ __('app.framework_website') }}</label>
                            <div class="control">
                                <input class="input" type="text" name="website" placeholder="{{ __('app.framework_website_placeholder') }}" value="{{ $framework->website }}">
                            </div>
                        </div>

                        <div class="field">
                            <input class="button is-info is-top-5" type="submit" value="{{ __('app.save') }}">
                        </div>
                    </form>
                </div>
            </div>

            <div class="column is-2"></div>
        </div>
    </div>
@endsection
