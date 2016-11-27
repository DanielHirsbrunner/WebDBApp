<!-- INCLUDE include/head.tpl -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<!-- INCLUDE include/flash_message.tpl -->
		<!-- INCLUDE include/admin_menu.tpl -->

		<p class="pull-left"><a href="{BASE_PATH}/mqf/add" class="btn btn-default"> + Create new MQF skill</a></p>
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
				<th data-field="description" data-sortable="true">Description</th>
				<th data-searchable="false">&nbsp;</th>
			</tr></thead>
			<tbody>
				<!-- BEGIN MQF_ROW -->
				<tr>
					<td>{MQF_ID}</td><td>{MQF_DESC}</td>
					<td><a href="{BASE_PATH}/mqf/edit/{MQF_ID}"><span class="glyphicon glyphicon-pencil"></a>
						<a href="{BASE_PATH}/mqf/delete/{MQF_ID}"><span class="glyphicon glyphicon-remove"></a>
					</td>
				</tr>
				<!-- END MQF_ROW -->
			</tbody>
		</table>
	</div>
</div>
<!-- INCLUDE include/scripts.tpl -->
</body>
</html>
