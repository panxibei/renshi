<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Test Muse</title>
    <!-- 引入样式 -->
    <link rel="stylesheet" href="{{ asset('statics/muse-ui/muse-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('statics/muse-ui/roboto.css') }}">
    <link rel="stylesheet" href="{{ asset('statics/muse-ui/material-icons.css') }}">

<style type="text/css">


</style>
	
</head>
<body>
<div id="app">

    <mu-button color="primary">Primary</mu-button>
    <br><br>
    <mu-button full-width color="primary" @click="openSimpleDialog">full width button</mu-button>
    <mu-dialog title="Dialog" width="340" :open.sync="openSimple">
        this is simple Dialog
        <mu-button slot="actions" flat color="primary" @click="closeSimpleDialog">Close</mu-button>
    </mu-dialog>

	<mu-button class="demo-button" color="primary" @click="alert()">Alert</mu-button>
	<!-- <mu-button class="demo-button" color="secondary" @click="confirm()">Confirm</mu-button>
	<mu-button class="demo-button" color="teal" @click="prompt()">Prompt</mu-button> -->


	
</div>
</body>
<script src="{{ asset('js/vue.min.js') }}"></script>
<!-- 引入组件库 -->
<script src="{{ asset('statics/muse-ui/muse-ui.js') }}"></script>



<script type="text/javascript">
var vm_app = new Vue({
	el: '#app',
	data: {

        username: '',
        password: '',
        phone: '',

        openSimple: false,



	},
	methods: {
        handleClick () {
            console.log('aaaaaaa');
            

        },

        openSimpleDialog () {
            this.openSimple = true;
        },
        closeSimpleDialog () {
            this.openSimple = false;
        },



		alert () {
			// this.$toast.message('Hello world');
			console.log('不用import引入，好像不能直接用message等组件。暂时放弃！');
		},




	},
	mounted: function () {
	}
})
</script>
<script>
// 对Date的扩展，将 Date 转化为指定格式的String
// 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符，
// 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字)
// 例子：
// (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423
// (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18
// let time1 = new Date().Format("yyyy-MM-dd");
// let time2 = new Date().Format("yyyy-MM-dd HH:mm:ss");
 
Date.prototype.Format = function (fmt) { //author: meizz
    let o = {
        "M+": this.getMonth() + 1, //月份
        "d+": this.getDate(), //日
        "h+": this.getHours(), //小时
        "m+": this.getMinutes(), //分
        "s+": this.getSeconds(), //秒
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度
        "S": this.getMilliseconds() //毫秒
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (let k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
};
</script>
</html>