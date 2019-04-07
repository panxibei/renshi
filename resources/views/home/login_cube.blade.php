@extends('home.layouts.homebase_cube')

@section('my_title')
Login - 
@parent
@endsection

@section('my_style')
@endsection

@section('my_logo_and_title')
@parent
@endsection

@section('my_js')
<script type="text/javascript">
</script>
@endsection

@section('my_body')
@parent


<cube-form :model="model" @validate="validateHandler" @submit="submitHandler" @reset="resetHandler">
  <cube-form-group>
    <cube-form-item :field="fields[0]">
        <cube-input v-model.lazy="username" placeholder="输入用户名"></cube-input>
    </cube-form-item>
    <cube-form-item :field="fields[1]">
        <cube-input v-model.lazy="password" type="password" :eye="{open:false,reverse:false}" placeholder="输入密码"></cube-input>
    </cube-form-item>
  </cube-form-group>
  <cube-form-group>
    <br>
    <cube-button :primary="true" type="submit">登 录</cube-button>
    <br>
    <!-- <cube-button type="reset">清 除</cube-button> -->
  </cube-form-group>
</cube-form>



<br><br>

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

        username: '',
        password: '',

        model: {
            inputValue: '',
            pcaValue: [],
            dateValue: ''
        },
        fields: [
            { //0
                // type: 'select',
                // modelKey: 'jiaban_add_uid',
                label: '用户',
                rules: {
                    required: false
                },
                trigger: 'blur'
            },
            { //1
                // type: 'input',
                // modelKey: 'jiaban_add_applicant',
                label: '密码',
                rules: {
                    required: false
                },
                // validating when blur
                trigger: 'blur'
            },

        ],


	},
	methods: {

        // form
        submitHandler(e) {
            e.preventDefault()
            // console.log('submit', e)
            // alert('submit');
            var _this = this;

            var username = _this.username;
            var password = _this.password;

            if (username == '' || password == ''
            || username == undefined || password == undefined) {
                // _this.warning(false, '警告', '输入内容为空或不正确！');
                const toast = _this.$createToast({
                    txt: '请正确输入登录名和密码！',
                    type: 'warn'
                })
                toast.show()
				return false;
			}

			var url = "{{ route('logincube.checklogin') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
                name: username,
                password: password,
			})
			.then(function (response) {
				// console.log(response.data);
                // return false;
                
                if (response.data) {
                
                    if (response.data=='nosingleuser') {
                        const toast = _this.$createToast({
                            txt: '用户已在其他地方登录！ 请注销再试！',
                            type: 'warn'
                        })
                        toast.show()
                        return false;
                    }

                    const toast = _this.$createToast({
                            txt: '登录成功！ 正在跳转...',
                            type: 'correct'
                        })
                    toast.show()
                    window.setTimeout(function(){
                        var url = "{{ route('portalcube') }}";
                        window.location.href = url;
                    }, 1000);
                
                } else {
                    const toast = _this.$createToast({
                            txt: '登录失败！',
                            type: 'error'
                        })
                    toast.show()

                }
			})
			.catch(function (error) {
                const toast = _this.$createToast({
                    txt: '登录失败！',
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

	},
	mounted: function () {

	}
})
</script>
@endsection