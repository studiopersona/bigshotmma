@extends('templates.password')

@section('content')
<h1 class="pageHeader__header passwordLayout__header">Reset Password</h1>
<h4 class="pageHeader__subheader passwordLayout__subheader">Enter and confirm your new password to resest it.</h4>
<div class="passwordForm form">
    <form action="{{ url('/password/reset') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="container-fluid">
            <div class="row form__row">
                <div class="col-xs-100">
                    <label for="email" class="ontrol-label">
                        <span class="visuallyhidden">E-Mail Address</span>
                        <input id="email" type="email" class="form-control" name="email" placeholder="EMAIL ADDRESS">
                    </label>
                </div>
            </div>
            <div class="row form__row">
                <div class="col-xs-100">
                    <label for="email" class="control-label">
                        <span class="visuallyhidden">Password</span>
                        <input id="password" type="password" class="form-control" name="password" placeholder="PASSWORD">
                    </label>
                </div>
            </div>
            <div class="row form__row">
                <div class="col-xs-100">
                    <label for="email" class="control-label">
                        <span class="visuallyhidden">E-Mail Address</span>
                        <input id="password-confirmation" type="password" class="form-control" name="password_confirmation" placeholder="CONFIRM PASSWORD">
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-100">
                    <button type="submit" class="button button--primary">Reset</button>
                </div>
                <div class="response col-xs-60 col-xs-push-20"></div>
            </div>
        </div>
    </form>
</div>
<div class="container-fluid">
    <div class="col-xs-100">
        <div class="logo">
            <img src="{{ url('/public/image/logo.png') }}" alt="Big Shot Fantasy MMA Logo">
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .passwordForm {
        text-align: center;
        padding: 0 1.5rem;
    }
</style>
@endsection
