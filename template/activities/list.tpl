<!-- INCLUDE include/head.tpl -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<!-- INCLUDE include/flash_message.tpl -->
		<!-- INCLUDE include/admin_menu.tpl -->

		<p><a href="{BASE_PATH}/activities/add" class="btn btn-default"> + Create new activity</a></p>
		<table>
			<tr class="table-heading"><th>#</th><th>Description</th><th>&nbsp;</th></tr>
			<!-- BEGIN ACTIVITY_ROW -->
			<tr>
				<td>{ACTIVITY_ID}</td><td>{ACTIVITY_DESC}</td>
				<td><a href="{BASE_PATH}/activities/edit/{ACTIVITY_ID}"><span class="glyphicon glyphicon-pencil"></a>
					<a href="{BASE_PATH}/activities/delete/{ACTIVITY_ID}"><span class="glyphicon glyphicon-remove"></a>
				</td>
			</tr>
			<!-- END ACTIVITY_ROW -->
		</table>
	</div>
</div>
<!-- INCLUDE include/scripts.tpl -->
</body>
</html>
