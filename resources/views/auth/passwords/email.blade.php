@extends('templates.password')

<!-- Main Content -->
@section('content')
<h1 class="pageHeader__header passwordLayout__header">Password Reset Request</h1>
<h4 class="pageHeader__subheader passwordLayout__subheader">Enter the email address associated with your account and we will email you a password reset link.</h4>
<div class="loginForm form">
    <form action="{{ url('password/email') }}" method="post">
        {{ csrf_field() }}
        <div class="container-fluid">
            <div class="row form__row">
                <div class="col-xs-100">
                    <label for="email" class="col-md-4 control-label">
                        <span class="visuallyhidden">E-Mail Address</span>
                        <input id="email" type="email" class="form-control" name="email" placeholder="EMAIL ADDRESS">
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-100">
                    <button type="submit" class="button button--primary">Send Request</button>
                </div>
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
