<!-- INCLUDE include/head.html -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<div class="row">
			<div class="col-sm-3 modules">
				<!-- INCLUDE include/modules.tpl -->
			</div>

			<div class="col-sm-9 syllabuses">
				<table>
					<tr class="table-heading"><th>Version</th><th>Last change</th><th>Edited by</th><th>&nbsp;&nbsp;</th></tr>
					<!-- BEGIN SYLLABUS_LIST -->
					<tr>
						<td>{SYLLABUS_VERSION}</td><td>{SYLLABUS_EDITTS}</td><td>{SYLLABUS_EDITBY}</td>
						<td><a href="{BASE_PATH}/edit/{SYLLABUS_ID}"><span class="glyphicon glyphicon-pencil"></a>
							<a href="{BASE_PATH}/delete/{SYLLABUS_ID}"><span class="glyphicon glyphicon-remove"></a>
							<a href="{BASE_PATH}/print/{SYLLABUS_ID}"><span class="glyphicon glyphicon-print"></a>
						</td>
					</tr>
					<!-- END SYLLABUS_LIST -->
				</table>
			</div>
		</div>
	</div>
</div>

</body>
</html>
