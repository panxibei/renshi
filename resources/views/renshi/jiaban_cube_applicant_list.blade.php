@extends('renshi.layouts.mainbase_cube')

@section('my_title')
Renshi(Jiaban List) - 
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
.scroll-list-wrap {
  /* height: 200px; */
  height: 40rem;
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
<h1>列表</h1>
</header>
<br>

<!-- <div class="scroll-list-wrap"> -->
<div :style="class_scroll">
  <cube-scroll
    ref="scroll"
    :data="data_scroll"
    :options="options_scroll"
    @pulling-down="onPullingDown"
    @pulling-up="onPullingUp">
  
    <ul>
        <li v-for="item in data_scroll">
        <div style="{display: flex;padding: 8px 14px 8px 20px;border-bottom: 1px solid rgba(7,17,27,.1);}">
            <div style="{font-weight: 700;line-height: 24px;}">
                <span style="{height:14px;line-height:14px;font-size:14px;color:#07111b;}">@{{item.category}}</span>
                &nbsp;&nbsp;
                <span style="{height:12px;line-height:12px;font-size:12px;color:#07111b;}">@{{item.duration}}小时</span>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <span style="{height:12px;line-height:12px;font-size:12px;color:#07111b;}">[@{{item.created_at}}]</span>
            </div>
            <div style="{font-weight: 700;line-height: 10px;}">
                <span style="{line-height:10px;font-size:10px;color:#93999f;}">时间：@{{item.datetimerange}}</span>
            </div>
            <div style="{font-weight: 700;line-height: 24px;}">
                <span style="{margin-right: 8px;font-size: 12px;color: #f01414;}">状态：@{{item.status == 99 ? '已结案' : '处理中'}}</span>
                &nbsp;&nbsp;
                <span style="{margin-right: 8px;font-size: 12px;color: #f01414;}">当前审核：@{{item.auditor}}</span>
            </div>
        </div>
        </li>
    </ul>
  
  </cube-scroll>
</div>

<br>
@endsection

@section('my_footer')
@parent
@endsection

@section('my_js_others')
@parent
<script>
var vm_app = new Vue({
	el: '#app',
	data: {

        //分页
		page_current: 1,
		page_total: 1, // 记录总数，非总页数
		page_size: 5,
		page_last: 1,

        class_scroll: {
            'height': '640px'
        },

        data_scroll: [
            // {
            //     'created_at': '2019-01-01 12:12:12',
            //     'category': '大家好',
            //     'duration': 1,
            //     'datetimerange': '2019-01-01 12:12:12 - 2019-01-01 22:22:22',
            //     'status': 1,
            //     'auditor': 'admin'
            // },
            // {
            //     'created_at': '2019-01-01 12:12:12',
            //     'category': 'a2',
            //     'duration': 1.5,
            //     'datetimerange': '2019-01-01 12:12:12 - 2019-01-01 22:22:22',
            //     'status': 1,
            //     'auditor': 'admin'
            // },
            // {
            //     'created_at': '2019-01-01 12:12:12',
            //     'category': 'a3',
            //     'duration': 2.5,
            //     'datetimerange': '2019-01-01 12:12:12 - 2019-01-01 22:22:22',
            //     'status': 1,
            //     'auditor': 'admin'
            // },
            // {
            //     'created_at': '2019-01-01 12:12:12',
            //     'category': 'a1',
            //     'duration': 1,
            //     'datetimerange': '2019-01-01 12:12:12 - 2019-01-01 22:22:22',
            //     'status': 1,
            //     'auditor': 'admin'
            // },
            // {
            //     'created_at': '2019-01-01 12:12:12',
            //     'category': 'a2',
            //     'duration': 1.5,
            //     'datetimerange': '2019-01-01 12:12:12 - 2019-01-01 22:22:22',
            //     'status': 1,
            //     'auditor': 'admin'
            // },
            // {
            //     'created_at': '2019-01-01 12:12:12',
            //     'category': 'a3',
            //     'duration': 2.5,
            //     'datetimerange': '2019-01-01 12:12:12 - 2019-01-01 22:22:22',
            //     'status': 1,
            //     'auditor': 'admin'
            // },
            // {
            //     'created_at': '2019-01-01 12:12:12',
            //     'category': 'a1',
            //     'duration': 1,
            //     'datetimerange': '2019-01-01 12:12:12 - 2019-01-01 22:22:22',
            //     'status': 1,
            //     'auditor': 'admin'
            // },
            // {
            //     'created_at': '2019-01-01 12:12:12',
            //     'category': 'a2',
            //     'duration': 1.5,
            //     'datetimerange': '2019-01-01 12:12:12 - 2019-01-01 22:22:22',
            //     'status': 1,
            //     'auditor': 'admin'
            // },
            // {
            //     'created_at': '2019-01-01 12:12:12',
            //     'category': 'a3',
            //     'duration': 2.5,
            //     'datetimerange': '2019-01-01 12:12:12 - 2019-01-01 22:22:22',
            //     'status': 1,
            //     'auditor': 'admin'
            // },

        ],

        options_scroll: {
            pullDownRefresh: {
                threshold: 90,
                stop: 40,
                txt: '刷新成功！'
            },
            pullUpLoad: {
                threshold: 0,
                txt: {
                    more: '上拉加载更多...',
                    noMore: '没有更多数据...'
                }
            }
        },


        actions_toolbar: [
            {
            text: '<i class="cubeic-home"></i> 返回首页',
            action: 'gotoPortal'
            },
            {
            text: '<i class="cubeic-person"></i> 申请加班',
            action: 'gotoApplicant'
            },
            {
            text: '<i class="cubeic-share"></i> 注销用户',
            action: 'gotoLogoff'
            }
        ],






        







        













    },
	methods: {

        // 下拉刷新数据
        onPullingDown() {
            var _this = this;

            _this.page_current = 1;

            var url = "{{ route('renshi.jiaban.applicantcube.applicantcubegets') }}";
            axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
            axios.get(url, {
                params: {
                    perPage: _this.page_size,
                    page: _this.page_current,
                }
            })
            .then(function (response) {
                // console.log(response.data);
                // console.log(response.data.data.length);
                // return false;
                
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
                
                if (response.data.data.length) {

                    _this.page_last = response.data.last_page;
                    let orignal_data = response.data.data;
                    let result_data = [];

                    orignal_data.map(function (v,i) {

                        let application = JSON.parse(v.application);

                        result_data.push({
                            'created_at': v.created_at,
                            'category': application[0].category,
                            'duration': application[0].duration,
                            'datetimerange': application[0].datetimerange,
                            'status': v.status,
                            'auditor': v.auditor
                        });

                    });

                    _this.data_scroll = result_data;
                } else {
                    // 如果没有新数据
                    _this.$refs.scroll.forceUpdate()
                }
            })
            .catch(function (error) {
                _this.$refs.scroll.forceUpdate()
            })

            // setTimeout(() => {
            // if (Math.random() > 0.5) {
            //     // 如果有新数据
            //     // this.data_scroll.unshift(_foods[1])

            //                     // 如果有新数据
            //                     let _foods = [
            //         '🙈 🙈 🙈 🙈 🙈 🙈',
            //         '🙈 🙈 🙈 🙈 🙈 🙈',
            //         '🙈 🙈 🙈 🙈 🙈 🙈',
            //     ]
            //     // let newPage = _foods.slice(0, 5)
            //     // // this.data_scroll = this.data_scroll.concat(newPage)
            //     // this.data_scroll = _foods
            //     this.data_scroll = _foods
            // } else {
            //     // 如果没有新数据
            //     this.$refs.scroll.forceUpdate()
            // }
            // }, 1000)
        },

        // 上拉追加数据
        onPullingUp() {
            var _this = this;

            _this.page_current++;

            var url = "{{ route('renshi.jiaban.applicantcube.applicantcubegets') }}";
            axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
            axios.get(url, {
                params: {
                    perPage: _this.page_size,
                    page: _this.page_current,
                }
            })
            .then(function (response) {
                // console.log(_this.page_current);
                // console.log(response.data.data);
                // console.log(response.data.data==false);
                // return false;
                
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
                
                if (response.data.data.length) {

                    _this.page_last = response.data.last_page;
                    let orignal_data = response.data.data;
                    let result_data = [];

                    orignal_data.map(function (v,i) {

                        let application = JSON.parse(v.application);

                        result_data.push({
                            'created_at': v.created_at,
                            'category': application[0].category,
                            'duration': application[0].duration,
                            'datetimerange': application[0].datetimerange,
                            'status': v.status,
                            'auditor': v.auditor
                        });

                    });

                    _this.data_scroll.push(result_data);
                } else {
                    // 如果没有新数据
                    _this.$refs.scroll.forceUpdate()
                }
            })
            .catch(function (error) {
                _this.$refs.scroll.forceUpdate()
            })

            // setTimeout(() => {
                // if (Math.random() > 0.5) {
                //     // 如果有新数据
                //     let _foods = [
                //         '🤓 🤓 🤓 🤓 🤓 🤓',
                //         '🤓 🤓 🤓 🤓 🤓 🤓',
                //         '🤓 🤓 🤓 🤓 🤓 🤓',
                //     ]
                //     let newPage = _foods.slice(0, 5)
                //     this.data_scroll = this.data_scroll.concat(newPage)
                // } else {
                //     // 如果没有新数据
                //     this.$refs.scroll.forceUpdate()
                // }
            // }, 1000)
        },


        jiabancubeGetsApplicant(page, last_page) {
            var _this = this;

            if (page > last_page) {
				page = last_page;
			} else if (page < 1) {
				page = 1;
			}

			var url = "{{ route('renshi.jiaban.applicantcube.applicantcubegets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url, {
                params: {
                    perPage: _this.page_size,
                    page: page,
                }
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;
				
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
                    // console.log(response.data);
                    // return false;

                    this.page_last = response.data.last_page;
                    var orignal_data = response.data.data;
                    var result_data = [];

                    // console.log(orignal_data[0]);
                    // return false;

                    orignal_data.map(function (v,i) {
                        // console.log(v.application);
                        // return false;

                        let application = JSON.parse(v.application);

                        result_data.push({
                            'created_at': v.created_at,
                            'category': application[0].category,
                            'duration': application[0].duration,
                            'datetimerange': application[0].datetimerange,
                            'status': v.status,
                            'auditor': v.auditor
                        });

                    });

                    _this.data_scroll = result_data;

                    // console.log(result_data);
                    // console.log(orignal_data);
                    // console.log(orignal_data[0]['application']);
                    // return false;

                    // const toast = _this.$createToast({
                    //     txt: '提交成功！',
                    //     type: 'correct'
                    // })
                    // toast.show()
				} else {
                    // const toast = _this.$createToast({
                    //     txt: '提交失败！',
                    //     type: 'error'
                    // })
                    // toast.show()
				}
			})
			.catch(function (error) {
                // const toast = _this.$createToast({
                //     txt: '提交失败！',
                //     type: 'error'
                // })
                // toast.show()
			})


        },

        // toolbar - start
        gotoPortal(item) {
            var url = "{{ route('portalcube') }}";
            window.location.href = url;
        },

        gotoApplicant(item) {
            var url = "{{ route('renshi.jiaban.applicantcube') }}";
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





    },
	mounted: function () {
        let window_screen_height = window.screen.height - 300;
        this.class_scroll = {
            'height': window_screen_height + 'px'
        };
        
        this.jiabancubeGetsApplicant(1, 1);

	}
})
</script>
@endsection