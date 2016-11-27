<script type="text/javascript" src="{BASE_PATH}/js/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="{BASE_PATH}/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{BASE_PATH}/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="{BASE_PATH}/js/bootstrap-table.min.js"></script>
<script type="text/javascript" src="{BASE_PATH}/js/bootstrap-table-toolbar.min.js"></script>
<script type="text/javascript" src="{BASE_PATH}/js/sweetalert.min.js"></script>


<script>
$(document).ready(function() {
	function ajaxDelete() {
		$(".ajax-delete").click(function() {
			var urls = $(this).prop("href").split("/");
			var url = "/"+urls[urls.length-3]+"/"+urls[urls.length-2]+"/"+urls[urls.length-1];
			var id = urls[urls.length-1];
			swal({
				title: "Delete",
				text: "Are you sure you want to delete this record?",
				type: "warning",
				showCancelButton: true,
				closeOnConfirm: false,
				showLoaderOnConfirm: true, 
			}, function() {
				$.post("{BASE_PATH}"+url, { delete: "ajax" }).done(function(data) {
					console.log(id);
					$("#table").bootstrapTable('remove', {field: 'id', values: [id]});
					swal({
						title: "Deleted!",
						type: "success"
					});
				}).fail(function() {
					swal({
						title: "Error! ",
						text: "This record cannot be deleted.\nThere is probably another record with relation to this one.",
						type: "error"
					});
				});
			});
			return false;
		});
	}
	$("#table").on("post-body.bs.table, all.bs.table", ajaxDelete);
	ajaxDelete();

});
</script>