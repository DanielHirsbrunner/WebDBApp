<!-- INCLUDE include/head.html -->

<body>

<div class="container">
	<form action="" method="post" class="form-horizontal">
		<div class="row form-group required {ERROR_CLASS}">
			<div class="col-xs-offset-2 col-xs-2 control-label"><label for="username">Uživatelské jméno</label></div>
			<div class="col-sm-6"><input type="text" name="username" class="form-control text form-control" id="username" value="{USERNAME_VALUE}" required></div>
		</div>

		<div class="row form-group required {ERROR_CLASS}">
			<div class="col-xs-offset-2 col-xs-2 control-label"><label for="password">Heslo</label></div>
			<div class="col-xs-6"><input type="password" name="password" class="form-control text form-control" id="password" required></div>
		</div>

		<!-- BEGIN BAD_CREDENTIALS -->
		<div class="row form-group has-error">
			<div class="col-xs-offset-4 col-xs-6">Username or password are wrong.</div>
		</div>
		<!-- END BAD_CREDENTIALS -->

		<div class="row form-group">
			<div class="col-xs-offset-4 col-xs-6"><input type="submit" name="send" class="btn btn-primary" value="Log in"></div>
		</div>

	</form>
</div>

</body>
</html>
