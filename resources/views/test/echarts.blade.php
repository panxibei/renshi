<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
	<title>test echarts</title>
    <link rel="stylesheet" href="{{ asset('statics/iview/styles/iview.css') }}">
</head>
<body>

<div id="app">

    <br>
	<div id="main" style="width: 1200px;height:400px;"></div>

    <br><br>



    <br><br>




<br><br>

fafdasf
<br><br>

</div>




</body>
<script src="{{ asset('js/vue.min.js') }}"></script>
<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="{{ asset('js/bluebird.min.js') }}"></script>
<script src="{{ asset('statics/iview/iview.min.js') }}"></script>
<script src="{{ asset('statics/echarts/echarts.min.js') }}"></script>

<script type="text/javascript">

</script>

<script type="text/javascript">
var vm_app = new Vue({
    el: '#app',
	data: {

        modal_password_edit: false,
        
        modal_camera_show: false,
        camera_imgurl: '',


        // imgsrc: 'data:image/png;base64,R0lGODlhWAAfAJEAAAAAAP////8AAGZmZiH5BAAHAP8ALAAAAABYAB8AAALfhI+py+0PX5i02ouz3rxn44XiSHJgiaYqdq7uK7bwTFtyjcN3zqd7D4wBgkTSr4i87AQCCrPCfFoG1IGIWqtabUOLNPCVfgNY8rZTzp4py+Yk7B6nL9pp3T6f3O3KLrQJ6OYlqLdGUTaHuKZYwcjHdfEUOEh4aGiW4Vi4acnZeNkGBlb5Ror5mbmVqLrISgfq9zcqO4uxmup5enuKChk56RRHqKnbmktMnDvxI1kZRbpnmZccXTittXaUtB2gzY3k/U0ULg5EXs5zjo6jvk7T7q4TGz8+T28eka+/zx9RAAA7',
        imgsrc: '',
    },
	methods: {

        submitpic() {
			var _this = this;
			var imgurl = _this.camera_imgurl;
			// var imgurl = 'data:image/png;base64,R0lGODlhWAAfAJEAAAAAAP////8AAGZmZiH5BAAHAP8ALAAAAABYAB8AAALfhI+py+0PX5i02ouz3rxn44XiSHJgiaYqdq7uK7bwTFtyjcN3zqd7D4wBgkTSr4i87AQCCrPCfFoG1IGIWqtabUOLNPCVfgNY8rZTzp4py+Yk7B6nL9pp3T6f3O3KLrQJ6OYlqLdGUTaHuKZYwcjHdfEUOEh4aGiW4Vi4acnZeNkGBlb5Ror5mbmVqLrISgfq9zcqO4uxmup5enuKChk56RRHqKnbmktMnDvxI1kZRbpnmZccXTittXaUtB2gzY3k/U0ULg5EXs5zjo6jvk7T7q4TGz8+T28eka+/zx9RAAA7';
            // console.log(imgurl);return false;
			
			var url = "{{ route('test.camera.testcamera') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url,{
				id: 2,
				imgurl: imgurl
			})
			.then(function (response) {
                // console.log(response.data);return false;

				if (response.data) {
                    console.log('成功');
                    alert('成功');
				} else {
                    console.log('失败');
                    alert('失败');
				}
			})
			.catch(function (error) {
                console.log(error);
                alert('error');
			})
		},
		
		echarts_column () {
				// 基于准备好的dom，初始化echarts实例
				var myChart = echarts.init(document.getElementById('main'));

				// 指定图表的配置项和数据
			var option = {
				title: {
					text: 'ECharts 入门示例'
				},
				tooltip: {},
				legend: {
					data:['销量']
				},
				xAxis: {
					data: ["衬衫","羊毛衫","雪纺衫","裤子","高跟鞋","袜子"]
				},
				yAxis: {},
				series: [{
					name: '销量',
					type: 'bar',
					data: [5, 20, 36, 10, 10, 20]
				}]
			};

			// 使用刚指定的配置项和数据显示图表。
			myChart.setOption(option);
		},

		echarts_pie() {
			var myChart = echarts.init(document.getElementById('main'));

			var data = [{
				name: 'Apples',
				value: 70
			}, {
				name: 'Strawberries',
				value: 68
			}, {
				name: 'Bananas',
				value: 48
			}, {
				name: 'Oranges',
				value: 40
			}, {
				name: 'Pears',
				value: 32
			}, {
				name: 'Pineapples',
				value: 27
			}, {
				name: 'Grapes',
				value: 18
			}];

			option = {
				title: [{
					text: 'Pie label alignTo'
				}, {
					subtext: '▲ 按人员',
					left: '16.67%',
					top: '75%',
					textAlign: 'center'
				}, {
					subtext: '▲ 按类别',
					left: '50%',
					top: '75%',
					textAlign: 'center'
				}, {
					subtext: '▲ 按部门',
					left: '83.33%',
					top: '75%',
					textAlign: 'center'
				}],
				series: [{
					type: 'pie',
					radius: '25%',
					center: ['50%', '50%'],
					data: data,
					animation: false,
					label: {
						position: 'outer',
						alignTo: 'none',
						bleedMargin: 5
					},
					left: 0,
					right: '66.6667%',
					top: 0,
					bottom: 0
				}, {
					type: 'pie',
					radius: '25%',
					center: ['50%', '50%'],
					data: data,
					animation: false,
					label: {
						position: 'outer',
						alignTo: 'labelLine',
						bleedMargin: 5
					},
					left: '33.3333%',
					right: '33.3333%',
					top: 0,
					bottom: 0
				}, {
					type: 'pie',
					radius: '25%',
					center: ['50%', '50%'],
					data: data,
					animation: false,
					label: {
						position: 'outer',
						alignTo: 'edge',
						margin: 20
					},
					left: '66.6667%',
					right: 0,
					top: 0,
					bottom: 0
				}]
			};

			myChart.setOption(option);

		},



	},
	mounted: function () {
		// this.echarts_column();
		this.echarts_pie();
	}
})
</script>
</html>