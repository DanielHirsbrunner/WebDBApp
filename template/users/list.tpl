<!-- INCLUDE include/head.html -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<p><a href="/users">Manage users</a> <a href="/modules">Manage modules</a></p>
		<p><a href="/users/add">Create new user</a></p> 
		<!-- BEGIN RESULTS_TABLE -->
		<table>
			<tr class="table-heading"><th>Username</th><th>Full name</th><th>Email</th><th>Admin</th><th>Modules</th><th>&nbsp;&nbsp;</th></tr>
			<!-- BEGIN USERS_LIST -->
			<tr>
				<td>{USERNAME}</td><td>{FULLNAME}</td><td>{EMAIL}</td><td>{ADMIN}</td>
				<td><a href="/users/modules/{USER_ID}">Edit modules</a></td>
				<td><a href="/users/edit/{USER_ID}"><span class="glyphicon glyphicon-pencil"></a>
					<a href="/users/delete/{USER_ID}"><span class="glyphicon glyphicon-remove"></a>
				</td>
			</tr>
			<!-- END USERS_LIST -->
		</table>
		<!-- END RESULTS_TABLE -->
	</div>
</div>

</body>
</html>
