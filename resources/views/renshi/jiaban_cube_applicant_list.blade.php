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
  height: 200px;
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

<div class="scroll-list-wrap">
  <cube-scroll
    ref="scroll"
    :data="data_scroll"
    :options="options_scroll"
    @pulling-down="onPullingDown"
    @pulling-up="onPullingUp">
  </cube-scroll>
</div>

<br>
abc


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

        data_scroll: [
            '😀 😁 😂 🤣 😃 😄 ',
            '🙂 🤗 🤩 🤔 🤨 😐 ',
            '👆🏻 scroll up/down 👇🏻 ',
            '😔 😕 🙃 🤑 😲 ☹️ ',
            '🐣 🐣 🐣 🐣 🐣 🐣 ',
            '👆🏻 scroll up/down 👇🏻 ',
            '🐥 🐥 🐥 🐥 🐥 🐥 ',
            '🤓 🤓 🤓 🤓 🤓 🤓 ',
            '👆🏻 scroll up/down 👇🏻 ',
            '🦔 🦔 🦔 🦔 🦔 🦔 ',
            '🙈 🙈 🙈 🙈 🙈 🙈 ',
            '👆🏻 scroll up/down 👇🏻 ',
            '🚖 🚖 🚖 🚖 🚖 🚖 ',
            '✌🏻 ✌🏻 ✌🏻 ✌🏻 ✌🏻 ✌🏻 ',

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









        jiaban_add_uid: '',
        jiaban_add_applicant: '',
        jiaban_add_department: '',
        jiaban_add_startdate: '',
        jiaban_add_enddate: '',
        jiaban_add_duration: '',
        jiaban_add_duration_options: [
            0.5, 1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5, 5.5,
            6, 6.5, 7, 7.5, 8, 8.5, 9, 9.5, 10, 10.5, 11, 11.5,
            12, 12.5, 13, 13.5, 14, 14.5, 15, 15.5, 16, 16.5, 17, 17.5,
            18, 18.5, 19, 19.5, 20, 20.5, 21, 21.5, 22, 22.5, 23, 23.5, 24
        ],
        jiaban_add_category: '',
        jiaban_add_category_options: ['平时加班', '双休加班', '节假日加班'],
        jiaban_add_reason: '',
        jiaban_add_remark: '',





        actions_toolbar: [
            {
            text: '<i class="cubeic-home"></i> 返回首页',
            action: 'gotoPortal'
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






        







        













    },
	methods: {

        onPullingDown() {
            // 下拉刷新数据
            setTimeout(() => {
            if (Math.random() > 0.5) {
                // 如果有新数据
                // this.data_scroll.unshift(_foods[1])

                                // 如果有新数据
                                let _foods = [
                    '🙈 🙈 🙈 🙈 🙈 🙈',
                    '🙈 🙈 🙈 🙈 🙈 🙈',
                    '🙈 🙈 🙈 🙈 🙈 🙈',
                ]
                let newPage = _foods.slice(0, 5)
                // this.data_scroll = this.data_scroll.concat(newPage)
                this.data_scroll = _foods
            } else {
                // 如果没有新数据
                this.$refs.scroll.forceUpdate()
            }
            }, 1000)
        },
        onPullingUp() {
            // 上拉追加数据
            setTimeout(() => {
            if (Math.random() > 0.5) {
                // 如果有新数据
                let _foods = [
                    '🤓 🤓 🤓 🤓 🤓 🤓',
                    '🤓 🤓 🤓 🤓 🤓 🤓',
                    '🤓 🤓 🤓 🤓 🤓 🤓',
                ]
                let newPage = _foods.slice(0, 5)
                this.data_scroll = this.data_scroll.concat(newPage)
            } else {
                // 如果没有新数据
                this.$refs.scroll.forceUpdate()
            }
            }, 1000)
        },













        // showDateTimePicker
        showDateTimePicker_startdate() {
            if (!this.dateTimePicker_startdate) {
                this.dateTimePicker_startdate = this.$createDatePicker({
                title: '选择开始时间',
                min: new Date(2018, 12, 1, 0, 0, 0),
                // max: new Date(2020, 9, 20, 20, 59, 59),
                max: new Date(this.jiaban_add_enddate || '2099-12-31 23:59:59'),
                value: new Date(),
                columnCount: 6,
                onSelect: this.selectHandle_startdate,
                onCancel: this.cancelHandle_startdate
                })
            }

            this.dateTimePicker_startdate.show()
        },
        selectHandle_startdate(date, selectedVal, selectedText) {
            this.jiaban_add_startdate = date.Format("yyyy-MM-dd hh:mm:ss")
            // this.$createDialog({
            //     type: 'warn',
            //     content: `Selected Item: <br/> - date: ${date} <br/> - value: ${selectedVal.join(', ')} <br/> - text: ${selectedText.join(' ')}`,
            //     icon: 'cubeic-alert'
            // }).show()
        },
        cancelHandle_startdate() {
            // this.$createToast({
            //     type: 'correct',
            //     txt: 'Picker canceled',
            //     time: 1000
            // }).show()
        },

        // showDateTimePicker
        showDateTimePicker_enddate() {
            if (!this.dateTimePicker_enddate) {
                this.dateTimePicker_enddate = this.$createDatePicker({
                title: '选择结束时间',
                // min: new Date(2008, 7, 8, 8, 0, 0),
                min: new Date(this.jiaban_add_startdate || '2019-01-01 00:00:00'),
                max: new Date(2099, 12, 31, 23, 59, 59),
                value: new Date(),
                columnCount: 6,
                onSelect: this.selectHandle_enddate,
                onCancel: this.cancelHandle_enddate
                })
            }

            this.dateTimePicker_enddate.show()
        },
        selectHandle_enddate(date, selectedVal, selectedText) {
            this.jiaban_add_enddate = date.Format("yyyy-MM-dd hh:mm:ss")
            // this.$createDialog({
            //     type: 'warn',
            //     content: `Selected Item: <br/> - date: ${date} <br/> - value: ${selectedVal.join(', ')} <br/> - text: ${selectedText.join(' ')}`,
            //     icon: 'cubeic-alert'
            // }).show()
        },
        cancelHandle_enddate() {
            // this.$createToast({
            //     type: 'correct',
            //     txt: 'Picker canceled',
            //     time: 1000
            // }).show()
        },


        // form
        submitHandler(e) {
            e.preventDefault()
            // console.log('submit', e)
            // alert('submit');
            var _this = this;

            var uid = _this.jiaban_add_uid;
            var applicant = _this.jiaban_add_applicant;
            var department = _this.jiaban_add_department;
            var startdate = _this.jiaban_add_startdate;
            var enddate = _this.jiaban_add_enddate;
            var duration = _this.jiaban_add_duration;
            var category = _this.jiaban_add_category;
            var reason = _this.jiaban_add_reason;
            var remark = _this.jiaban_add_remark;

            if (uid == '' || applicant == '' || department == '' || startdate == '' || enddate == '' || category == ''  || duration == '' || reason == ''
            || uid == undefined || applicant == undefined || department == undefined || startdate == undefined || enddate == undefined || category == undefined  || duration == undefined || reason == undefined) {
                // _this.warning(false, '警告', '输入内容为空或不正确！');
                const toast = _this.$createToast({
                    txt: '输入内容为空或不正确！',
                    type: 'warn'
                })
                toast.show()
				return false;
			}

			var url = "{{ route('renshi.jiaban.applicantcube.applicantcubecreate') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
                uid: uid,
                applicant: applicant,
                department: department,
                startdate: startdate,
                enddate: enddate,
                duration: duration,
                category: category,
                reason: reason,
                remark: remark,
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;
				
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.onclear_applicant();
					_this.jiabangetsapplicant(_this.page_current, _this.page_last);
                    // _this.success(false, '成功', '提交成功！');
                    const toast = _this.$createToast({
                        txt: '提交成功！',
                        type: 'correct'
                    })
                    toast.show()
				} else {
                    // _this.error(false, '失败', '提交失败！');
                    const toast = _this.$createToast({
                        txt: '提交失败！',
                        type: 'error'
                    })
                    toast.show()
				}
			})
			.catch(function (error) {
                // _this.error(false, '错误', '提交失败！');
                const toast = _this.$createToast({
                    txt: '提交失败！',
                    type: 'error'
                })
                toast.show()
			})

        },
        validateHandler(result) {
            // this.validity = result.validity
            // this.valid = result.valid
            // console.log('validity', result.validity, result.valid, result.dirty, result.firstInvalidFieldIndex)
        },
        resetHandler(e) {
            // console.log('reset', e)
            this.jiaban_add_uid = '';
            this.jiaban_add_applicant = '';
            this.jiaban_add_department = '';
            this.jiaban_add_startdate = '';
            this.jiaban_add_enddate = '';
            this.jiaban_add_duration = '';
            this.jiaban_add_category = '';
            this.jiaban_add_reason = '';
            this.jiaban_add_remark = '';
        },

        // toolbar - start
        gotoPortal(item) {
            var url = "{{ route('portalcube') }}";
            window.location.href = url;
        },

        gotoJiabanList() {
            console.log('gotoTodo');
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

























        handleClick() {
			console.log('aaaaaaa');
			this.$dialog.alert({
                title: '标题',
                message: '弹窗内容'
                }).then(() => {
                // on close
            });

        },


        // showToast
		showToastTime() {
            const toast = this.$createToast({
                time: 2000,
                txt: 'Toast time 2s'
            })
            toast.show()
        },

        showToastTxtOnly() {
            this.toast = this.$createToast({
                txt: 'Plain txt here!!!!!!!!!!!!!!!',
                type: 'txt'
            })
            this.toast.show()
        },




        



		



    },
	mounted: function () {

	}
})
</script>
@endsection