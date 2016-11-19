<!-- INCLUDE include/head.tpl -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<!-- INCLUDE include/flash_message.tpl -->
		<!-- INCLUDE include/admin_menu.tpl -->

		<form action="" method="post" class="form-horizontal">
			<h4>Do you really want to delete user <i>{DELETE_USER_NAME}</i>?</h4>

			<a href="{BASE_PATH}/users" class="btn btn-info">Cancel</a>
			<input type="submit" name="delete" class="btn btn-danger" value="Delete">
		</form>
	</div>
</div>
<!-- INCLUDE include/scripts.tpl -->
</body>
</html>
