<!-- INCLUDE include/head.html -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<p><a href="{BASE_PATH}/users">Manage users</a> <a href="{BASE_PATH}/modules">Manage modules</a></p>

		<!-- BEGIN USER_NOT_FOUND -->
		<p>User was not found.</p>
		<!-- END USER_NOT_FOUND -->

		<!-- BEGIN USER_DELETE -->
		<form action="" method="post" class="form-horizontal">
			<p>Do you really want to delete this user?</p>

			<div><input type="submit" class="btn btn-warning" value="Delete"></div>
			<input type="hidden" name="delete">
		</form>
		<!-- END USER_DELETE -->
	</div>
</div>

</body>
</html>
