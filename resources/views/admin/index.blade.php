@extends('layouts.layout_view')

@section('title', 'Administration')

@section('content')
    <div class="container">
        <div class="columns">
            <div class="column is-1"></div>

            <div class="column is-10">
                <div class="form">
                    <div>
                        <h1>{{ __('app.admin_area') }}</h1>
                    </div>

                    <div>
                        <div><strong>Astarlove (dnyAstarlove) v1.0 developed by Daniel Brendel (dbrendel1988@gmail.com)</strong></div>
                        <div><strong>Project: </strong>{{ env('APP_NAME') }}</div>
                        <div><strong>Author: </strong>{{ env('APP_AUTHOR') }}</div>
                        <div><strong>Codename: </strong>{{ env('APP_CODENAME') }}</div>
                        <div><strong>Contact: </strong>{{ env('APP_CONTACT') }}</div>
                        <div><strong>Version: </strong>{{ env('APP_VERSION') }}</div>
                        <br/>
                    </div>

                    <ul data-role="tabs" data-expand="true">
                        <li><a href="#tab-page-1">{{ __('app.about') }}</a></li>
                        <li><a href="#tab-page-2">{{ __('app.logo') }}</a></li>
                        <li><a href="#tab-page-3">{{ __('app.cookie_consent') }}</a></li>
                        <li><a href="#tab-page-4">{{ __('app.reg_info') }}</a></li>
                        <li><a href="#tab-page-5">{{ __('app.tos') }}</a></li>
                        <li><a href="#tab-page-6">{{ __('app.imprint') }}</a></li>
                        <li><a href="#tab-page-7">{{ __('app.head_code') }}</a></li>
                        <li><a href="#tab-page-8">{{ __('app.users') }}</a></li>
                        <li><a href="#tab-page-9">{{ __('app.approvals') }}</a></li>
                        <li><a href="#tab-page-10">{{ __('app.reports') }}</a></li>
                    </ul>

                    <div class="border bd-default no-border-top p-2">
                        <div id="tab-page-1">
                            <form method="POST" action="{{ url('/admin/about/save') }}">
                                @csrf

                                <div class="field">
                                    <label class="label">{{ __('app.about') }}</label>
                                    <div class="control">
                                        <textarea class="textarea" name="about">{{ $settings->about }}</textarea>
                                    </div>
                                </div>

                                <div class="field">
                                    <div class="control">
                                        <input type="submit" value="{{ __('app.save') }}">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div id="tab-page-2">
                            <form method="POST" action="{{ url('/admin/logo/save') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="field">
                                    <label class="label">{{ __('app.logo_info') }}</label>
                                    <div class="control">
                                        <div><img src="{{ url('/logo.png') }}" alt="logo"></div>
                                        <div><input type="file" name="logo" data-role="file"></div>
                                    </div>
                                </div>

                                <div class="field">
                                    <div class="control">
                                        <input type="submit" value="{{ __('app.save') }}">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div id="tab-page-3">
                            <form method="POST" action="{{ url('/admin/cookieconsent/save') }}">
                                @csrf

                                <div class="field">
                                    <label class="label">{{ __('app.cookieconsent_description') }}</label>
                                    <div class="control">
                                        <textarea class="textarea" name="cookieconsent">{{ $settings->cookie_consent }}</textarea>
                                    </div>
                                </div>

                                <div class="field">
                                    <div class="control">
                                        <input type="submit" value="{{ __('app.save') }}">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div id="tab-page-4">
                            <form method="POST" action="{{ url('/admin/reginfo/save') }}">
                                @csrf

                                <div class="field">
                                    <label class="label">{{ __('app.reginfo_description') }}</label>
                                    <div class="control">
                                        <textarea class="textarea" name="reginfo">{{ $settings->reg_info }}</textarea>
                                    </div>
                                </div>

                                <div class="field">
                                    <div class="control">
                                        <input type="submit" value="{{ __('app.save') }}">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div id="tab-page-5">
                            <form method="POST" action="{{ url('/admin/tos/save') }}">
                                @csrf

                                <div class="field">
                                    <label class="label">{{ __('app.tos_description') }}</label>
                                    <div class="control">
                                        <textarea class="textarea" name="tos">{{ $settings->tos }}</textarea>
                                    </div>
                                </div>

                                <div class="field">
                                    <div class="control">
                                        <input type="submit" value="{{ __('app.save') }}">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div id="tab-page-6">
                            <form method="POST" action="{{ url('/admin/imprint/save') }}">
                                @csrf

                                <div class="field">
                                    <label class="label">{{ __('app.imprint_description') }}</label>
                                    <div class="control">
                                        <textarea class="textarea" name="imprint">{{ $settings->imprint }}</textarea>
                                    </div>
                                </div>

                                <div class="field">
                                    <div class="control">
                                        <input type="submit" value="{{ __('app.save') }}">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div id="tab-page-7">
                            <form method="POST" action="{{ url('/admin/headcode/save') }}">
                                @csrf

                                <div class="field">
                                    <label class="label">{{ __('app.headcode_description') }}</label>
                                    <div class="control">
                                        <textarea class="textarea" name="headcode">{{ $settings->head_code }}</textarea>
                                    </div>
                                </div>

                                <div class="field">
                                    <div class="control">
                                        <input type="submit" value="{{ __('app.save') }}">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div id="tab-page-8">
                            <div class="field">
                                <input type="text" id="userident">
                            </div>

                            <div class="field">
                                <input type="button" value="{{ __('app.get_user_details') }}" onclick="getUserDetails(document.getElementById('userident').value);">
                            </div>

                            <div id="user_settings" class="is-hidden">
                                <form method="POST" action="{{ url('/admin/user/save') }}">
                                    @csrf

                                    <input type="hidden" name="id" id="user_id">

                                    <div class="field">
                                        <div class="control">
                                            <a id="userDetailsLink" href=""></a>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <label class="label">{{ __('app.username') }}</label>
                                        <div class="control">
                                            <input type="text" id="user_name" name="username">
                                        </div>
                                    </div>

                                    <div class="field">
                                        <label class="label">{{ __('app.email') }}</label>
                                        <div class="control">
                                            <input type="text" name="email" id="user_email">
                                        </div>
                                    </div>

                                    <div class="field">
                                        <div class="control">
                                            <a href="javascript:void(0);" onclick="location.href = window.location.origin + '/admin/user/' + document.getElementById('user_id').value + '/resetpw';">{{ __('app.reset_password') }}</a>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <div class="control">
                                            <input type class="checkbox" name="locked" id="user_locked" data-role="checkbox" data-style="2" data-caption="{{ __('app.locked') }}" value="1">
                                        </div>
                                    </div>

                                    <div class="field">
                                        <div class="control">
                                            <input type class="checkbox" name="admin" id="user_admin" data-role="checkbox" data-style="2" data-caption="{{ __('app.admin') }}" value="1">
                                        </div>
                                    </div>

                                    <div class="field">
                                        <div class="control">
                                            <input type="submit" value="{{ __('app.save') }}">
                                        </div>
                                    </div>
                                </form>

                                <div class="field">
                                    <div class="control">
                                        <br/><a href="javascript:void(0);" class="button is-danger" onclick="if (confirm('{{ __('app.confirm_delete') }}')) location.href = window.location.origin + '/admin/user/' + document.getElementById('user_id').value + '/delete';">{{ __('app.delete_account') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="tab-page-9">
                            <table class="table striped table-border mt-4" data-role="table" data-pagination="true"
                                    data-table-rows-count-title="{{ __('app.table_show_entries') }}"
                                    data-table-search-title="{{ __('app.table_search') }}"
                                    data-table-info-title="{{ __('app.table_row_info') }}"
                                    data-pagination-prev-title="{{ __('app.table_pagination_prev') }}"
                                    data-pagination-next-title="{{ __('app.table_pagination_next') }}">
                                <thead>
                                <tr>
                                    <th class="text-left">{{ __('app.framework_id') }}</th>
                                    <th class="text-left">{{ __('app.framework_name') }}</th>
                                    <th class="text-left">{{ __('app.framework_user') }}</th>
                                    <th class="text-right">{{ __('app.approve') }}</th>
                                    <th class="text-right">{{ __('app.decline') }}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($approvals as $approval)
                                    <tr>
                                        <td>
                                            #{{ $approval->id }}
                                        </td>

                                        <td class="right">
                                            <a href="{{ url('/view/' . $approval->slug) }}">{{ $approval->name }}</a>
                                        </td>

                                        <td>
                                            <a href="{{ url('/user/' . $approval->userData->id) }}">{{ $approval->userData->username }}</a>
                                        </td>

                                        <td>
                                            <a href="{{ url('/admin/approval/' . $approval->id . '/approve') }}">{{ __('app.approve') }}</a>
                                        </td>

                                        <td>
                                            <a href="{{ url('/admin/approval/' . $approval->id . '/decline') }}">{{ __('app.decline') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div id="tab-page-10">
                            <table class="table striped table-border mt-4" data-role="table" data-pagination="true"
                                    data-table-rows-count-title="{{ __('app.table_show_entries') }}"
                                    data-table-search-title="{{ __('app.table_search') }}"
                                    data-table-info-title="{{ __('app.table_row_info') }}"
                                    data-pagination-prev-title="{{ __('app.table_pagination_prev') }}"
                                    data-pagination-next-title="{{ __('app.table_pagination_next') }}">
                                <thead>
                                <tr>
                                    <th class="text-left">{{ __('app.report_id') }}</th>
                                    <th class="text-left">{{ __('app.report_entity') }}</th>
                                    <th class="text-left">{{ __('app.report_type') }}</th>
                                    <th class="text-left">{{ __('app.report_count') }}</th>
                                    <th class="text-right">{{ __('app.report_lock') }}</th>
                                    <th class="text-right">{{ __('app.report_delete') }}</th>
                                    <th class="text-right">{{ __('app.report_safe') }}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($reports['frameworks'] as $item)
                                    <tr>
                                        <td>
                                            #{{ $item->id }}
                                        </td>

                                        <td class="right">
                                            <a href="{{ url('/view/' . $item->entityId) }}" target="_blank">{{ $item->entityId }}</a>
                                        </td>

                                        <td>
                                            {{ $item->type }}
                                        </td>

                                        <td>{{ $item->count }}</td>

                                        <td>
                                            <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_lock') }}')) location.href = '{{ url('/admin/entity/lock?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_lock') }}</a>
                                        </td>

                                        <td>
                                            <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_delete') }}')) location.href = '{{ url('/admin/entity/delete?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_delete') }}</a>
                                        </td>

                                        <td>
                                            <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_safe') }}')) location.href = '{{ url('/admin/entity/safe?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_safe') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <table class="table striped table-border mt-4" data-role="table" data-pagination="true"
                                data-table-rows-count-title="{{ __('app.table_show_entries') }}"
                                data-table-search-title="{{ __('app.table_search') }}"
                                data-table-info-title="{{ __('app.table_row_info') }}"
                                data-pagination-prev-title="{{ __('app.table_pagination_prev') }}"
                                data-pagination-next-title="{{ __('app.table_pagination_next') }}">
                                <thead>
                                <tr>
                                    <th class="text-left">{{ __('app.report_id') }}</th>
                                    <th class="text-left">{{ __('app.report_entity') }}</th>
                                    <th class="text-left">{{ __('app.report_type') }}</th>
                                    <th class="text-left">{{ __('app.report_count') }}</th>
                                    <th class="text-right">{{ __('app.report_lock') }}</th>
                                    <th class="text-right">{{ __('app.report_delete') }}</th>
                                    <th class="text-right">{{ __('app.report_safe') }}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($reports['users'] as $item)
                                    <tr>
                                        <td>
                                            #{{ $item->id }}
                                        </td>

                                        <td class="right">
                                            <a href="{{ url('/user/' . $item->entityId) }}" target="_blank">{{ $item->entityId }}</a>
                                        </td>

                                        <td>
                                            {{ $item->type }}
                                        </td>

                                        <td>{{ $item->count }}</td>

                                        <td>
                                            <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_lock') }}')) location.href = '{{ url('/admin/entity/lock?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_lock') }}</a>
                                        </td>

                                        <td>
                                            <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_delete') }}')) location.href = '{{ url('/admin/entity/delete?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_delete') }}</a>
                                        </td>

                                        <td>
                                            <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_safe') }}')) location.href = '{{ url('/admin/entity/safe?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_safe') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <table class="table striped table-border mt-4" data-role="table" data-pagination="true"
                                data-table-rows-count-title="{{ __('app.table_show_entries') }}"
                                data-table-search-title="{{ __('app.table_search') }}"
                                data-table-info-title="{{ __('app.table_row_info') }}"
                                data-pagination-prev-title="{{ __('app.table_pagination_prev') }}"
                                data-pagination-next-title="{{ __('app.table_pagination_next') }}">
                                <thead>
                                <tr>
                                    <th class="text-left">{{ __('app.report_id') }}</th>
                                    <th class="text-left">{{ __('app.report_entity') }}</th>
                                    <th class="text-left">{{ __('app.report_type') }}</th>
                                    <th class="text-left">{{ __('app.report_count') }}</th>
                                    <th class="text-right">{{ __('app.report_lock') }}</th>
                                    <th class="text-right">{{ __('app.report_delete') }}</th>
                                    <th class="text-right">{{ __('app.report_safe') }}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($reports['reviews'] as $item)
                                    <tr>
                                        <td>
                                            #{{ $item->id }}
                                        </td>

                                        <td class="right">
                                            <a href="{{ url('/view/' . $item->entityId) }}" target="_blank">{{ $item->entityId }}</a>
                                        </td>

                                        <td>
                                            {{ $item->type }}
                                        </td>

                                        <td>{{ $item->count }}</td>

                                        <td>
                                            <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_lock') }}')) location.href = '{{ url('/admin/entity/lock?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_lock') }}</a>
                                        </td>

                                        <td>
                                            <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_delete') }}')) location.href = '{{ url('/admin/entity/delete?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_delete') }}</a>
                                        </td>

                                        <td>
                                            <a href="javascript:void(0)" onclick="if (confirm('{{ __('app.report_confirm_safe') }}')) location.href = '{{ url('/admin/entity/safe?id=' . $item->entityId . '&type=' . $item->type) }}';">{{ __('app.report_safe') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="column is-1"></div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        function getUserDetails(ident)
        {
            window.vue.ajaxRequest('get', '{{ url('/admin/user/details') }}?ident=' + ident, {}, function(response) {
                if (response.code === 200) {
                    document.getElementById('user_settings').classList.remove('is-hidden');

                    document.getElementById('user_id').value = response.data.id;

                    document.getElementById('user_name').value = response.data.username;
                    document.getElementById('user_email').value = response.data.email;

                    document.getElementById('user_locked').checked = response.data.locked;
                    document.getElementById('user_admin').checked = response.data.admin;
                } else {
                    document.getElementById('user_settings').classList.add('is-hidden');
                    alert(response.msg);
                }
            });
        }
    </script>
@endsection
