<!-- INCLUDE include/head.tpl -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<!-- INCLUDE include/flash_message.tpl -->
		<!-- INCLUDE include/admin_menu.tpl -->

		<p class="pull-left"><a href="{BASE_PATH}/modules/add" class="btn btn-default"> + Create new module</a></p>
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
				<th data-field="credits" data-sortable="true">Credits</th>
				<th data-searchable="false">&nbsp;</th>
			</tr></thead>
			<tbody>
				<!-- BEGIN MODULES_ROW -->
				<tr>
					<td>{MODULE_ID}</td><td>{MODULE_NAME}</td><td>{MODULE_CODE}</td><td>{MODULE_CREDITS}</td>
					<td><a href="{BASE_PATH}/modules/edit/{MODULE_ID}"><span class="glyphicon glyphicon-pencil"></a>
						<a href="{BASE_PATH}/modules/delete/{MODULE_ID}"><span class="glyphicon glyphicon-remove"></a>
					</td>
				</tr>
				<!-- END MODULES_ROW -->
			</tbody>
		</table>
	</div>
</div>
<!-- INCLUDE include/scripts.tpl -->
</body>
</html>
