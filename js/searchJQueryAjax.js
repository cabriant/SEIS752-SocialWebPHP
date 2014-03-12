$(document).ready(function () {
	$("#user_query").on('keydown', function (e) {
		if (e.keyCode == 13) {
			$("#search_button").click();
		}
	});

	$("#search_button").on('click', function () {
		searchForUser();
	});
});

function searchForUser() {
	var userQ = $("#user_query").val();
	if (userQ == null || userQ == "") {
		alert("Please enter a name to search.");
		return false;
	}
	
	$.post("searchJQueryAjax.php", { action: "query_user", user_query: userQ }, function (data) {
		$("#search_results_container").html(data);
	});
}