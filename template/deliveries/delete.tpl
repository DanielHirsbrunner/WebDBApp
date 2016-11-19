<!-- INCLUDE include/head.tpl -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<!-- INCLUDE include/flash_message.tpl -->
		<!-- INCLUDE include/admin_menu.tpl -->

		<form action="" method="post" class="form-horizontal">
			<h4>Do you really want to delete mode of delivery <i>{DELETE_DELIVERY_DESC}</i>?</h4>

			<a href="{BASE_PATH}/deliveries" class="btn btn-info">Cancel</a>
			<input type="submit" name="delete" class="btn btn-danger" value="Delete">
		</form>
	</div>
</div>
<!-- INCLUDE include/scripts.tpl -->
</body>
</html>
