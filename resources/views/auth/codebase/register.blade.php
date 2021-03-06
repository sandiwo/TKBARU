@extends('layouts.codebase.blank')

@section('title')
    Register
@endsection

@section('custom_css')
@endsection

@section('content')
    <div id="page-container" class="main-content-boxed">
        <main id="main-container">
            <div class="bg-gd-emerald">
                <div class="hero-static content content-full bg-white invisible" data-toggle="appear">
                    <div class="py-5 px-5 text-center">
                        <div class="py-30 px-5 text-center">
                            <img src="{{ asset('images/loginlogo_notext.png') }}" width="125" height="100"/>
                        </div>
                    </div>
                    <div class="py-30 px-5 text-center">
                        <h2 class="h4 font-w400 text-muted mb-0">@lang('login.register.title')</h2>
                    </div>

                    <div class="row justify-content-center px-5">
                        <div class="col-sm-8 col-md-6 col-xl-4">
                            <form action="{{ url('/register') }}" method="post">
                                @csrf
                                @if ($company_mode == 'create')
                                    <div class="form-group row {{ $errors->has('company_name') ? 'is-invalid':'' }}">
                                        <div class="col-12">
                                            <div class="form-material floating input-group">
                                                <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name') }}">
                                                <label for="name">@lang('login.register.company_name')</label>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="si si-umbrella"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            @if ($errors->has('company_name'))
                                                <div class="invalid-feedback">{{ $errors->first('company_name') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @elseif ($company_mode == 'use_default')
                                    <input type="hidden" name="company_id" value="{{ $company_id }}">
                                @elseif ($company_mode == 'pick')
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <select name="picked_company_id" class="form-control">
                                                    <option>@lang('labels.PLEASE_SELECT')</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                @endif
                                <div class="form-group row {{ $errors->has('name') ? 'is-invalid':'' }}">
                                    <div class="col-12">
                                        <div class="form-material floating input-group">
                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                                            <label for="name">@lang('login.register.full_name')</label>
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="si si-user"></i>
                                                </span>
                                            </div>
                                        </div>
                                        @if ($errors->has('name'))
                                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('email') ? 'is-invalid':'' }}">
                                    <div class="col-12">
                                        <div class="form-material floating input-group">
                                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                                            <label for="email">@lang('login.register.email')</label>
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="si si-envelope"></i>
                                                </span>
                                            </div>
                                        </div>
                                        @if ($errors->has('email'))
                                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('password') ? 'is-invalid':'' }}">
                                    <div class="col-12">
                                        <div class="form-material floating input-group">
                                            <input type="password" class="form-control" id="password" name="password">
                                            <label for="password">@lang('login.register.password')</label>
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="si si-key"></i>
                                                </span>
                                            </div>
                                        </div>
                                        @if ($errors->has('password'))
                                            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('password') ? 'is-invalid':'' }}">
                                    <div class="col-12">
                                        <div class="form-material floating input-group">
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                            <label for="password_confirmation">@lang('login.register.retype_password')</label>
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="si si-like"></i>
                                                </span>
                                            </div>
                                        </div>
                                        @if ($errors->has('password'))
                                            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('terms') ? 'is-invalid':'' }}">
                                    <div class="col-12">
                                        <label class="css-control css-control-primary css-checkbox">
                                            <input type="checkbox" class="css-control-input" id="terms" name="terms">
                                            <span class="css-control-indicator"></span>
                                            @lang('login.register.agree_1') &amp; @lang('login.register.agree_2')
                                        </label>
                                        @if ($errors->has('terms'))
                                            <div class="invalid-feedback">{{ $errors->first('terms') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row gutters-tiny">
                                    <div class="col-12 mb-10">
                                        <button type="submit" class="btn btn-block btn-hero btn-noborder btn-rounded btn-alt-success">
                                            <i class="si si-user-follow mr-10"></i> @lang('buttons.register_button')
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <a class="btn btn-block btn-noborder btn-rounded btn-alt-secondary" href="#" data-toggle="modal" data-target="#modal-terms">
                                            <i class="si si-book-open text-muted mr-10"></i> @lang('buttons.read_term_button')
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a class="btn btn-block btn-noborder btn-rounded btn-alt-secondary" href="{{ url('/login') }}">
                                            <i class="si si-login text-muted mr-10"></i> @lang('buttons.login_button')
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div class="modal fade" id="modal-terms" tabindex="-1" role="dialog" aria-labelledby="modal-terms" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-slidedown" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">@lang('login.register.terms_and_cond')</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-close fa-fw"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        &nbsp;
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">@lang('buttons.close_button')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script type="application/javascript">

    </script>
@endsection

<!doctype html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}" class="no-focus">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

        <title>Register - TKBARU</title>

        <meta name="description" content="Toko Baru - GitzJoey's Laravel Implementations For General Trading System">
        <meta name="author" content="GitzJoey">
        <meta name="robots" content="noindex, nofollow">

        <meta property="og:title" content="TKBARU, Toko, Baru, GitzJoey, Laravel, Implementations, General, Trading, System">
        <meta property="og:site_name" content="TKBARU">
        <meta property="og:description" content="Toko Baru - GitzJoey's Laravel Implementations For General Trading System">
        <meta property="og:type" content="website, app, trading, system">
        <meta property="og:url" content="">
        <meta property="og:image" content="">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

        <link rel="stylesheet" id="css-main" href="{{ mix('css/codebase/main.css') }}">
    </head>
    <body>

        <input type="hidden" id="appSettings" value=""/>

        <script src="{{ mix('js/codebase/main.js') }}"></script>
    </body>
</html>