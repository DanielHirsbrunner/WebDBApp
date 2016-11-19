<!-- INCLUDE include/head.tpl -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<!-- INCLUDE include/flash_message.tpl -->
		<!-- INCLUDE include/admin_menu.tpl -->

		<p><a href="{BASE_PATH}/users/add" class="btn btn-default"> + Create new user</a></p>
		<table>
			<tr class="table-heading"><th>#</th><th>Username</th><th>Full name</th><th>Email</th><th>Admin</th><th>Modules</th><th>&nbsp;</th></tr>
			<!-- BEGIN USERS_ROW -->
			<tr>
				<td>{USER_ID}</td><td>{USERNAME}</td><td>{FULLNAME}</td><td>{EMAIL}</td><td>{ADMIN}</td>
				<td><a href="{BASE_PATH}/users/modules/{USER_ID}">Edit modules</a></td>
				<td><a href="{BASE_PATH}/users/edit/{USER_ID}"><span class="glyphicon glyphicon-pencil"></a>
					<a href="{BASE_PATH}/users/delete/{USER_ID}"><span class="glyphicon glyphicon-remove"></a>
				</td>
			</tr>
			<!-- END USERS_ROW -->
		</table>
	</div>
</div>
<!-- INCLUDE include/scripts.tpl -->
</body>
</html>
