<!-- INCLUDE include/head.tpl -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<!-- INCLUDE include/flash_message.tpl -->
		<!-- INCLUDE include/admin_menu.tpl -->

		<p class="pull-left"><a href="{BASE_PATH}/users/add" class="btn btn-default"> + Create new user</a></p>
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
				<th data-field="username" data-sortable="true">Username</th>
				<th data-field="fullname" data-sortable="true">Full name</th>
				<th data-field="email" data-sortable="true">Email</th>
				<th data-field="admin" data-sortable="true">Admin</th>
				<th data-searchable="false">Modules</th>
				<th data-searchable="false">&nbsp;</th>
		</tr></they>
		<tbody>
			<!-- BEGIN USERS_ROW -->
			<tr>
				<td>{USER_ID}</td><td>{USERNAME}</td><td>{FULLNAME}</td><td>{EMAIL}</td><td>{ADMIN}</td>
				<td><a href="{BASE_PATH}/users/modules/{USER_ID}">Edit modules</a></td>
				<td><a href="{BASE_PATH}/users/edit/{USER_ID}"><span class="glyphicon glyphicon-pencil"></a>
					<a href="{BASE_PATH}/users/delete/{USER_ID}"><span class="glyphicon glyphicon-remove"></a>
				</td>
			</tr>
			<!-- END USERS_ROW -->
		</tbody>
		</table>
	</div>
</div>
<!-- INCLUDE include/scripts.tpl -->
</body>
</html>
