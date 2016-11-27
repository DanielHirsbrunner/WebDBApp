<!-- INCLUDE include/head.tpl -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<!-- INCLUDE include/flash_message.tpl -->
		<!-- INCLUDE include/admin_menu.tpl -->

		<p class="pull-left"><a href="{BASE_PATH}/deliveries/add" class="btn btn-default"> + Create new mode of delivery</a></p>
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
				<!-- BEGIN DELIVERY_ROW -->
				<tr>
					<td>{DELIVERY_ID}</td><td>{DELIVERY_DESC}</td>
					<td><a href="{BASE_PATH}/deliveries/edit/{DELIVERY_ID}"><span class="glyphicon glyphicon-pencil"></a>
						<a class="ajax-delete" href="{BASE_PATH}/deliveries/delete/{DELIVERY_ID}"><span class="glyphicon glyphicon-remove"></a>
					</td>
				</tr>
				<!-- END DELIVERY_ROW -->
			</tbody>
		</table>
	</div>
</div>
<!-- INCLUDE include/scripts.tpl -->
</body>
</html>
