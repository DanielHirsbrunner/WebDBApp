<!-- INCLUDE include/head.tpl -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<!-- INCLUDE include/flash_message.tpl -->
		<p><a href="{BASE_PATH}/users">Manage users</a> <a href="{BASE_PATH}/modules">Manage modules</a> <a href="{BASE_PATH}/assessments">Manage assessment types</a></p>
		<p><a href="{BASE_PATH}/assessments/add" class="btn btn-default"> + Create new assessment type</a></p>
		<table>
			<tr class="table-heading"><th>#</th><th>Description</th><th>&nbsp;</th></tr>
			<!-- BEGIN ASSESSMENTS_ROW -->
			<tr>
				<td>{ASSESSMENT_ID}</td><td>{ASSESSMENT_DESC}</td>
				<td><a href="{BASE_PATH}/assessments/edit/{ASSESSMENT_ID}"><span class="glyphicon glyphicon-pencil"></a>
					<a href="{BASE_PATH}/assessments/delete/{ASSESSMENT_ID}"><span class="glyphicon glyphicon-remove"></a>
				</td>
			</tr>
			<!-- END ASSESSMENTS_ROW -->
		</table>
	</div>
</div>
<!-- INCLUDE include/scripts.tpl -->
</body>
</html>
