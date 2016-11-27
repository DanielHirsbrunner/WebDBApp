<!-- INCLUDE include/head.tpl -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<!-- INCLUDE include/flash_message.tpl -->
		<!-- INCLUDE include/admin_menu.tpl -->

		<!-- BEGIN RESULTS_SELECT -->
		<div class="pull-left">
			<!-- BEGIN RESULTS_SELECT2 -->
			<form action="{BASE_PATH}/users/modules/{FORM_ACTION_USER_ID}/add" method="post">
				<select class="selectpicker" data-live-search="true" name="module">
					<!-- BEGIN SELECT_OPTION -->
					<option value="{MODULE_ID}">{NAME} - {CODE}</option>
					<!-- END SELECT_OPTION -->
				</select>
				<input type="submit" class="btn btn-default" value="+ Add">
			</form>
			<!-- END RESULTS_SELECT2 -->
			<!-- BEGIN ALL_MODULES_ASSIGNED -->
			All modules are already assigned.
			<!-- END ALL_MODULES_ASSIGNED -->
		</div>
		<!-- END RESULTS_SELECT -->
		<table class="table"
				id="table"
				data-toggle="table"
				data-unique-id="id"

				data-show-columns="true"

				data-search="true"
				data-advanced-search="true"
				data-id-table="advancedTable">
			<thead><tr>
				<th data-field="id" data-sortable="true">#</th>
				<th data-field="name" data-sortable="true">Name</th>
				<th data-field="code" data-sortable="true">Code</th>
				<th data-searchable="false">&nbsp;</th>
				<th data-searchable="false">&nbsp;</th>
			</tr></thead>
			<tbody>
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
			</tbody>
		</table>
	</div>
</div>
<!-- INCLUDE include/scripts.tpl -->
</body>
</html>
