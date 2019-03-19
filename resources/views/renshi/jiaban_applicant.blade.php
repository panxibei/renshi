@extends('renshi.layouts.mainbase')

@section('my_title')
Renshi(Jiaban) - 
@parent
@endsection

@section('my_style')
@endsection

@section('my_js')
<script type="text/javascript">
</script>
@endsection

@section('my_body')
@parent
<Divider orientation="left">Jiaban applicant</Divider>

<i-row :gutter="16">
    <i-col span="4">
        ↓ 批量录入&nbsp;&nbsp;
        <Input-number v-model.lazy="piliangluruxiang_applicant" @on-change="value=>piliangluru_applicant_generate(value)" :min="1" :max="20" size="small" style="width: 60px"></Input-number>
        &nbsp;项
    </i-col>
    <i-col span="20">
        &nbsp;&nbsp;<i-button @click="oncreate_applicant()" size="default" type="primary">提 交</i-button>
        &nbsp;&nbsp;<i-button @click="onclear_applicant()" size="default">清 除</i-button>
    </i-col>
</i-row>
    
    &nbsp;

    <span v-for="(item, index) in piliangluru_applicant">
    
    <i-row>
    <br>
        <!-- <i-col span="1">
            &nbsp;(@{{index+1}})
        </i-col> -->
        <i-col span="4">
            * 工号&nbsp;
            <i-select v-model.lazy="item.uid" filterable remote :remote-method="remoteMethod_applicant" :loading="applicant_loading" @on-change="value=>onchange_applicant(value)" clearable placeholder="输入后选择" size="small" style="width: 120px;">
                <i-option v-for="item in applicant_options" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
            </i-select>
        </i-col>
        <i-col span="3">
            * 姓名&nbsp;
            <i-input v-model.lazy="item.applicant" size="small" placeholder="例：张三" clearable style="width: 80px"></i-input>
        </i-col>
        <i-col span="3">
            部门&nbsp;
            <i-input v-model.lazy="item.department" readonly="true" size="small" placeholder="例：生产部" clearable style="width: 80px"></i-input>
        </i-col>
        <i-col span="4">
            * 类别&nbsp;
            <i-select v-model.lazy="item.category" size="small" style="width:120px" placeholder="选择加班类别">
                <i-option v-for="item in option_category" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
            </i-select>
        </i-col>
        <i-col span="7">
            * 时间&nbsp;
            <Date-picker v-model.lazy="item.datetime" :editable="false" type="datetimerange" format="yyyy-MM-dd HH:mm" size="small" placeholder="加班时间" style="width:250px"></Date-picker>
        </i-col>
        <i-col span="3">
            * 时长&nbsp;
            <Input-number v-model.lazy="item.duration" :editable="false" :min="0.5" :max="40" :step="0.5" size="small" placeholder="" clearable style="width: 60px"></Input-number>
        </i-col>
        
    </i-row>
    <br>
    </span>










    &nbsp;


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
		current_nav: '',
		current_subnav: '',
		
		sideractivename: '1-1',
		sideropennames: ['1'],
		
		// 批量录入applicant表
		piliangluru_applicant: [
			{
				uid: '',
				applicant: '',
				department: '',
				start_date: '',
				end_date: '',
				category: '',
				duration: ''
			},
		],

		// 批量录入项
		piliangluruxiang_applicant: 1,

		//加班类别
		option_category: [
			{value: '平时加班', label: '平时加班'},
			{value: '双休加班', label: '双休加班'},
			{value: '节假日加班', label: '节假日加班'}
		],

		// 选择角色查看编辑相应权限
		applicant_select: '',
		applicant_options: [],
		applicant_loading: false,












		
		//分页
		page_current: 1,
		page_total: 1, // 记录总数，非总页数
		page_size: {{ $config['PERPAGE_RECORDS_FOR_PERMISSION'] }},
		page_last: 1,
		
		// 创建
		modal_permission_add: false,
		permission_add_id: '',
		permission_add_name: '',
		permission_add_email: '',
		permission_add_password: '',
		
		// 编辑
		modal_jiaban_edit: false,
		modal_jiaban_pass_loading: false,
		modal_jiaban_deny_loading: false,
		jiaban_edit_id: '',
		jiaban_edit_uuid: '',
		jiaban_edit_agent: '',
		jiaban_edit_department_of_agent: '',
		jiaban_edit_applicant: '',
		jiaban_edit_department_of_applicant: '',
		jiaban_edit_category: '',
		jiaban_edit_start_date: '',
		jiaban_edit_end_date: '',
		jiaban_edit_duration: '',
		jiaban_edit_status: '',
		jiaban_edit_reason: '',
		jiaban_edit_remark: '',
		jiaban_edit_auditing: '',
		jiaban_edit_created_at: '',
		jiaban_edit_updated_at: '',
		
		// 删除
		delete_disabled: true,
		delete_disabled_sub: true,

		// tabs索引
		currenttabs: 0,
		
		// 查询过滤器
		queryfilter_name: '',
		
		// 查询过滤器下拉
		collapse_query: '',		
		
		// 选择角色查看编辑相应权限
		role_select: '',
		role_options: [],
		role_loading: false,
		boo_update: false,
		titlestransfer: ['待选', '已选'], // ['源列表', '目的列表']
		datatransfer: [],
		targetkeystransfer: [], // ['1', '2'] key
		
		// 选择权限查看哪些角色在使用
		permission2role_select: '',
		permission2role_options: [],
		permission2role_loading: false,
		permission2role_input: '',		
		
		// 测试用户是否有相应权限
		test_permission_select: '',
		test_permission_options: [],
		test_permission_loading: false,
		test_user_select: '',
		test_user_options: [],
		test_user_loading: false,
		
		
    },
	methods: {
		menuselect: function (name) {
			navmenuselect(name);
		},
		// 1.加载进度条
		loadingbarstart () {
			this.$Loading.start();
		},
		loadingbarfinish () {
			this.$Loading.finish();
		},
		loadingbarerror () {
			this.$Loading.error();
		},
		// 2.Notice 通知提醒
		info (nodesc, title, content) {
			this.$Notice.info({
				title: title,
				desc: nodesc ? '' : content
			});
		},
		success (nodesc, title, content) {
			this.$Notice.success({
				title: title,
				desc: nodesc ? '' : content
			});
		},
		warning (nodesc, title, content) {
			this.$Notice.warning({
				title: title,
				desc: nodesc ? '' : content
			});
		},
		error (nodesc, title, content) {
			this.$Notice.error({
				title: title,
				desc: nodesc ? '' : content
			});
		},

		alert_logout: function () {
			this.error(false, '会话超时', '会话超时，请重新登录！');
			window.setTimeout(function(){
				window.location.href = "{{ route('portal') }}";
			}, 2000);
			return false;
		},
		
		// 把laravel返回的结果转换成select能接受的格式
		json2selectvalue: function (json) {
			var arr = [];
			for (var key in json) {
				// alert(key);
				// alert(json[key]);
				// arr.push({ obj.['value'] = key, obj.['label'] = json[key] });
				arr.push({ value: key, label: json[key] });
			}
			return arr;
			// return arr.reverse();
		},
		
		// 穿梭框显示文本转换
		json2transfer: function (json) {
			var arr = [];
			for (var key in json) {
				arr.push({
					key: key,
					label: json[key],
					description: json[key],
					disabled: false
				});
			}
			return arr.reverse();
		},

		// 生成piliangluru_applicant
		piliangluru_applicant_generate: function (counts) {
			if (counts == undefined) counts = 1;
			var len = this.piliangluru_applicant.length;
			
			if (counts > len) {
				for (var i=0;i<counts-len;i++) {
					// this.piliangluru_applicant.push({value: 'piliangluru_applicant'+parseInt(len+i+1)});
					this.piliangluru_applicant.push(
						{
                            uid: '',
                            applicant: '',
                            department: '',
                            start_date: '',
                            end_date: '',
                            category: '',
                            duration: ''
                        }
					);
				}
			} else if (counts < len) {
				if (this.piliangluruxiang_applicant != '') {
					for (var i=counts;i<len;i++) {
						if (this.piliangluruxiang_applicant == this.piliangluru_applicant[i].value) {
							this.piliangluruxiang_applicant = '';
							break;
						}
					}
				}
				
				for (var i=0;i<len-counts;i++) {
					this.piliangluru_applicant.pop();
				}
			}
		},

		// oncreate_applicant
		oncreate_applicant: function () {
			var _this = this;

			var booFlagOk = true;
			_this.piliangluru_applicant.map(function (v,i) {
				// applicant: '',
				// department: '',
				// start_date: '',
				// end_date: '',
				// category: '',
				// duration: ''
				
				if (v.jizhongming == '' || v.pinfan == '' || v.pinming == ''  || v.xuqiushuliang == '' || v.leibie == ''
					|| v.jizhongming == undefined || v.pinfan == undefined || v.pinming == undefined || v.xuqiushuliang == undefined || v.leibie == undefined) {
					booFlagOk = false;
				}
			});
			
			if (booFlagOk == false) {
				_this.warning(false, '警告', '输入内容为空或不正确2！');
				return false;
			}
			
			var piliangluru_applicant = _this.piliangluru_applicant;
			
			var url = "{{ route('bpjg.zrcfx.relationcreate') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				piliangluru: piliangluru_applicant
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
					_this.success(false, '成功', '记入成功！');
				} else {
					_this.error(false, '失败', '记入失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '记入失败！');
				// console.log(error);
			})
		},

        // onclear_applicant
		onclear_applicant: function () {
			var _this = this;
			_this.piliangluru_applicant.map(function (v,i) {
				v.uid = '';
				v.applicant = '';
				v.department = '';
				v.start_date = '';
				v.end_date = '';
				v.category = '';
				v.duration = '';
			});
			
			// _this.$refs.xianti.focus();
		},

		// 远程查询角色
		remoteMethod_applicant (query) {
			var _this = this;

			if (query !== '') {
				_this.applicant_loading = true;
				
				var queryfilter_name = query;
				
				var url = "{{ route('renshi.jiaban.applicant.uidlist') }}";
				axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
				axios.get(url,{
					params: {
						queryfilter_name: queryfilter_name
					}
				})
				.then(function (response) {

				// if (response.data['jwt'] == 'logout') {
				// 	_this.alert_logout();
				// 	return false;
				// }
					
					if (response.data) {
						var json = response.data;
						_this.applicant_options = _this.json2selectvalue(json);
					}
				})
				.catch(function (error) {
				})				
				
				setTimeout(() => {
					_this.applicant_loading = false;
					// const list = this.list.map(item => {
						// return {
							// value: item,
							// label: item
						// };
					// });
					// this.options1 = list.filter(item => item.label.toLowerCase().indexOf(query.toLowerCase()) > -1);
				}, 200);
			} else {
				_this.applicant_options = [];
			}
		},

        // 选择role查看permission
		onchange_applicant: function (value) {
			var _this = this;

			var employeeid = value;
			// console.log(roleid);return false;
			
			if (employeeid == undefined || employeeid == '') {
				return false;
			}

			var url = "{{ route('admin.permission.rolehaspermission') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					employeeid: employeeid
				}
			})
			.then(function (response) {
				console.log(response.data);
				return false;

				// if (response.data['jwt'] == 'logout') {
				// 	_this.alert_logout();
				// 	return false;
				// }
				
				if (response.data) {
					var json = response.data.allpermissions;
					_this.datatransfer = _this.json2transfer(json);
					
					var arr = response.data.rolehaspermission;
					_this.targetkeystransfer = _this.arr2target(arr);

				} else {
					_this.targetkeystransfer = [];
					_this.datatransfer = [];
				}
			})
			.catch(function (error) {
				_this.error(false, 'Error', error);
			})
			
		},








		
		// 穿梭框目标文本转换（数字转字符串）
		arr2target: function (arr) {
			var res = [];
			arr.map(function( value, index) {
				// console.log('map遍历:'+index+'--'+value);
				res.push(value.toString());
			});
			return res;
		},
		
		// 切换当前页
		oncurrentpagechange: function (currentpage) {
			this.jiabangets(currentpage, this.page_last);
		},
		// 切换页记录数
		onpagesizechange: function (pagesize) {
			var _this = this;
			var cfg_data = {};
			cfg_data['PERPAGE_RECORDS_FOR_PERMISSION'] = pagesize;
			var url = "{{ route('admin.config.change') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				cfg_data: cfg_data
			})
			.then(function (response) {

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.page_size = pagesize;
					_this.jiabangets(1, _this.page_last);
				} else {
					_this.warning(false, 'Warning', 'failed!');
				}
			})
			.catch(function (error) {
				_this.error(false, 'Error', 'failed!');
			})
		},		
		
		jiabangets: function(page, last_page){
			var _this = this;
			
			if (page > last_page) {
				page = last_page;
			} else if (page < 1) {
				page = 1;
			}
			

			var queryfilter_name = _this.queryfilter_name;

			_this.loadingbarstart();
			var url = "{{ route('renshi.jiaban.jiabangets') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					perPage: _this.page_size,
					page: page,
					queryfilter_name: queryfilter_name,
				}
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				// if (response.data['jwt'] == 'logout') {
				// 	_this.alert_logout();
				// 	return false;
				// }

				if (response.data) {
					_this.delete_disabled = true;
					_this.tableselect = [];
					
					_this.page_current = response.data.current_page;
					_this.page_total = response.data.total;
					_this.page_last = response.data.last_page;
					_this.tabledata = response.data.data;
					
				}
				
				_this.loadingbarfinish();
			})
			.catch(function (error) {
				_this.loadingbarerror();
				_this.error(false, 'Error', error);
			})
		},
		

		
		// 表role选择
		onselectchange: function (selection) {
			var _this = this;

			_this.tableselect = [];
			for (var i in selection) {
				_this.tableselect.push(selection[i].id);
			}
			
			_this.delete_disabled = _this.tableselect[0] == undefined ? true : false;
		},

		onselectchangesub: function (selection) {
			var _this = this;

			_this.tableselect = [];
			for (var i in selection) {
				_this.tableselect.push(selection[i].main_id);
			}

			_this.tableselectsub = [];
			for (var i in selection) {
				_this.tableselectsub.push(selection[i].sub_id);
			}
			
			_this.delete_disabled = _this.tableselectsub[0] == undefined ? true : false;
		},

		// permission编辑前查看
		jiaban_edit: function (row) {
			var _this = this;
			
			_this.jiaban_edit_id = row.id;
			_this.permission_edit_name = row.name;

			_this.jiaban_edit_uuid = row.uuid;
			_this.jiaban_edit_agent = row.agent;
			_this.jiaban_edit_department_of_agent = row.department_of_agent;
			_this.jiaban_edit_applicant = row.applicant;
			_this.jiaban_edit_department_of_applicant = row.department_of_applicant;
			_this.jiaban_edit_category = row.category;
			_this.jiaban_edit_start_date = row.start_date;
			_this.jiaban_edit_end_date = row.end_date;
			_this.jiaban_edit_duration = row.duration;
			_this.jiaban_edit_status = row.status;
			_this.jiaban_edit_reason = row.reason;
			_this.jiaban_edit_remark = row.remark;
			_this.jiaban_edit_auditing = JSON.parse(row.auditing);
			_this.jiaban_edit_created_at = row.created_at;
			_this.jiaban_edit_updated_at = row.updated_at;



			// _this.role_edit_email = row.email;
			// _this.user_edit_password = row.password;
			// _this.relation_xuqiushuliang_edit[0] = row.xuqiushuliang;
			// _this.relation_xuqiushuliang_edit[1] = row.xuqiushuliang;
			// _this.user_created_at_edit = row.created_at;
			// _this.user_updated_at_edit = row.updated_at;

			_this.modal_jiaban_edit = true;
		},		
		

		// jiaban编辑后保存（同意）
		jiaban_edit_ok: function () {
			var _this = this;
			
			var id = _this.jiaban_edit_id;
			var name = _this.permission_edit_name;
			// var email = _this.user_edit_email;
			// var password = _this.user_edit_password;
			// var created_at = _this.relation_created_at_edit;
			// var updated_at = _this.relation_updated_at_edit;
			
			if (name == '' || name == null || name == undefined) {
				_this.warning(false, '警告', '内容不能为空！');
				return false;
			}
			
			// var regexp = /^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.[a-zA-Z0-9]{2,6}$/;
			// if (! regexp.test(email)) {
				// _this.warning(false, 'Warning', 'Email is incorrect!');
				// return false;
			// }
			
			var url = "{{ route('admin.permission.update') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				id: id,
				name: name,
				// email: email,
				// password: password,
				// xuqiushuliang: xuqiushuliang[1],
				// created_at: created_at,
				// updated_at: updated_at
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				_this.jiabangets(_this.page_current, _this.page_last);
				
				if (response.data) {
					_this.success(false, '成功', '更新成功！');
					
					_this.jiaban_edit_id = '';
					_this.permission_edit_name = '';
					// _this.role_edit_email = '';
					// _this.role_edit_password = '';
					
					// _this.relation_xuqiushuliang_edit = [0, 0];
					// _this.relation_created_at_edit = '';
					// _this.relation_updated_at_edit = '';
				} else {
					_this.error(false, '失败', '更新失败！请刷新查询条件后再试！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '更新失败！');
			})			
		},


		// 通过
		jiaban_edit_pass () {
			this.modal_jiaban_pass_loading = true;

			setTimeout(() => {
				this.modal_jiaban_pass_loading = false;
				this.modal_jiaban_edit = false;
				this.$Message.success('成功通过！');
			}, 2000);
		},

		// 拒绝
		jiaban_edit_deny () {
			this.modal_jiaban_deny_loading = true;

			setTimeout(() => {
				this.modal_jiaban_deny_loading = false;
				this.modal_jiaban_edit = false;
				this.$Message.success('成功拒绝！');
			}, 2000);
		},
		
		// ondelete_permission
		ondelete_permission: function () {
			var _this = this;
			
			var tableselect = _this.tableselect;
			
			if (tableselect[0] == undefined) return false;
			
			var url = "{{ route('admin.permission.permissiondelete') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				tableselect: tableselect
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.jiabangets(_this.page_current, _this.page_last);
					_this.success(false, '成功', '删除成功！');
				} else {
					_this.error(false, '失败', '删除失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '删除失败！');
			})
		},		
		
		// 显示新建权限
		oncreate_permission: function () {
			this.modal_permission_add = true;
		},
		
		// 新建权限
		oncreate_permission_ok: function () {
			var _this = this;
			var name = _this.permission_add_name;
			
			if (name == '' || name == null || name == undefined) {
				_this.warning(false, '警告', '内容不能为空！');
				return false;
			}
			
			// var re = new RegExp(“a”);  //RegExp对象。参数就是我们想要制定的规则。有一种情况必须用这种方式，下面会提到。
			// var re = /a/;   // 简写方法 推荐使用 性能更好  不能为空 不然以为是注释 ，
			// var regexp = /^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.[a-zA-Z0-9]{2,6}$/;
			// if (! regexp.test(email)) {
				// _this.warning(false, 'Warning', 'Email is incorrect!');
				// return false;
			// }

			var url = "{{ route('admin.permission.create') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				name: name,
			})
			.then(function (response) {
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.success(false, 'Success', 'Permission created successfully!');
					_this.permission_add_name = '';
					_this.jiabangets(_this.page_current, _this.page_last);
				} else {
					_this.error(false, 'Warning', 'Permission created failed!');
				}
			})
			.catch(function (error) {
				_this.error(false, 'Error', 'Permission created failed!');
			})
		},		
		
		// 导出权限
		onexport_permission: function(){
			var url = "{{ route('admin.permission.excelexport') }}";
			window.setTimeout(function(){
				window.location.href = url;
			}, 1000);
			return false;
		},		
		
		// 穿梭框显示文本
		rendertransfer: function (item) {
			return item.label + ' (ID:' + item.key + ')';
		},
		
		onChangeTransfer: function (newTargetKeys, direction, moveKeys) {
			// console.log(newTargetKeys);
			// console.log(direction);
			// console.log(moveKeys);
			this.targetkeystransfer = newTargetKeys;
		},		
		
		
		// 选择role查看permission
		onchange_role: function () {
			var _this = this;
			var roleid = _this.role_select;
			// console.log(roleid);return false;
			
			if (roleid == undefined || roleid == '') {
				_this.targetkeystransfer = [];
				_this.datatransfer = [];
				_this.boo_update = true;
				return false;
			}
			_this.boo_update = false;
			var url = "{{ route('admin.permission.rolehaspermission') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					roleid: roleid
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
					var json = response.data.allpermissions;
					_this.datatransfer = _this.json2transfer(json);
					
					var arr = response.data.rolehaspermission;
					_this.targetkeystransfer = _this.arr2target(arr);

				} else {
					_this.targetkeystransfer = [];
					_this.datatransfer = [];
				}
			})
			.catch(function (error) {
				_this.error(false, 'Error', error);
			})
			
		},
		
		// roleupdatepermission
		roleupdatepermission: function () {
			var _this = this;
			var roleid = _this.role_select;
			var permissionid = _this.targetkeystransfer;

			if (roleid == undefined || roleid == '') return false;
			
			var url = "{{ route('admin.permission.roleupdatepermission') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url,{
				roleid: roleid,
				permissionid: permissionid
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.success(false, 'Success', 'Update OK!');
				} else {
					_this.warning(false, 'Warning', 'Update failed!');
				}
			})
			.catch(function (error) {
				_this.error(false, 'Error', error);
			})
		},

		// 远程查询角色
		remoteMethod_role (query) {
			var _this = this;

			if (query !== '') {
				_this.role_loading = true;
				
				var queryfilter_name = query;
				
				var url = "{{ route('admin.permission.rolelist') }}";
				axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
				axios.get(url,{
					params: {
						queryfilter_name: queryfilter_name
					}
				})
				.then(function (response) {

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
					
					if (response.data) {
						var json = response.data;
						_this.role_options = _this.json2selectvalue(json);
					}
				})
				.catch(function (error) {
				})				
				
				setTimeout(() => {
					_this.role_loading = false;
					// const list = this.list.map(item => {
						// return {
							// value: item,
							// label: item
						// };
					// });
					// this.options1 = list.filter(item => item.label.toLowerCase().indexOf(query.toLowerCase()) > -1);
				}, 200);
			} else {
				_this.slot_options = [];
			}
		},
		
		
		// 选择permission查看role
		onchange_permission2role: function () {
			var _this = this;
			var permissionid = _this.permission2role_select;
			// console.log(permissionid);return false;
			
			if (permissionid == undefined || permissionid == '') {
				_this.permission2role_input = '';
				return false;
			}

			var url = "{{ route('admin.permission.permissiontoviewrole') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					permissionid: permissionid
				}
			})
			.then(function (response) {

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					var json = response.data;
					var str = '';
					for (var key in json) {
						str += json[key] + '\n';
					}
					// _this.permission2role_input = str.slice(0, -2);
					_this.permission2role_input = str.replace(/\n$/, '');
				}
			})
			.catch(function (error) {
				_this.error(false, 'Error', error);
			})
		},		

		// 远程查询权限
		remoteMethod_permission2role (query) {
			var _this = this;

			if (query !== '') {
				_this.permission2role_loading = true;
				
				var queryfilter_name = query;
				
				var url = "{{ route('admin.permission.permissionlist') }}";
				axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
				axios.get(url,{
					params: {
						queryfilter_name: queryfilter_name
					}
				})
				.then(function (response) {
					if (response.data['jwt'] == 'logout') {
						_this.alert_logout();
						return false;
					}
					
					if (response.data) {
						var json = response.data;
						_this.permission2role_options = _this.json2selectvalue(json);
					}
				})
				.catch(function (error) {
				})				
				
				setTimeout(() => {
					_this.permission2role_loading = false;
				}, 200);
			} else {
				_this.permission2role_options = [];
			}
		},

		
		// 远程查询权限（同步）
		remoteMethod_sync_permission (query) {
			var _this = this;
			if (query !== '') {
				_this.test_permission_loading = true;
				var queryfilter_name = query;
				var url = "{{ route('admin.permission.permissionlist') }}";
				axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
				axios.get(url,{
					params: {
						queryfilter_name: queryfilter_name
					}
				})
				.then(function (response) {
					if (response.data['jwt'] == 'logout') {
						_this.alert_logout();
						return false;
					}
					
					if (response.data) {
						var json = response.data;
						_this.test_permission_options = _this.json2selectvalue(json);
					}
				})
				.catch(function (error) {
				})				
				setTimeout(() => {
					_this.test_permission_loading = false;
				}, 200);
			} else {
				_this.test_permission_options = [];
			}
		},
		
		
		// 远程查询用户（同步）
		remoteMethod_sync_user (query) {
			var _this = this;
			if (query !== '') {
				_this.test_user_loading = true;
				var queryfilter_name = query;
				var url = "{{ route('admin.permission.userlist') }}";
				axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
				axios.get(url,{
					params: {
						queryfilter_name: queryfilter_name
					}
				})
				.then(function (response) {
					if (response.data['jwt'] == 'logout') {
						_this.alert_logout();
						return false;
					}
					
					if (response.data) {
						var json = response.data;
						_this.test_user_options = _this.json2selectvalue(json);
					}
				})
				.catch(function (error) {
				})				
				setTimeout(() => {
					_this.test_user_loading = false;
				}, 200);
			} else {
				_this.test_user_options = [];
			}
		},		
		

		// 测试用户是否有权限
		testuserspermission: function () {
			var _this = this;
			var permissionid = _this.test_permission_select;
			var userid = _this.test_user_select;

			if (userid == undefined || userid == '' ||
				permissionid == undefined || permissionid == '') {
				_this.warning(false, 'Warning', '内容不能为空！');
				return false;
			}
			
			var url = "{{ route('admin.permission.testuserspermission') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url,{
				permissionid: permissionid,
				userid: userid
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.success(false, 'Success', 'Permission(s) test successfully!');
				} else {
					_this.warning(false, 'Warning', 'Permission(s) test failed!');
				}
			})
			.catch(function (error) {
				_this.error(false, 'Error', 'Permission(s) test failed!');
			})
		},

	},
	mounted: function(){
		var _this = this;
		_this.current_nav = '加班管理';
		_this.current_subnav = '申请';
		// 显示所有
		_this.jiabangets(1, 1); // page: 1, last_page: 1
	}
});
</script>
@endsection