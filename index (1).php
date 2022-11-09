<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Notification Example</title>
</head>
<body>

</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script type="text/javascript">
	let permission = Notification.permission;
	if(permission === "granted"){
		showNotification();
	}else if(permission === "default"){
		requestAndShowPermission();
	}else{
		alert("ok");
	}
	function requestAndShowPermission(){
		Notification.requestPermission(function(permission){
			if(permission === "granted"){
				showNotification();
			}
		});
	}
	function showNotification(){
		$.ajax({
			type:"POST",
			url:"data.php",
			success:function(data){
				data = JSON.parse(data);
				let title = data.title;
				let icon = data.icon;
				let body = data.body;
				let notification = new Notification(title, {body, icon});
				notification.onclick = () => {
					notification.close();
					window.parent.focus();
				}
			}
		});
	}
</script>