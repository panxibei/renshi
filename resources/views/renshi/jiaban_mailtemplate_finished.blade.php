<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
	<title>【{{$site_title}}】 您的申请已经通过</title>
</head>
<body>

<div id="app">

	<strong>{{$agent_name}}</strong> 您好！

	<br><br>
	编号为 [{{$uuid}}] 的申请已经通过，请点击 <a href="{{ route('portal') }}">{{$site_title}}</a> 登录查看详情。

    <br><br>

</div>

</body>
</html>