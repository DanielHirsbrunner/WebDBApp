<!-- INCLUDE include/head.tpl -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<!-- INCLUDE include/flash_message.tpl -->
		<!-- INCLUDE include/admin_menu.tpl -->

		<p><a href="{BASE_PATH}/modules/add" class="btn btn-default"> + Create new module</a></p>
		<table>
			<tr class="table-heading"><th>#</th><th>Name</th><th>Code</th><th>Credits</th><th>&nbsp;</th></tr>
			<!-- BEGIN MODULES_ROW -->
			<tr>
				<td>{MODULE_ID}</td><td>{MODULE_NAME}</td><td>{MODULE_CODE}</td><td>{MODULE_CREDITS}</td>
				<td><a href="{BASE_PATH}/modules/edit/{MODULE_ID}"><span class="glyphicon glyphicon-pencil"></a>
					<a href="{BASE_PATH}/modules/delete/{MODULE_ID}"><span class="glyphicon glyphicon-remove"></a>
				</td>
			</tr>
			<!-- END MODULES_ROW -->
		</table>
	</div>
</div>
<!-- INCLUDE include/scripts.tpl -->
</body>
</html>
