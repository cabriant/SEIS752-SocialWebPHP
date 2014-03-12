function searchForUser() {

	var userQ = document.getElementById('user_query').value;

	if (userQ == null || userQ == "") {
		alert("Please enter a name to search.");
		return false;
	}

	var params = "action=query_user&user_query=" + userQ;	
	var url = "searchAjax.php";
	
	if(window.XMLHttpRequest)
	{
		//code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else
	{
		//code for IE6,IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	//sending the values using post method
	xmlhttp.open("POST", url, true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	//xmlhttp.setRequestHeader("Content-length", params.length);
	xmlhttp.send(params);
	xmlhttp.onreadystatechange = function()
	{
		if(xmlhttp.readyState === 4 && xmlhttp.status === 200)
		{
			document.getElementById("search_results_container").innerHTML=xmlhttp.responseText;
		}
	};
}