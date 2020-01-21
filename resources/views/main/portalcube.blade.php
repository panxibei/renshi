@extends('renshi.layouts.mainbase_cube')

@section('my_title')
Renshi(Portal) - 
@parent
@endsection

@section('my_style')
<style>
.title-jiaban-applicant {
	position: relative;
	height: 44px;
	line-height: 44px;
	text-align: center;
	background-color: #edf0f4;
	box-shadow: 0 1px 6px #ccc;
	-webkit-backface-visibility: hidden;
	backface-visibility: hidden;
	z-index: 5;
}
</style>
@endsection

@section('my_js')
<script type="text/javascript">
</script>
@endsection

@section('my_body')
@parent

<cube-toolbar :actions="actions_toolbar" @click="clickHandler_toolbar"></cube-toolbar>

<header class="title-jiaban-applicant">
<h1>当前登录用户</h1>
</header>
<br>

<cube-form>
  <cube-form-group>
    <cube-form-item :field="fields[0]">
        <cube-input v-model.lazy="jiaban_info_uid" placeholder="" readonly></cube-input>
    </cube-form-item>
    <cube-form-item :field="fields[1]">
        <cube-input v-model.lazy="jiaban_info_applicant" placeholder="" readonly></cube-input>
    </cube-form-item>
    <cube-form-item :field="fields[2]">
        <cube-input v-model.lazy="jiaban_info_department" placeholder="" readonly></cube-input>
    </cube-form-item>
  </cube-form-group>
</cube-form>

<br>
<hr>
<br>
<cube-form>
<cube-form-group>
<cube-form-item :field="chart_fields[0]">
<cube-select v-model="select_value" title="选择年份" :options="select_options" placeholder="查看图表" :autoPop="false" :disabled="false" @change="select_change"></cube-select>
</cube-form-item>
</cube-form-group>
</cube-form>

<br>
<div id="chart1" style="width:auto;height:400px;"></div>

<br>

@endsection

@section('my_footer')
<hr>
@parent
@endsection

@section('my_js_others')
@parent
<script>
var vm_app = new Vue({
	el: '#app',
	data: {

        jiaban_info_uid: '',
        jiaban_info_applicant: '',
        jiaban_info_department: '',


        fields: [
            { //0
                // type: 'select',
                // modelKey: 'jiaban_info_uid',
                label: '工号',
                rules: {
                    required: false
                }
            },
            { //1
                // type: 'input',
                // modelKey: 'jiaban_info_applicant',
                label: '姓名',
                rules: {
                    required: false
                },
                // validating when blur
                // trigger: 'blur'
            },
            { //2
                // type: 'input',
                // modelKey: 'jiaban_info_department',
                label: '部门',
                rules: {
                    required: false
                },
            },




        ],


        actions_toolbar: [
            {
            text: '<i class="cubeic-person"></i> 申请加班',
            action: 'gotoApplicant'
            },
            {
            text: '<i class="cubeic-calendar"></i> 查看加班',
            action: 'gotoJiabanList'
            },
            {
            text: '<i class="cubeic-share"></i> 注销用户',
            action: 'gotoLogoff'
            }
        ],

        select_options: [2013, 2014, 2015, 2016, 2017, 2018, 2019, 2020],
        select_value: 2019,


        chart_fields: [
            { //0
                label: '查看图表',
                rules: {
                    required: false
                }
            },
        ],

		chart1_data: [{
			name: 'Apples',
			value: 70
		}, {
			name: 'Strawberries',
			value: 68
		}, {
			name: 'Bananas',
			value: 48
		}],







	},
	methods: {



        // toolbar - start
        gotoApplicant(item) {
            var url = "{{ route('renshi.jiaban.applicantcube') }}";
            window.location.href = url;
        },

        gotoJiabanList() {
            var url = "{{ route('renshi.jiaban.applicantcube.list') }}";
            window.location.href = url;
        },

        gotoLogoff() {
            this.$createToast({
                type: 'correct',
                txt: '正在注销...',
                time: 1000
            }).show()
            window.setTimeout(function(){
                var url = "{{ route('main.logout') }}";
                window.location.href = url;
            }, 1000);
        },

        clickHandler_toolbar(item) {
            if (item.action) {
                this[item.action](item)
            }
        },
        // toolbar - end


        userinfo() {
            var _this = this;
            var url = "{{ route('portalcubeuser') }}";
            axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
            axios.get(url,{
                params: {
                }
            })
            .then(function (response) {

				if (response.data['jwt'] == 'logout') {
                    _this.$createToast({
                        type: 'warn',
                        txt: '会话超时！正在注销...',
                        time: 1000
                    }).show()
                    window.setTimeout(function(){
                        var url = "{{ route('main.logout') }}";
                        window.location.href = url;
                    }, 1000);
					return false;
				}

                if (response.data) {
                    _this.jiaban_info_uid = response.data.uid;
                    _this.jiaban_info_applicant = response.data.displayname;
                    _this.jiaban_info_department = response.data.department;
                } else {
                }
            })
            .catch(function (error) {
                this.$createToast({
                    type: 'error',
                    txt: '获取登录用户信息失败！',
                    time: 1000
                }).show()
            })

        },

        select_change(value, index, text) {
            var _this = this;
            console.log('change', value, index, text)

            var url = "{{ route('renshi.jiaban.applicantcube.jiabangetsanalytics') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					// perPage: _this.page_size,
					// page: page,
					// queryfilter_uid: queryfilter_uid,
					// queryfilter_applicant: queryfilter_applicant,
					// queryfilter_category: queryfilter_category,
					// queryfilter_created_at: queryfilter_created_at,
				}
			})
			.then(function (response) {
				console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}

				if (response.data) {
					
					// _this.page_current = response.data.res_paginate.current_page;
					// _this.page_total = response.data.res_paginate.total;
					// _this.page_last = response.data.res_paginate.last_page;
					// _this.tabledata = response.data.res_paginate.data;

                    
                    _this.chart1_data = response.data;
            
                    _this.chart1();
					
				}
			})
			.catch(function (error) {
				_this.error(false, 'Error', error);
			})









            

        },

		// chart1 pie
		chart1() {
			var myChart = echarts.init(document.getElementById('chart1'));

			var data = this.chart1_data;

			option = {
				title: [{
					text: ''
				}, {
					subtext: '▲ 按类别',
					left: '50%',
					top: '50%',
					textAlign: 'center'
				}],
				tooltip: {
					trigger: 'item',
					formatter: '{c}小时 ({d})%'
				},
				series: [{
					type: 'pie',
					radius: '40%',
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
					bottom: 200
				}]
			};

			myChart.setOption(option);

		},























        



		



	},
	mounted: function () {
        this.userinfo();
	}
})
</script>
@endsection