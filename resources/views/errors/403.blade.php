<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>403 error</title>
	<style type="text/css">
		/* 解决闪烁问题的CSS */
		[v-cloak] {	display: none; }
	</style>
	<script src="{{ asset('js/vue.min.js') }}"></script>
	<script src="{{ asset('js/axios.min.js') }}"></script>
	<script src="{{ asset('js/bluebird.min.js') }}"></script>
</head>
<body>

<div style="text-align:center;" id="error403" v-cloak>

	<strong style="font-size:72px;">403</strong>
	<h3>很抱歉，您没有访问该页面的权限！</h3>
	<span>@{{ time }} 秒后自动返回上一页</span>

</div>

<script>
var count = 3;
var vm_error403 = new Vue({
    el: '#error403',
    data: {
		time: count
    },
	mounted: function(){
		var _this = this;
		setInterval(function () {
			_this.time=count;
			Vue.set([_this.time],'time',count);
			count--;
			if (count<=0) {
				count=0;
				window.history.go(-1);
			}
		},1000)
	}
});
</script>
</body>
</html>