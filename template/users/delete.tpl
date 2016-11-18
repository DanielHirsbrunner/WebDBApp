<!-- INCLUDE include/head.tpl -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<!-- INCLUDE include/flash_message.tpl -->
		<p><a href="{BASE_PATH}/users">Manage users</a> <a href="{BASE_PATH}/modules">Manage modules</a></p>

		<!-- BEGIN USER_NOT_FOUND -->
		<p><b>User was not found.</b></p>
		<a href="{BASE_PATH}/users" class="btn btn-info">Go back to list</a>
		<!-- END USER_NOT_FOUND -->

		<!-- BEGIN USER_DELETE -->
		<form action="" method="post" class="form-horizontal">
			<h4>Do you really want to delete user <i>{DELETE_USER_NAME}</i>?</h4>

			<a href="{BASE_PATH}/users" class="btn btn-info">Cancel</a>
			<input type="submit" name="delete" class="btn btn-danger" value="Delete">
		</form>
		<!-- END USER_DELETE -->
	</div>
</div>
<!-- INCLUDE include/scripts.tpl -->
</body>
</html>
