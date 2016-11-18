<!-- INCLUDE include/head.tpl -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<!-- INCLUDE include/flash_message.tpl -->
		<p><a href="{BASE_PATH}/users">Manage users</a> <a href="{BASE_PATH}/modules">Manage modules</a></p>
		<p><a href="{BASE_PATH}/modules/add" class="btn btn-default"> + Create new module</a></p>
		<!-- BEGIN RESULTS_TABLE -->
		<table>
			<tr class="table-heading"><th>#</th><th>Name</th><th>Code</th><th>Credits</th><th>&nbsp;</th></tr>
			<!-- BEGIN MODULES_ROW -->
			<tr>
				<td>{MODULE_ID}</td><td>{NAME}</td><td>{CODE}</td><td>{CREDITS}</td>
				<td><a href="{BASE_PATH}/modules/edit/{MODULE_ID}"><span class="glyphicon glyphicon-pencil"></a>
					<a href="{BASE_PATH}/modules/delete/{MODULE_ID}"><span class="glyphicon glyphicon-remove"></a>
				</td>
			</tr>
			<!-- END MODULES_ROW -->
		</table>
		<!-- END RESULTS_TABLE -->
	</div>
</div>
<!-- INCLUDE include/scripts.tpl -->
</body>
</html>
