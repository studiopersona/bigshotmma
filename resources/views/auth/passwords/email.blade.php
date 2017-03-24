@extends('templates.password')

<!-- Main Content -->
@section('content')
<h1 class="pageHeader__header passwordLayout__header">Password Reset Request</h1>
<h4 class="pageHeader__subheader passwordLayout__subheader">Enter the email address associated with your account and we will email you a password reset link.</h4>
<div class="passwordForm form">
    <form action="{{ url('password/email') }}" method="post">
        {{ csrf_field() }}
        <div class="container-fluid">
            <div class="row form__row">
                <div class="col-xs-100">
                    <label for="email" class="control-label">
                        <span class="visuallyhidden">E-Mail Address</span>
                        <input id="email" type="email" class="form-control" name="email" placeholder="EMAIL ADDRESS">
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-100">
                    <button type="submit" class="button button--primary">Send Request</button>
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

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
    $(function() {
        var submitBtn = $('button[type="submit"]');

        submitBtn.on('click', function(e) {
            var responseEl = $('.response'),
                par = $('<p>'),
                email;

            e.preventDefault();

            responseEl.empty();
            par.removeClass('error').removeClass('success');

            email = $("#email").val();

            if ( email === "" ) {
                par.addClass('error').html('An email address must be entered');

                responseEl.append(par[0]);
            } else {
                $.ajax({
                    url: $('form').attr('action'),
                    method: 'post',
                    data: { email: email },
                    success: function(res) {
                        if ( res.success ) {
                            par.addClass('success').html(res.message);
                            submitBtn.attr('disabled', true);
                        } else {
                            par.addClass('error').html(res.message);
                        }
                        responseEl.append(par[0]);
                    },
                });
            }
        });
    });
</script>
@endsection

@section('styles')
<style>
.passwordForm {
    text-align: center;
    padding: 0 1.5rem;
}

.response {
    margin-top: 1.5rem;
}

.response > p {
    padding: 1rem;
    border-radius: 4px;
    border-width: 1px;
    border-style: solid;
    font-size: 1.6rem;
    font-weight: 700;
}

.response .error {
    color: red;
    border-color: red;
}

.response .success {
    color: green;
    border-color: green;
}
</style>
