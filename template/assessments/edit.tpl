<!-- INCLUDE include/head.tpl -->

<body>

<div class="container">
	<!-- INCLUDE include/header.tpl -->

	<div class="content">
		<!-- INCLUDE include/flash_message.tpl -->
		<!-- INCLUDE include/admin_menu.tpl -->

		<!-- BEGIN ASSESSMENT_TYPES_EDIT -->
		<form action="" method="post" class="form-horizontal">

			<div class="row form-group required {ERROR_DESC}">
				<div class="col-sm-offset-1 col-sm-2 control-label"><label for="description">Description</label></div>
				<div class="col-sm-8"><input type="text" name="description" class="form-control" id="description" value="{VALUE_DESC}" required></div>
			</div>
			<!-- BEGIN ERROR_DESC_LONG -->
			<div class="row form-group has-error">
				<div class="col-sm-offset-3 help-block">Description is too long. Maximum is 100 characters.</div>
			</div>
			<!-- END ERROR_DESC_LONG -->
			<div class="row form-group">
				<div class="col-sm-offset-1 col-sm-2 control-label"><label for="isWrittenTest">Is written test</label></div>
				<div class="col-sm-8"><input type="checkbox" class="checkbox" name="isWrittenTest" {VALUE_ISWRITTENTEST} id="isWrittenTest"></div>
			</div>
			
			<div class="row form-group">
				<div class="col-sm-offset-3 col-sm-6"><input type="submit" class="btn btn-primary" value="{VALUE_BUTTON}"></div>
			</div>

		</form>
		<!-- END ASSESSMENT_TYPES_EDIT -->
	</div>
</div>
<!-- INCLUDE include/scripts.tpl -->
</body>
</html>
