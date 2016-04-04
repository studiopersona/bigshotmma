<div class="loginForm">
	{!! Form::open(['route' => 'api.v1.authenticate.index', 'class'=>'loginForm__form form', 'method'=>'post']) !!}
	<div class="container-fluid">
		<div class="row form__row">
			<div class="col-xs-100">
				<label for="email">
					<span class="visuallyhidden">Email</span>
					<input type="email" placeholder="EMAIL ADDRESS">
				</label>
			</div>
		</div>
		<div class="row form__row">
			<div class="col-xs-100">
				<label for="password">
					<span class="visuallyhidden">Password</span>
					<input type="password" placeholder="PASSWORD">
				</label>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-100">
				<button type="submit" class="button button--primary">Login</button>
			</div>
		</div>
	</div>
	{!! Form::close() !!}
</div>
