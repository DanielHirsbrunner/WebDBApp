<!-- INCLUDE include/head.tpl -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<!-- INCLUDE include/flash_message.tpl -->
		<!-- INCLUDE include/admin_menu.tpl -->

		<p><a href="{BASE_PATH}/deliveries/add" class="btn btn-default"> + Create new mode of delivery</a></p>
		<table>
			<tr class="table-heading"><th>#</th><th>Description</th><th>&nbsp;</th></tr>
			<!-- BEGIN DELIVERY_ROW -->
			<tr>
				<td>{DELIVERY_ID}</td><td>{DELIVERY_DESC}</td>
				<td><a href="{BASE_PATH}/deliveries/edit/{DELIVERY_ID}"><span class="glyphicon glyphicon-pencil"></a>
					<a href="{BASE_PATH}/deliveries/delete/{DELIVERY_ID}"><span class="glyphicon glyphicon-remove"></a>
				</td>
			</tr>
			<!-- END DELIVERY_ROW -->
		</table>
	</div>
</div>
<!-- INCLUDE include/scripts.tpl -->
</body>
</html>
