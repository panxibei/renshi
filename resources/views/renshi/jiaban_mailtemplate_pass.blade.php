<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
	<title>【{{$site_title}}】 您有一条来自 [{{$agent_name}}] 的新消息等待处理</title>
</head>
<body>

<div id="app">

	<strong>{{$auditor}}</strong> 您好！

	<br><br>
	您有一条来自 <strong>{{$agent_name}}</strong> 的新消息等待处理，请点击 <a href="{{ route('portal') }}">{{$site_title}}</a> 登录查看详情。

    <br><br>

</div>

</body>
</html>