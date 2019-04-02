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
<Divider orientation="left">Jiaban archived</Divider>

<Tabs type="card" v-model="currenttabs">

	<Tab-pane label="Archives List">

		<Collapse v-model="collapse_query">
			<Panel name="1">
				Archives Query Filter
				<p slot="content">
				
					<i-row :gutter="16">
						<i-col span="5">
							当前审核人&nbsp;&nbsp;
							<i-input v-model.lazy="queryfilter_auditor" @on-change="jiabangetsarchived(page_current, page_last)" size="small" clearable style="width: 100px"></i-input>
						</i-col>
						<i-col span="8">
							创建时间&nbsp;
							<Date-picker v-model.lazy="queryfilter_created_at" @on-change="jiabangetsarchived(page_current, page_last)" type="datetimerange" format="yyyy-MM-dd HH:mm" size="small" placeholder="" style="width:250px"></Date-picker>
						</i-col>
						<i-col span="2">
							<Checkbox v-model="queryfilter_trashed" @on-change="jiabangetsarchived(page_current, page_last)">已删除</Checkbox>
						</i-col>
						<i-col span="9">
							&nbsp;
						</i-col>
					</i-row>
				
				
				&nbsp;
				</p>
			</Panel>
		</Collapse>
		&nbsp;
		
		<i-row :gutter="16">
			<br>
			<i-col span="3">
				<i-button @click="ondelete_applicant()" :disabled="delete_disabled" type="warning" size="small">删除</i-button>&nbsp;<br>&nbsp;
			</i-col>
			<i-col span="2">
				<i-button type="default" size="small" @click="onexport_applicant()"><Icon type="ios-download-outline"></Icon> 导出列表</i-button>
			</i-col>
			<i-col span="4">
			&nbsp;
			</i-col>
			<i-col span="15">
			&nbsp;
			</i-col>
		</i-row>
		
		<i-row :gutter="16">
			<i-col span="24">
	
				<i-table height="300" size="small" border :columns="tablecolumns" :data="tabledata" @on-selection-change="selection => onselectchange(selection)"></i-table>
				<br><Page :current="page_current" :total="page_total" :page-size="page_size" @on-change="currentpage => oncurrentpagechange(currentpage)" @on-page-size-change="pagesize => onpagesizechange(pagesize)" :page-size-opts="[5, 10, 20, 50]" show-total show-elevator show-sizer></Page>
			
				<Modal v-model="modal_jiaban_edit" title="查看 - 加班单" width="800">
					
					<div style="text-align:left">
						
						<i-row :gutter="16">
							<i-col span="10">
								UUID&nbsp;&nbsp;
								<i-input v-model.lazy="jiaban_edit_uuid" readonly="true" style="width: 260px" size="small"></i-input>
							</i-col>

							<i-col span="7">
							创建时间&nbsp;&nbsp;
							<i-input v-model.lazy="jiaban_edit_created_at" readonly="true" style="width: 140px" size="small"></i-input>
							</i-col>

							<i-col span="7">
							更新时间&nbsp;&nbsp;
							<i-input v-model.lazy="jiaban_edit_updated_at" readonly="true" style="width: 140px" size="small"></i-input>
							</i-col>
						</i-row>

						&nbsp;
						<i-row :gutter="16">
						<br>
							<i-col span="8">
								代理申请人&nbsp;&nbsp;
								<i-input v-model.lazy="jiaban_edit_agent" readonly="true" style="width: 160px" size="small"></i-input>
							</i-col>

							<i-col span="9">
								代理申请人部门&nbsp;&nbsp;
								<i-input v-model.lazy="jiaban_edit_department_of_agent" readonly="true" style="width: 160px" size="small"></i-input>
							</i-col>

							<i-col span="7">
							状态：
								<span v-if="jiaban_edit_status==99">
									已结案 <Icon type="md-checkmark"></Icon>
								</span>
								<span v-else>
									未完成 <Icon type="md-close"></Icon>
								</span>
							</i-col>

						</i-row>
						

						&nbsp;<Divider orientation="left">审核流程</Divider>

						<Steps :current="jiaban_edit_status" size="small">
							<Step :title="jiaban_edit_agent" content="申请人"></Step>
							<Step v-for="(auditing, index) in jiaban_edit_auditing_circulation" :title="auditing.name" content="审核人"></Step>
						</Steps>

						@hasanyrole('role_super_admin')
						<Divider dashed></Divider>

						<i-row :gutter="16">
							<i-col span="24">

								<i-row :gutter="16">
									<i-col span="1">
										&nbsp;
									</i-col>
									<i-col span="2">
										序号
									</i-col>
									<i-col span="4">
										UID
									</i-col>
									<i-col span="4">
										审核人
									</i-col>
									<i-col span="4">
										部门
									</i-col>
									<i-col span="9">
										操作
									</i-col>
								</i-row>

								<span v-for="(auditing, index) in jiaban_edit_auditing_circulation">

									&nbsp;
									<i-row :gutter="16">
									<br>
										<i-col span="1">
											<span v-if="index==jiaban_edit_status-1">
												<Tooltip content="流程当前位置" placement="top">
													<Icon type="ios-cafe"></Icon>
												</Tooltip>
											</span>
											<span v-else>
												&nbsp;
											</span>
										</i-col>
										<i-col span="2">
											#@{{index+1}}
										</i-col>
										<i-col span="4">
											@{{ auditing.uid }}
										</i-col>
										<i-col span="4">
											@{{ auditing.name }}
										</i-col>
										<i-col span="4">
											@{{ auditing.department }}
										</i-col>
										<i-col span="9">
											<span v-if="index!=jiaban_edit_status-1">
												<Tooltip content="转至此用户" placement="top">
													<Icon type="ios-paper-plane"></Icon>
												</Tooltip>
											</span>
											<span v-else>&nbsp;</span>
										</i-col>
									</i-row>

								</span>
							
							</i-col>
						</i-row>
						@endhasanyrole


						&nbsp;<Divider orientation="left">加班信息</Divider>

						<i-row :gutter="16">
							<i-col span="24">

								<i-row :gutter="16">
									<i-col span="1">
										序号
									</i-col>
									<i-col span="3">
										工号
										<!-- <i-input v-model.lazy="application.applicant" readonly="true" style="width: 160px"></i-input> -->
									</i-col>
									<i-col span="3">
										姓名
										<!-- <i-input v-model.lazy="application.applicant" readonly="true" style="width: 160px"></i-input> -->
									</i-col>
									<i-col span="4">
										部门
									</i-col>
									<i-col span="3">
										类别
									</i-col>
									<i-col span="8">
										时间
									</i-col>
									<i-col span="2">
										时长
									</i-col>
								</i-row>

								<span v-for="(application, index) in jiaban_edit_application">

									&nbsp;
									<i-row :gutter="16">
									<br>
										<i-col span="1">
											#@{{index+1}}
										</i-col>
										<i-col span="3">
											@{{ application.uid }}
										</i-col>
										<i-col span="3">
											@{{ application.applicant }}
											<!-- <i-input v-model.lazy="application.applicant" readonly="true" style="width: 160px"></i-input> -->
										</i-col>
										<i-col span="4">
											@{{ application.department }}
										</i-col>
										<i-col span="3">
											@{{ application.category }}
										</i-col>
										<i-col span="8">
											@{{ application.datetimerange }}
										</i-col>
										<i-col span="2">
											@{{ application.duration }} 小时
										</i-col>
									</i-row>

								</span>
							
							</i-col>
						</i-row>

						&nbsp;
						<i-row :gutter="16">
						<br>
							<i-col span="24">
								理由&nbsp;&nbsp;
								<i-input v-model.lazy="jiaban_edit_reason" type="textarea" readonly="true" :autosize="{minRows: 2,maxRows: 5}"></i-input>
							</i-col>
						</i-row>

						&nbsp;
						<i-row :gutter="16">
						<br>
							<i-col span="24">
								备注&nbsp;&nbsp;
								<i-input v-model.lazy="jiaban_edit_remark" type="textarea" readonly="true" :autosize="{minRows: 2,maxRows: 5}"></i-input>
							</i-col>
						</i-row>

						&nbsp;<Divider orientation="left">审核信息</Divider>

						<i-row :gutter="16">
							<i-col span="24">
							
								<span v-for="(auditing, index) in jiaban_edit_auditing">

									&nbsp;
									<i-row :gutter="16">
									<span v-if="index!=0"><br></span>
										<i-col span="8">
											审核&nbsp;&nbsp;
											<i-input v-model.lazy="auditing.auditor" readonly="true" style="width: 160px"></i-input>
										</i-col>
										<i-col span="16">
											时间&nbsp;&nbsp;
											<i-input v-model.lazy="auditing.created_at" readonly="true" style="width: 160px"></i-input>
										</i-col>
									</i-row>

									&nbsp;
									<i-row :gutter="16">
									<br>
										<i-col span="24">
											意见&nbsp;&nbsp;
											<i-input v-model.lazy="auditing.opinion" type="textarea" readonly="true" :autosize="{minRows: 2,maxRows: 5}"></i-input>
										</i-col>
									</i-row>

								</span>
							
							</i-col>
						</i-row>

						&nbsp;
						<i-row :gutter="16">
						<br>
							<i-col span="24">
								status:&nbsp;&nbsp;
								<span v-if="jiaban_edit_status==99">
									已结案 <Icon type="md-checkmark"></Icon>
								</span>
								<span v-else>
									未完成 <Icon type="md-close"></Icon>
								</span>
							</i-col>
						</i-row>
						
						&nbsp;
					
					</div>
					<div slot="footer">
					<!--
						<i-button type="primary" size="large" long :loading="modal_jiaban_pass_loading" @click="jiaban_edit_pass">通 过</i-button>
						<br><br>
						<i-button type="text" size="large" long :loading="modal_jiaban_deny_loading" @click="jiaban_edit_deny">拒 绝</i-button>
					-->
						<i-button type="primary" size="large" long @click="modal_jiaban_edit=false">关 闭</i-button>
					</div>	
				</Modal>

		
			</i-col>
		</i-row>

	</Tab-pane>




</Tabs>


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
		
		sideractivename: '1-3',
		sideropennames: ['1'],

		// 创建
		jiaban_add_reason: '',
		jiaban_add_remark: '',
		
		// 批量录入applicant表
		piliangluru_applicant: [
			{
				uid: '',
				applicant: '',
				department: '',
				datetimerange: [],
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

		tablecolumns: [
			{
				type: 'selection',
				width: 50,
				align: 'center',
				fixed: 'left'
			},
			{
				title: '序号',
				type: 'index',
				align: 'center',
				width: 80,
			},
			{
				title: 'UUID',
				key: 'uuid',
				sortable: true,
				width: 280
			},
			{
				title: '代理申请人',
				key: 'agent',
				width: 160
			},
			{
				title: '代理申请人部门',
				key: 'department_of_agent',
				width: 160
			},
			{
				title: '当前审核人',
				key: 'auditor',
				width: 160
			},
			{
				title: '当前审核人部门',
				key: 'department_of_auditor',
				width: 160
			},
			{
				title: '状态',
				key: 'status',
				width: 90,
				render: (h, params) => {
					if (params.row.status == 99) {
						// return h('div', {}, '已结案')
							return h('div', {}, [
							h('Icon',{
								props: {
									type: 'ios-checkmark-circle-outline',
									// size: 14,
									}
								}
							),
							h('span',' 已结案')
						])

					} else {
						// return h('div', {}, '待处理')
						return h('div', {}, [
							h('Icon',{
								props: {
									type: 'ios-help-circle-outline',
									// size: 14,
									}
								}
							),
							h('span',' 待处理')
						])
					}	
				},
			},
			{
				title: '创建时间',
				key: 'created_at',
				width: 160
			},
			{
				@hasanyrole('role_super_admin')
				title: '已归档',
				key: 'archived',
				width: 80,
				render: (h, params) => {
					return h('div', [
						// params.row.deleted_at.toLocaleString()
						// params.row.deleted_at ? '禁用' : '启用'
						
						h('i-switch', {
							props: {
								type: 'primary',
								size: 'small',
								value: params.row.archived
							},
							style: {
								marginRight: '5px'
							},
							on: {
								'on-change': (value) => {//触发事件是on-change,用双引号括起来，
									//参数value是回调值，并没有使用到
									vm_app.onarchived_applicant(params.row.id) //params.index是拿到table的行序列，可以取到对应的表格值
								}
							}
						}, 'Edit')
						
					]);
				}
				@endhasanyrole
			},
			{
				title: '操作',
				key: 'action',
				align: 'center',
				width: 80,
				render: (h, params) => {
					return h('div', [
						h('Button', {
							props: {
								type: 'primary',
								size: 'small'
							},
							style: {
								marginRight: '5px'
							},
							on: {
								click: () => {
									vm_app.jiaban_edit(params.row)
								}
							}
						}, '查看')
					]);
				},
				fixed: 'right'
			}
		],
		tabledata: [],
		tableselect: [],

		//分页
		page_current: 1,
		page_total: 1, // 记录总数，非总页数
		page_size: {{ $config['PERPAGE_RECORDS_FOR_PERMISSION'] }},
		page_last: 1,
		
		// 编辑
		modal_jiaban_edit: false,
		modal_jiaban_pass_loading: false,
		modal_jiaban_deny_loading: false,
		jiaban_edit_id: '',
		jiaban_edit_uuid: '',
		jiaban_edit_agent: '',
		jiaban_edit_department_of_agent: '',
		jiaban_edit_application: '',
		jiaban_edit_status: 0,
		jiaban_edit_reason: '',
		jiaban_edit_remark: '',
		jiaban_edit_auditing: '',
		jiaban_edit_auditing_circulation: '',
		jiaban_edit_auditing_id: '',
		jiaban_edit_auditing_uid: '',
		jiaban_edit_created_at: '',
		jiaban_edit_updated_at: '',







		// 删除
		delete_disabled: true,
		delete_disabled_sub: true,

		// tabs索引
		currenttabs: 0,
		
		// 查询过滤器
		queryfilter_auditor: '',
		queryfilter_created_at: '',
		queryfilter_trashed: false,
		
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
                            datetimerange: [],
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

			var jiaban_add_reason = _this.jiaban_add_reason;
			var jiaban_add_remark = _this.jiaban_add_remark;

			if (jiaban_add_reason == ''
				|| jiaban_add_reason == undefined) {
				booFlagOk = false;
			} else {
				_this.piliangluru_applicant.map(function (v,i) {
					// applicant: '',
					// department: '',
					// datetimerange: [],
					// category: '',
					// duration: ''
					
					if (v.applicant == '' || v.department == '' || v.category == ''  || v.duration == '' || v.datetimerange[0] == ''
						|| v.applicant == undefined || v.department == undefined || v.category == undefined || v.duration == undefined || v.datetimerange[0] == undefined) {
						booFlagOk = false;
					}
				});
			}
			
			if (booFlagOk == false) {
				_this.warning(false, '警告', '输入内容为空或不正确！');
				return false;
			}
			
			var piliangluru_applicant = _this.piliangluru_applicant;
			
			var url = "{{ route('renshi.jiaban.applicant.applicantcreate') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				reason: jiaban_add_reason,
				remark: jiaban_add_remark,
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
					_this.jiabangetsarchived(_this.page_current, _this.page_last);
					_this.success(false, '成功', '提交成功！');
				} else {
					_this.error(false, '失败', '提交失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '提交失败！');
			})
		},

        // onclear_applicant
		onclear_applicant: function () {
			var _this = this;
			_this.jiaban_add_reason = '';
			_this.jiaban_add_remark = '';
			_this.piliangluru_applicant.map(function (v,i) {
				v.uid = '';
				v.applicant = '';
				v.department = '';
				v.datetimerange = [];
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
		onchange_applicant: function (value, index) {
			var _this = this;

			var employeeid = value;
			// console.log(roleid);return false;
			
			if (employeeid == undefined || employeeid == '') {
                _this.piliangluru_applicant[index].applicant = '';
                _this.piliangluru_applicant[index].department = '';
				return false;
			}

			var url = "{{ route('renshi.jiaban.applicant.employeelist') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					employeeid: employeeid
				}
			})
			.then(function (response) {
                // alert(index);
				// console.log(response.data);
				// return false;

				// if (response.data['jwt'] == 'logout') {
				// 	_this.alert_logout();
				// 	return false;
				// }
				
				if (response.data) {
                    var json = response.data;
                    var arr = [];
                    for (var key in json) {
                        arr.push(json[key]);
                    }
                    _this.piliangluru_applicant[index].applicant = arr[0];
                    _this.piliangluru_applicant[index].department = arr[1];
				} else {
                    _this.piliangluru_applicant[index].applicant = '';
                    _this.piliangluru_applicant[index].department = '';
                }
			})
			.catch(function (error) {
				_this.error(false, 'Error', error);
			})
			
		},


		// 归档
		onarchived_applicant (jiaban_id) {
			var _this = this;
			
			// var jiaban_id = _this.jiaban_edit_id;
			// console.log(jiaban_id);
			
			if (jiaban_id == undefined) return false;
			
			var url = "{{ route('renshi.jiaban.applicant.applicantarchived') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				jiaban_id: jiaban_id
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.modal_jiaban_edit = false;
					_this.jiabangetsarchived(_this.page_current, _this.page_last);
					_this.success(false, '成功', '归档状态改变成功！');
				} else {
					_this.error(false, '失败', '归档状态改变失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '归档状态改变失败！');
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
			this.jiabangetsarchived(currentpage, this.page_last);
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
					_this.jiabangetsarchived(1, _this.page_last);
				} else {
					_this.warning(false, 'Warning', 'failed!');
				}
			})
			.catch(function (error) {
				_this.error(false, 'Error', 'failed!');
			})
		},		
		
		jiabangetsarchived: function(page, last_page){
			var _this = this;
			
			if (page > last_page) {
				page = last_page;
			} else if (page < 1) {
				page = 1;
			}
			

			var queryfilter_auditor = _this.queryfilter_auditor;
			var queryfilter_created_at = _this.queryfilter_created_at;
			var queryfilter_trashed = _this.queryfilter_trashed;

			if (queryfilter_created_at[0]=='' || queryfilter_created_at[0]==undefined) {
				queryfilter_created_at = '';
			} else {
				const end = new Date();
				const start = new Date();
				// 加8小时
				end.setTime(queryfilter_created_at[1].getTime() + 3600 * 1000 * 8);
				start.setTime(queryfilter_created_at[0].getTime() + 3600 * 1000 * 8);
				// start.setTime(queryfilter_created_at[0].getTime() - 3600 * 1000 * 24 * 365);
				queryfilter_created_at = [start, end];
			}

			queryfilter_trashed = queryfilter_trashed || '';

			_this.loadingbarstart();
			var url = "{{ route('renshi.jiaban.jiabangetsarchived') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					perPage: _this.page_size,
					page: page,
					queryfilter_auditor: queryfilter_auditor,
					queryfilter_created_at: queryfilter_created_at,
					queryfilter_trashed: queryfilter_trashed,
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
			_this.jiaban_edit_id_of_agent = row.id_of_agent;
			_this.jiaban_edit_agent = row.agent;
			_this.jiaban_edit_department_of_agent = row.department_of_agent;
			_this.jiaban_edit_application = JSON.parse(row.application);
			_this.jiaban_edit_status = row.status;
			_this.jiaban_edit_reason = row.reason;
			_this.jiaban_edit_remark = row.remark;
			_this.jiaban_edit_auditing = JSON.parse(row.auditing);
			_this.jiaban_edit_created_at = row.created_at;
			_this.jiaban_edit_updated_at = row.updated_at;

			_this.jiaban_edit_auditing_id = row.id_of_auditor;
			_this.jiaban_edit_auditing_uid = row.uid_of_auditor;

			var url = "{{ route('renshi.jiaban.applicant.auditinglist') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					id: _this.jiaban_edit_id_of_agent
				}
			})
			.then(function (response) {
                // alert(index);
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
                    _this.jiaban_edit_auditing_circulation = response.data;
                }
			})
			.catch(function (error) {
				_this.error(false, 'Error', error);
			})

			setTimeout(() => {
				_this.modal_jiaban_edit = true;
			}, 500);
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
				
				_this.jiabangetsarchived(_this.page_current, _this.page_last);
				
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
		
		// ondelete_applicant
		ondelete_applicant: function () {
			var _this = this;
			
			var tableselect = _this.tableselect;
			
			if (tableselect[0] == undefined) return false;
			
			var url = "{{ route('renshi.jiaban.applicant.applicanttrash') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				id: tableselect
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.jiabangetsarchived(_this.page_current, _this.page_last);
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
		oncreate_applicant_gototab: function () {
			this.currenttabs = 1;
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
					_this.jiabangetsarchived(_this.page_current, _this.page_last);
				} else {
					_this.error(false, 'Warning', 'Permission created failed!');
				}
			})
			.catch(function (error) {
				_this.error(false, 'Error', 'Permission created failed!');
			})
		},		
		
		// 导出权限
		onexport_applicant: function(){
			alert('功能待完成！');
			return false;

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
		_this.current_subnav = '归档';
		// 显示所有
		_this.jiabangetsarchived(1, 1); // page: 1, last_page: 1
	}
});
</script>
@endsection