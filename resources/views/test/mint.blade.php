<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Test Mint</title>
    <!-- 引入样式 -->
    <link rel="stylesheet" href="{{ asset('statics/mint-ui/lib/style.css') }}">

<style type="text/css">


</style>
	
</head>
<body>
<div id="app">

<mt-field label="用户名" placeholder="请输入用户名" v-model="username"></mt-field>
<mt-field label="密码" placeholder="请输入密码" type="password" v-model="password"></mt-field>

<mt-button type="primary" size="large" @click.native="handleClick">default</mt-button>


<mt-range v-model="rangeValue">
	<div slot="start">0</div>
	<div slot="end">100</div>
</mt-range>


<mt-picker :slots="slots" @change="onValuesChange"></mt-picker>


<mt-button type="primary" size="large" @click.native="openPicker">Open Datetime</mt-button>
<mt-datetime-picker
	ref="picker"
	type="date"
	v-model="pickerValue"
	startDate="startDate1">
</mt-datetime-picker>



	
</div>
</body>
<script src="{{ asset('js/vue.min.js') }}"></script>
<!-- 引入组件库 -->
<script src="{{ asset('statics/mint-ui/lib/index.js') }}"></script>



<script type="text/javascript">
var vm_app = new Vue({
	el: '#app',
	data: {

        username: '',
        password: '',

		rangeValue: 10,
		
		slots: [
			{
				flex: 1,
				values: ['2015-01', '2015-02', '2015-03', '2015-04', '2015-05', '2015-06'],
				className: 'slot1',
				textAlign: 'right'
			}, {
				divider: true,
				content: '-',
				className: 'slot2'
			}, {
				flex: 1,
				values: ['2015-01', '2015-02', '2015-03', '2015-04', '2015-05', '2015-06'],
				className: 'slot3',
				textAlign: 'left'
			}
		],

		pickerValue: new Date(),
		startDate1: new Date(),


	},
	methods: {
        handleClick() {
			console.log('aaaaaaa');
			this.$messagebox('提示', '操作成功');
			this.$toast('提示信息');

        },


		onValuesChange(picker, values) {
			if (values[0] > values[1]) {
				picker.setSlotValue(1, values[0]);
			}
		},


		openPicker() {
			this.$refs.picker.open();
		},



		



	},
	mounted: function () {

	}
})
</script>
</html>