<!-- INCLUDE include/head.tpl -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<!-- INCLUDE include/flash_message.tpl -->
		<!-- INCLUDE include/admin_menu.tpl -->

		<p><a href="{BASE_PATH}/mqf/add" class="btn btn-default"> + Create new MQF skill</a></p>
		<table>
			<tr class="table-heading"><th>#</th><th>Description</th><th>&nbsp;</th></tr>
			<!-- BEGIN MQF_ROW -->
			<tr>
				<td>{MQF_ID}</td><td>{MQF_DESC}</td>
				<td><a href="{BASE_PATH}/mqf/edit/{MQF_ID}"><span class="glyphicon glyphicon-pencil"></a>
					<a href="{BASE_PATH}/mqf/delete/{MQF_ID}"><span class="glyphicon glyphicon-remove"></a>
				</td>
			</tr>
			<!-- END MQF_ROW -->
		</table>
	</div>
</div>
<!-- INCLUDE include/scripts.tpl -->
</body>
</html>
