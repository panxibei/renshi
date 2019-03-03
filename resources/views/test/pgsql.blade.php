<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
	<title>test pgsql</title>
</head>
<body>

mysql: <br>
@foreach($data1 as $value)
    {{ $value }}
@endforeach

<br><br>

pgsql:<br>
@foreach($data2 as $value)
    {{ $value }}
@endforeach

<br><br>

mssql:<br>
@foreach($data3 as $value)
    {{ $value }}
@endforeach


</body>
</html>