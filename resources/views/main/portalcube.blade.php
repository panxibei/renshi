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
            <cube-input v-model.lazy="chart1_date" @focus="showDateTimePicker_chart1date" placeholder="选择日期"></cube-input>
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

        chart1_date: '',


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
        
        chart1_subtext: '',







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

        // 获取某个月份的天数 例：getDays(2018-12)
        getDays(yearmonth) {
            var arr = yearmonth.split('-');
            // var d = new Date(year, month, 0);
            var d = new Date(arr[0], arr[1], 0); //初始化月份的第0天，由于JS中day的范围为1~31中的值，所以当设为0时，会向前一天，也即表示上个月的最后一天。
            return d.getDate();
        },



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


        // 加载chart1数据
        chart1_change(value) {
            var _this = this;

            // var days = getDaysOfMonth(this.chart1_date.substr(0, 4), this.chart1_date.substr(5, 2));
            var days = _this.getDays(this.chart1_date);

            var queryfilter_created_at = [
                new Date(this.chart1_date).Format("yyyy-MM-1 00:00:00"),
                new Date(this.chart1_date).Format("yyyy-MM-" + days + " 23:59:59")
            ]
            // console.log(queryfilter_created_at);return false;

            var url = "{{ route('renshi.jiaban.applicantcube.jiabangetsanalytics') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					// perPage: _this.page_size,
					// page: page,
					// queryfilter_uid: queryfilter_uid,
					// queryfilter_applicant: queryfilter_applicant,
					// queryfilter_category: queryfilter_category,
					queryfilter_created_at: queryfilter_created_at,
				}
			})
			.then(function (response) {
				// console.log(response.data);
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
                    
                }	

                _this.chart1_subtext = response.data.length == 0 ? _this.chart1_date + ' - 暂无数据' : _this.chart1_date;
                _this.chart1();
			})
			.catch(function (error) {
				_this.error(false, 'Error', error);
			})





            

        },

		// chart1 pie
		chart1() {
			var myChart = echarts.init(document.getElementById('chart1'));

            var data = this.chart1_data;
            var subtext = this.chart1_subtext;

            option = {
                title: {
                    text: '当月加班时间数及占比',
                    subtext: subtext,
                    left: 'center'
                },
                tooltip: {
                    trigger: 'item',
                    // formatter: '{a} <br/>{b} : {c}小时 ({d}%)'
                    formatter: '{b} : {c}小时 ({d}%)'
                },
                series: [
                    {
                        name: '加班类别',
                        type: 'pie',
                        radius: '35%',
                        center: ['50%', '40%'],
                        data: data,
                        // [
                        //     {value: 335, name: '直接访问'},
                        //     {value: 310, name: '邮件营销'},
                        //     {value: 234, name: '联盟广告'},
                        //     {value: 135, name: '视频广告'},
                        //     {value: 1548, name: '搜索引擎'}
                        // ],
                        emphasis: {
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };

			myChart.setOption(option);

		},


        // showDateTimePicker
        showDateTimePicker_chart1date() {
            if (!this.dateTimePicker_chart1date) {
                this.dateTimePicker_chart1date = this.$createDatePicker({
                title: '选择日期',
                // min: new Date(2008, 7, 8, 8, 0, 0),
                min: new Date(this.chart1_date || '2019-01-01 00:00:00'),
                max: new Date(2099, 12, 31, 23, 59, 59),
                value: new Date(),
                columnCount: 2,
                onSelect: this.selectHandle_chart1date,
                onCancel: this.cancelHandle_chart1date
                })
            }

            this.dateTimePicker_chart1date.show()
        },
        selectHandle_chart1date(date, selectedVal, selectedText) {
            // this.chart1_date = date.Format("yyyy-MM-dd hh:mm:ss")
            this.chart1_date = date.Format("yyyy-MM")
            // this.$createDialog({
            //     type: 'warn',
            //     content: `Selected Item: <br/> - date: ${date} <br/> - value: ${selectedVal.join(', ')} <br/> - text: ${selectedText.join(' ')}`,
            //     icon: 'cubeic-alert'
            // }).show()
            this.chart1_change(this.chart1_date)
        },
        cancelHandle_chart1date() {
            // this.$createToast({
            //     type: 'correct',
            //     txt: 'Picker canceled',
            //     time: 1000
            // }).show()
        },



        



		



	},
	mounted: function () {
        this.userinfo();
	}
})
</script>
@endsection