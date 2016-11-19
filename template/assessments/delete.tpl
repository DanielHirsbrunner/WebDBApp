<!-- INCLUDE include/head.tpl -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<!-- INCLUDE include/flash_message.tpl -->
		<p><a href="{BASE_PATH}/users">Manage users</a> <a href="{BASE_PATH}/modules">Manage modules</a></p>

		<form action="" method="post" class="form-horizontal">
			<h4>Do you really want to delete assessment type <i>{DELETE_ASSESSMENT_DESC}</i>?</h4>

			<a href="{BASE_PATH}/modules" class="btn btn-info">Cancel</a>
			<input type="submit" name="delete" class="btn btn-danger" value="Delete">
		</form>
	</div>
</div>
<!-- INCLUDE include/scripts.tpl -->
</body>
</html>
