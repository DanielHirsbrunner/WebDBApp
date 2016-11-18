<!-- INCLUDE include/head.tpl -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<!-- INCLUDE include/flash_message.tpl -->
		<p><a href="{BASE_PATH}/users">Manage users</a> <a href="{BASE_PATH}/modules">Manage modules</a></p>

		<!-- BEGIN USERS_EDIT -->
		<form action="" method="post" class="form-horizontal">
			<div class="row form-group required {ERROR_USERNAME}">
				<div class="col-sm-offset-1 col-sm-2 control-label"><label for="username">Username</label></div>
				<div class="col-sm-8"><input type="text" name="username" class="form-control" id="username" value="{VALUE_USERNAME}" required></div>
			</div>
			<!-- BEGIN ERROR_USERNAME_LONG -->
			<div class="row form-group has-error">
				<div class="col-sm-offset-3 help-block">Username is too long. Maximum is 50 characters.</div>
			</div>
			<!-- END ERROR_USERNAME_LONG -->

			<div class="row form-group {PASSWORD_REQUIRED} {ERROR_PASSWORD}">
				<div class="col-sm-offset-1 col-sm-2 control-label"><label for="password">Password</label></div>
				<div class="col-sm-8"><input type="password" name="password" class="form-control" id="password" {PASSWORD_REQUIRED}></div>
			</div>
			<!-- BEGIN ERROR_PASSWORD_LONG -->
			<div class="row form-group has-error">
				<div class="col-sm-offset-3 help-block">Password was too long. Maximum is 100 characters.</div>
			</div>
			<!-- END ERROR_PASSWORD_LONG -->

			<div class="row form-group {PASSWORD_REQUIRED} {ERROR_PASSWORD2}">
				<div class="col-sm-offset-1 col-sm-2 control-label"><label for="password2">Repeat password</label></div>
				<div class="col-sm-8"><input type="password" name="password2" class="form-control" id="password2" {PASSWORD_REQUIRED}></div>
			</div>
			<!-- BEGIN ERROR_PASSWORD_NOMATCH -->
			<div class="row form-group has-error">
				<div class="col-sm-offset-3 help-block">Passwords didn't match.</div>
			</div>
			<!-- END ERROR_PASSWORD_NOMATCH -->

			<div class="row form-group required {ERROR_NAME}">
				<div class="col-sm-offset-1 col-sm-2 control-label"><label for="name">Name</label></div>
				<div class="col-sm-8"><input type="text" name="name" class="form-control" id="name" value="{VALUE_NAME}" required></div>
			</div>
			<!-- BEGIN ERROR_NAME_LONG -->
			<div class="row form-group has-error">
				<div class="col-sm-offset-3 help-block">Name is too long. Maximum is 50 characters.</div>
			</div>
			<!-- END ERROR_NAME_LONG -->

			<div class="row form-group required {ERROR_SURNAME}">
				<div class="col-sm-offset-1 col-sm-2 control-label"><label for="surname">Surname</label></div>
				<div class="col-sm-8"><input type="text" name="surname" class="form-control" id="surname" value="{VALUE_SURNAME}" required></div>
			</div>
			<!-- BEGIN ERROR_SURNAME_LONG -->
			<div class="row form-group has-error">
				<div class="col-sm-offset-3 help-block">Surname is too long. Maximum is 50 characters.</div>
			</div>
			<!-- END ERROR_SURNAME_LONG -->

			<div class="row form-group required {ERROR_EMAIL}">
				<div class="col-sm-offset-1 col-sm-2 control-label"><label for="email">Email</label></div>
				<div class="col-sm-8"><input type="text" name="email" class="form-control" id="email" value="{VALUE_EMAIL}" required></div>
			</div>
			<!-- BEGIN ERROR_EMAIL_LONG -->
			<div class="row form-group has-error">
				<div class="col-sm-offset-3 help-block">Email is too long. Maximum is 50 characters.</div>
			</div>
			<!-- END ERROR_EMAIL_LONG -->

			<div class="row form-group required {ERROR_QUALIFICATION}">
				<div class="col-sm-offset-1 col-sm-2 control-label"><label for="qualification">Qualification</label></div>
				<div class="col-sm-8"><input type="text" name="qualification" class="form-control" id="qualification" value="{VALUE_QUALIFICATION}" required></div>
			</div>
			<!-- BEGIN ERROR_QUALIFICATION_LONG -->
			<div class="row form-group has-error">
				<div class="col-sm-offset-3 help-block">Qualification is too long. Maximum is 250 characters.</div>
			</div>
			<!-- END ERROR_QUALIFICATION_LONG -->

			<div class="row form-group">
				<div class="col-sm-offset-1 col-sm-2 control-label"><label for="admin">Admin</label></div>
				<div class="col-sm-8"><input type="checkbox" class="checkbox" name="admin" {VALUE_ADMIN} id="admin"></div>
			</div>

			<div class="row form-group">
				<div class="col-sm-offset-3 col-sm-6"><input type="submit" class="btn btn-primary" value="{VALUE_BUTTON}"></div>
			</div>

		</form>
		<!-- END USERS_EDIT -->
	</div>
</div>
<!-- INCLUDE include/scripts.tpl -->
</body>
</html>
