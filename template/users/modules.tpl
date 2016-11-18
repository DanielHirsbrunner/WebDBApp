<!-- INCLUDE include/head.tpl -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<!-- INCLUDE include/flash_message.tpl -->
		<p><a href="{BASE_PATH}/users">Manage users</a> <a href="{BASE_PATH}/modules">Manage modules</a></p>
		<p>
			<!-- BEGIN RESULTS_SELECT -->
			<form action="{BASE_PATH}/users/modules/{FORM_ACTION_USER_ID}/add" method="post">
				<select class="selectpicker" data-live-search="true" name="module">
					<!-- BEGIN SELECT_OPTION -->
					<option value="{MODULE_ID}" data-tokens="{NAME} {CODE}">{NAME} ({CODE})</option>
					<!-- END SELECT_OPTION -->
				</select>
				<input type="submit" class="btn btn-default" value="+ Add">
			</form>
			<!-- END RESULTS_SELECT -->
			<!-- BEGIN ALL_MODULES_ASSIGNED -->
			All modules are already assigned.
			<!-- END ALL_MODULES_ASSIGNED -->
		</p>
		<table>
			<tr class="table-heading"><th>#</th><th>Name</th><th>Code</th><th>&nbsp;</th><th>&nbsp;</th></tr>
			<!-- BEGIN MODULES_ROW -->
			<tr>
				<td>{MODULE_ID}</td><td>{NAME}</td><td>{CODE}</td>
				<td><a href="{BASE_PATH}/users/modules/{USER_ID}/remove/{MODULE_ID}">Unassign</a></td>
				<td><a href="{BASE_PATH}/modules/edit/{MODULE_ID}"><span class="glyphicon glyphicon-pencil"></a>
					<a href="{BASE_PATH}/modules/delete/{MODULE_ID}"><span class="glyphicon glyphicon-remove"></a>
				</td>
			</tr>
			<!-- END MODULES_ROW -->
			<!-- BEGIN NO_MODULES_ASSIGNED -->
			<tr>
				<td colspan="5">No assigned modules found.</td>
			</tr>
			<!-- END NO_MODULES_ASSIGNED -->
		</table>
	</div>
</div>
<!-- INCLUDE include/scripts.tpl -->
</body>
</html>
