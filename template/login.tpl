<!-- INCLUDE include/head.tpl -->

<body>

<div class="container content login-page">
	<!-- INCLUDE include/flash_message.tpl -->
	<form action="" method="post" class="form-horizontal">
		<div class="row form-group required {ERROR_CLASS}">
			<div class="col-xs-offset-2 col-xs-2 control-label"><label for="username">Username</label></div>
			<div class="col-sm-6"><input type="text" name="username" class="form-control" id="username" value="{USERNAME_VALUE}" required></div>
		</div>

		<div class="row form-group required {ERROR_CLASS}">
			<div class="col-xs-offset-2 col-xs-2 control-label"><label for="password">Password</label></div>
			<div class="col-xs-6"><input type="password" name="password" class="form-control" id="password" required></div>
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
	<p>admin / admin</p>
	<p>LimEngLye / secret</p>
</div>
<!-- INCLUDE include/scripts.tpl -->
</body>
</html>
