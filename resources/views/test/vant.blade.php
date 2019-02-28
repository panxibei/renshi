<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Test Mint</title>
    <!-- 引入样式 -->
    <link rel="stylesheet" href="{{ asset('statics/vant/index.css') }}">

<style type="text/css">


</style>
	
</head>
<body>
<div id="app">

<van-button type="info" @click="handleClick">信息按钮</van-button>

<van-cell-group>
  <van-field
    v-model="username"
    required
    clearable
    label="用户名"
    right-icon="question-o"
    placeholder="请输入用户名"
    @click-right-icon="$toast('question')"
  ></van-field>

  <van-field
    v-model="password"
    type="password"
    label="密码"
    placeholder="请输入密码"
    required
  ></van-field>
</van-cell-group>

<van-button type="info" @click="visibled0=!visibled0">显示日期</van-button>

<span v-if="visibled0">
<van-datetime-picker
  v-model="currentDate"
  type="datetime"
  :min-date="minDate"
  :max-date="maxDate"
></van-datetime-picker>
</span>




	
</div>
</body>
<script src="{{ asset('js/vue.min.js') }}"></script>
<!-- 引入组件库 -->
<script src="{{ asset('statics/vant/vant.min.js') }}"></script>



<script type="text/javascript">
var vm_app = new Vue({
	el: '#app',
	data: {

        username: '',
        password: '',

		rangeValue: 10,
		
		minHour: 10,
        maxHour: 20,
        minDate: new Date(),
        maxDate: new Date(2019, 10, 1),
        currentDate: new Date(),
        visibled0: false,


	},
	methods: {
        handleClick() {
			console.log('aaaaaaa');
			this.$dialog.alert({
                title: '标题',
                message: '弹窗内容'
                }).then(() => {
                // on close
            });

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