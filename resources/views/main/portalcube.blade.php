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
            text: '<i class="cubeic-edit"></i> 处理加班',
            action: 'gotoTodo'
            },
            {
            text: '<i class="cubeic-red-packet"></i> 查看归档',
            action: 'gotoArchived'
            },
            {
            text: '<i class="cubeic-share"></i> 注销用户',
            action: 'gotoLogoff'
            }
        ],












	},
	methods: {



        // toolbar - start
        gotoApplicant(item) {
            this.$createToast({
                type: 'correct',
                txt: 'clicked ' + item.text,
                time: 1000
            }).show()
        },

        gotoTodo() {
            console.log('gotoTodo');
        },

        gotoArchived() {
            console.log('gotoArchived');
            alert();
        },

        gotoLogoff() {
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



























        



		



	},
	mounted: function () {
        this.userinfo();
	}
})
</script>
@endsection