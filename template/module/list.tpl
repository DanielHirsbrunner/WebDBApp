<!-- INCLUDE include/head.tpl -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<!-- INCLUDE include/flash_message.tpl -->
		<!-- INCLUDE include/admin_menu.tpl -->
		<div class="row">
			<div class="col-sm-3 modules">
				<!-- INCLUDE include/modules.tpl -->
			</div>

			<div class="col-sm-9 syllabuses">
				<p class="pull-left"><a href ="{BASE_PATH}/syllabusWizard/0" class="btn btn-default">+ new empty syllabus</a></p>
				<table class="table"
					id="table"
					data-toggle="table"
					data-unique-id="id"

					data-show-columns="true"

					data-search="true"
					data-advanced-search="true"
					data-id-table="advancedTable">
				<thead><tr>
					<th data-field="version" data-sortable="true">Version</th>
					<th data-field="last_change">Last change</th>
					<th data-field="edited_by" data-sortable="true">Edited by</th>
					<th data-searchable="false">&nbsp;&nbsp;</th>
				</tr></thead>
				<tbody>
					<!-- BEGIN SYLLABUS_LIST -->
					<tr>
						<td>{SYLLABUS_VERSION}</td><td>{SYLLABUS_EDITTS}</td><td>{SYLLABUS_EDITBY}</td>
						<td><a href="{BASE_PATH}/syllabusWizard/{SYLLABUS_ID}"><span class="glyphicon glyphicon-pencil"></a>
							<a href="{BASE_PATH}/syllabusDelete/{SYLLABUS_ID}"><span class="glyphicon glyphicon-remove"></a>
							<a href="{BASE_PATH}/syllabusPrint/{SYLLABUS_ID}"><span class="glyphicon glyphicon-print"></a>
						</td>
					</tr>
					<!-- END SYLLABUS_LIST -->
				</tbody>
				</table>
				<br/><br/>
			</div>
		</div>
	</div>
</div>
<!-- INCLUDE include/scripts.tpl -->
</body>
</html>
