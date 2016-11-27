$(document).ready(function() {
	function ajaxDelete() {
		$(".ajax-delete").click(function() {
			var url = $(this).prop("href");
			var temp = url.split("/");
			var id = temp[temp.length-1];
			swal({
				title: "Delete",
				text: "Are you sure you want to delete this record?",
				type: "warning",
				showCancelButton: true,
				closeOnConfirm: false,
				showLoaderOnConfirm: true,
			}, function() {
				$.post(url, { delete: "ajax" }).done(function(msg) {
					$("#table").bootstrapTable('remove', {field: 'id', values: [id]});
					swal({
						html: true,
						title: "Deleted!",
						text: msg,
						type: "success"
					});
				}).fail(function(msg) {
					swal({
						html: true,
						title: "Error!",
						text: msg.responseText,
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
