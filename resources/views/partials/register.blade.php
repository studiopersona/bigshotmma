<div class="registerForm">
	{!! Form::open(['url' => url('api/v1/register'), 'class'=>'loginForm__form form', 'method'=>'post']) !!}
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
		<div class="row form__row">
			<div class="col-xs-100">
				<label for="playerName">
					<span class="visuallyhidden">Player Name</span>
					<input type="text" placeholder="PLAYER NAME">
				</label>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-100">
				<button @click="register" type="submit" class="button button--primary">Sign Up</button>
			</div>
		</div>
	</div>
	{!! Form::close() !!}
</div>