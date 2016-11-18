<!-- INCLUDE include/head.tpl -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<!-- INCLUDE include/flash_message.tpl -->
		<p><a href="{BASE_PATH}/users">Manage users</a> <a href="{BASE_PATH}/modules">Manage modules</a></p>

		<!-- BEGIN MODULES_EDIT -->
		<form action="" method="post" class="form-horizontal">
			<div class="row form-group required {ERROR_NAME}">
				<div class="col-sm-offset-1 col-sm-2 control-label"><label for="name">Name</label></div>
				<div class="col-sm-8"><input type="text" name="name" class="form-control" id="name" value="{VALUE_NAME}" required></div>
			</div>
			<!-- BEGIN ERROR_NAME_LONG -->
			<div class="row form-group has-error">
				<div class="col-sm-offset-3 help-block">Name is too long. Maximum is 50 characters.</div>
			</div>
			<!-- END ERROR_NAME_LONG -->

			<div class="row form-group {ERROR_CODE}">
				<div class="col-sm-offset-1 col-sm-2 control-label"><label for="code">Code</label></div>
				<div class="col-sm-8"><input type="text" name="code" class="form-control" id="code" value="{VALUE_CODE}" required></div>
			</div>
			<!-- BEGIN ERROR_CODE_LONG -->
			<div class="row form-group has-error">
				<div class="col-sm-offset-3 help-block">Code is too long. Maximum is 20 characters.</div>
			</div>
			<!-- END ERROR_CODE_LONG -->

			<div class="row form-group required {ERROR_CREDITS}">
				<div class="col-sm-offset-1 col-sm-2 control-label"><label for="credits">Credits</label></div>
				<div class="col-sm-8"><input type="number" name="credits" class="form-control" id="credits" value="{VALUE_CREDITS}" required></div>
			</div>
			<!-- BEGIN ERROR_CREDITS_LONG -->
			<div class="row form-group has-error">
				<div class="col-sm-offset-3 help-block">Number of credits is too big. Maximum is 99.</div>
			</div>
			<!-- END ERROR_CREDITS_LONG -->
			<!-- BEGIN ERROR_CREDITS_NUMBER -->
			<div class="row form-group has-error">
				<div class="col-sm-offset-3 help-block">Number of credits must be a positive integer.</div>
			</div>
			<!-- END ERROR_CREDITS_NUMBER -->

			<div class="row form-group required">
				Module Owner
			</div>

			<div class="row form-group required {ERROR_PURPOSE}">
				<div class="col-sm-offset-1 col-sm-2 control-label"><label for="purpose">Purpose</label></div>
				<div class="col-sm-8"><textarea name="purpose" class="form-control" id="purpose" rows="5" required>{VALUE_PURPOSE}</textarea></div>
			</div>
			<!-- BEGIN ERROR_PURPOSE_LONG -->
			<div class="row form-group has-error">
				<div class="col-sm-offset-3 help-block">Purpose is too long. Maximum is 1500 characters.</div>
			</div>
			<!-- END ERROR_PURPOSE_LONG -->

			<div class="row form-group">
				<div class="col-sm-offset-3 col-sm-6"><input type="submit" class="btn btn-primary" value="{VALUE_BUTTON}"></div>
			</div>

		</form>
		<!-- END MODULES_EDIT -->
	</div>
</div>
<!-- INCLUDE include/scripts.tpl -->
</body>
</html>
