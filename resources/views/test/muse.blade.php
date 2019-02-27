<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Test Muse</title>
    <!-- 引入样式 -->
    <link rel="stylesheet" href="{{ asset('statics/muse-ui/muse-ui.css') }}">

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








		handleClose: function () {
			this.show = false;
		},
		mylabel: function (h) {
			return h('div', [
				h('span', '标签一'),
				h('Badge', {
					props: {
						count: 3
					}
				})
			])
		},
		switchchange: function (status) {
			this.$Message.info('开关状态：' + status);
		},
		showperson: function (index) {
			alert(index);
			this.$Modal.info({
				title: 'User Info',
				content: `Date：${this.data5[index].date}<br>Name：${this.data5[index].name}<br>Age：${this.data5[index].age}<br>Address：${this.data5[index].address}`
			})
		},
		removeperson: function (index) {
			alert(index);
		},
		datepickerchange: function () {
			//alert(this.datepicker1.Format("yyyy-MM-dd hh:mm:ss.S"));
			alert(this.datepicker1.Format("yyyy-MM-dd hh:mm:ss"));
			
		},
		getMockData: function () {
			let mockData = [];
			for (let i = 1; i <= 20; i++) {
				mockData.push({
					key: i.toString(),
					label: 'Content ' + i,
					description: 'The desc of content  ' + i,
					disabled: Math.random() * 3 < 1
				});
			}
			return mockData;
		},
		getTargetKeys: function () {
			return this.getMockData()
					.filter(() => Math.random() * 2 > 1)
					//.map(item => item.key);
					.map(function (item) {return item.key});
		},
		render1: function (item) {
			return item.label;
		},
		handleChange1: function (newTargetKeys, direction, moveKeys) {
			console.log(newTargetKeys);
			console.log(direction);
			console.log(moveKeys);
			this.targetKeystransfer = newTargetKeys;
		},
		handleSubmit(name) {
			this.$refs[name].validate((valid) => {
				if (valid) {
					this.$Message.success('Success!');
				} else {
					this.$Message.error('Fail!');
				}
			})
		},
		handleReset (name) {
			this.$refs[name].resetFields();
		},
		loading () {
			const msg = this.$Message.loading({
				content: 'Loading... 3秒后会自动关闭。当然你可以点击按钮关闭我。',
				duration: 0
			});
			setTimeout(msg, 3000);
		},
		poptipok () {
			this.$Message.info('You click ok');
		},
		poptipcancel () {
			this.$Message.info('You click cancel');
		},
		stepnext () {
			if (this.stepcurrent == 3) {
				this.stepcurrent = 0;
			} else {
				this.stepcurrent += 1;
			}
		},
		loadingbarstart () {
			this.$Loading.start();
		},
		loadingbarfinish () {
			this.$Loading.finish();
		},
		loadingbarerror () {
			this.$Loading.error();
		},


		handleReachBottom () {
			return new Promise(resolve => {
				setTimeout(() => {
					const last = this.list1[this.list1.length - 1];
					for (let i = 1; i < 21; i++) {
						this.list1.push(last + i);
					}
					resolve();
				}, 2000);
			});
		},



	},
	mounted: function () {
		this.datatransfer = this.getMockData();
        this.targetKeystransfer = this.getTargetKeys();
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