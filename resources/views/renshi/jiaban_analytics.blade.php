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
<Divider orientation="left">加班统计</Divider>

<Collapse v-model="collapse_query">
	<Panel name="1">
		查询条件
		<p slot="content">
		
			<i-row :gutter="16">
			<br>
				<i-col span="5">
					申请人ID&nbsp;
					<i-select v-model.lazy="queryfilter_uid" filterable remote :remote-method="remoteMethod_queryfilter_uid" :loading="loading_queryfilter_uid" @on-change="jiabangetsanalytics(page_current, page_last)" clearable placeholder="输入工号后选择" style="width: 120px;" size="small">
						<i-option v-for="item in options_queryfilter_uid" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
				<i-col span="5">
					申请人&nbsp;&nbsp;
					<i-select v-model.lazy="queryfilter_applicant" filterable remote :remote-method="remoteMethod_queryfilter_applicant" :loading="loading_queryfilter_applicant" @on-change="jiabangetsanalytics(page_current, page_last)" clearable placeholder="输入姓名后选择" style="width: 120px;" size="small">
						<i-option v-for="item in options_queryfilter_applicant" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
				<i-col span="8">
					提交时间&nbsp;
					<Date-picker v-model.lazy="queryfilter_created_at" @on-change="jiabangetsanalytics(page_current, page_last)" type="daterange" format="yyyy-MM-dd HH:mm" size="small" placeholder="" style="width:250px"></Date-picker>
				</i-col>
				<i-col span="6">
					类别&nbsp;
					<i-select v-model.lazy="queryfilter_category" @on-change="jiabangetsanalytics(page_current, page_last)" clearable placeholder="" style="width: 120px;" size="small">
						<i-option v-for="item in options_queryfilter_category" :value="item.value" :key="item.value">@{{ item.label }}</i-option>
					</i-select>
				</i-col>
			</i-row>


		
		
		&nbsp;
		</p>
	</Panel>
</Collapse>
&nbsp;

<Tabs type="card" v-model="currenttabs">

	<Tab-pane Icon="ios-list-box-outline" label="表格统计">

		<i-row :gutter="16">
			<br>
			<i-col span="3">
				<i-button @click="ontrash_archived()" :disabled="delete_disabled" icon="ios-trash-outline" type="warning" size="small">批量删除</i-button>&nbsp;<br>&nbsp;
			</i-col>
			<i-col span="2">
				<Poptip confirm title="确定要导出当前数据吗？" placement="right-start" @on-ok="onexport_archived()" @on-cancel="" transfer="true">
					<i-button type="default" size="small" @click=""><Icon type="ios-download-outline"></Icon> 导出列表</i-button>
				</Poptip>
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
			
				<Modal v-model="modal_jiaban_edit" title="查看 - 加班单" width="800" footer-hide="true">
				<span id="id_modal_jiaban" style="page-break-after:always">
					<Divider orientation="center" class="print_display" media="print">加 班 单</Divider>

					<div style="text-align:left">
						
						<i-row :gutter="16">
							<i-col span="10">
								UUID&nbsp;&nbsp;
								<i-input v-model.lazy="jiaban_edit_uuid" readonly="true" style="width: 250px" size="small"></i-input>
							</i-col>

							<i-col span="7">
								提交时间&nbsp;&nbsp;
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
								<!-- <i-input v-model.lazy="jiaban_edit_agent" readonly="true" style="width: 160px" size="small"></i-input> -->

								<Poptip trigger="hover" placement="bottom-start" width="360">
									<i-input v-model.lazy="jiaban_edit_agent" readonly="true" style="width: 160px" size="small"></i-input>
									<div id="id_print_img" class="api" slot="content">
										<div class="">
											<img :src="jiaban_edit_camera_imgurl" alt="暂无内容">
										</div>
									</div>
								</Poptip>

							</i-col>

							<i-col span="9">
								代理申请人部门&nbsp;&nbsp;
								<i-input v-model.lazy="jiaban_edit_department_of_agent" readonly="true" style="width: 160px" size="small"></i-input>
							</i-col>

							<i-col span="5">
							状态：
								<span v-if="jiaban_edit_status==99">
									已结案 <Icon type="md-thumbs-up"></Icon>
								</span>
								<span v-else-if="jiaban_edit_status==0">
									已否决 <Icon type="md-thumbs-down"></Icon>
								</span>
								<span v-else>
									处理中 <Icon type="md-cafe"></Icon>
								</span>
							</i-col>

							<i-col span="2">
								<!-- <i-button @click="printJS('id_modal_jiaban', 'html')">Print</i-button> -->
								<i-button id="id_print_button" icon="ios-print-outline" size="small" @click="printJS({ printable: 'id_modal_jiaban', type: 'html', documentTitle: '加班单 - 申请', scanStyles: true, css: '{{ asset('statics/iview/styles/iview.css') }}', ignoreElements: ['id_print_button', 'id_print_img'] })">打印</i-button>
							</i-col>

						</i-row>
						

						&nbsp;<Divider orientation="left">审核流程</Divider>

						<Steps :current="jiaban_edit_auditing_index" :status="jiaban_edit_status==0?'error':'process'" size="small">
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

								<div class="print_auditing" media="print">
								<span v-for="(auditing, index) in jiaban_edit_auditing_circulation">

									&nbsp;
									<i-row :gutter="16">
									<br>
										<i-col span="1">
											<span v-if="index==jiaban_edit_status-1">
												<Tooltip content="流程当前位置" placement="right">
													<Icon type="ios-cafe-outline"></Icon>
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
													<Icon type="ios-paper-plane-outline"></Icon>
												</Tooltip>
											</span>
											<span v-else>&nbsp;</span>
										</i-col>
									</i-row>

								</span>
								</div>
							
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

								<div class="print_application" media="print">
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
								</div>
							
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

									<i-row :gutter="16">
									<span v-if="index!=0"><br></span>
										<i-col span="7">
											审核&nbsp;&nbsp;
											<i-input v-model.lazy="auditing.auditor" readonly="true" style="width: 160px"></i-input>
										</i-col>
										<i-col span="5">
											状态&nbsp;&nbsp;
											<i-input v-model.lazy="auditing.status==1?'同意':'否决'" readonly="true" style="width: 80px"></i-input>
										</i-col>
										<i-col span="12">
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

						<!-- &nbsp;
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
						</i-row> -->
						
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
				
					<br>
					
					<span class="print_display" media="print">打印时间：<span id="getcurrentdatetime"></span></span>
				</span>	
				</Modal>

		
			</i-col>
		</i-row>

	</Tab-pane>


	<Tab-pane Icon="ios-stats-outline" label="图表统计">

		<i-row :gutter="16">
			<i-col span="24">
				<div id="chart1" style="width:auto;height:400px;"></div>
			</i-col>
		</i-row>

	</Tab-pane>




</Tabs>

<my-passwordchange></my-passwordchange>

@endsection

@section('my_footer')
@parent

@endsection

@section('my_js_others')
@parent
<script>
var vm_app = new Vue({
	el: '#app',
	components: {
		'my-passwordchange': httpVueLoader("{{ asset('components/my-passwordchange.vue') }}")
	},
    data: {
		// 是否全屏
		isfullscreen: false,

		// 修改密码界面
		modal_password_edit: false,

		current_nav: '',
		current_subnav: '',
		
		sideractivename: '1-4',
		sideropennames: ['1'],
		
		//分页
		page_current: 1,
		page_total: 0, // 记录总数，非总页数
		page_size: {{ $user['configs']['PERPAGE_RECORDS_FOR_ANALYTICS'] ?? 5 }},
		page_last: 1,

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
				title: '序号',
				type: 'index',
				align: 'center',
				width: 70,
				indexMethod: (row) => {
					return row._index + 1 + vm_app.page_size * (vm_app.page_current - 1)
				}
			},
			{
				title: 'UUID',
				key: 'uuid',
				sortable: true,
				width: 110,
				render: (h, params) => {
					return h('div', {}, [
						h('span',{
							// style:{
							// 	color: '#ff9900'
							// }
						}, params.row.uuid.substr(0, 8) + ' ...')
					])
				}
			},
			{
				title: '申请人',
				key: 'applicant',
				width: 160,
				render: (h, params) => {
					return h('div', {}, [
						h('Icon',{
							props: {
								type: 'ios-person',
								// size: 14,
								}
							}
						),
						h('span',{
							// style:{
							// 	color: '#ff9900'
							// }
						}, ' '+params.row.applicant)
					])
				}
			},
			{
				title: '申请人部门',
				key: 'department',
				width: 160,
				render: (h, params) => {
					return h('div', {}, [
						h('Icon',{
							props: {
								type: 'ios-people',
								// size: 14,
								}
							}
						),
						h('span',{
							// style:{
							// 	color: '#ff9900'
							// }
						}, ' '+params.row.department)
					])
				}
			},
			{
				title: '代理申请人',
				key: 'agent',
				width: 160,
				render: (h, params) => {
					return h('div', {}, [
						h('Icon',{
							props: {
								type: 'ios-person',
								// size: 14,
								}
							}
						),
						h('span',{
							// style:{
							// 	color: '#ff9900'
							// }
						}, ' '+params.row.agent)
					])
				}
			},
			{
				title: '代理申请人部门',
				key: 'department_of_agent',
				width: 160,
				render: (h, params) => {
					return h('div', {}, [
						h('Icon',{
							props: {
								type: 'ios-people',
								// size: 14,
								}
							}
						),
						h('span',{
							// style:{
							// 	color: '#ff9900'
							// }
						}, ' '+params.row.department_of_agent)
					])
				}
			},
			{
				title: '类别',
				key: 'category',
				width: 110,
			},
			{
				title: '时间',
				key: 'datetimerange',
				width: 250,
			},
			{
				title: '时长',
				key: 'duration',
				width: 90,
			},
			{
				title: '提交时间',
				key: 'created_at',
				// sortable: true,
				width: 160
			},
		],
		tabledata: [],
		tableselect: [],
		
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
		jiaban_edit_camera_imgurl: '',
		jiaban_edit_auditing: '',
		jiaban_edit_auditing_circulation: '',
		jiaban_edit_auditing_index: 0,
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
		queryfilter_uid: '',
		queryfilter_applicant: '',
		queryfilter_category: '',
		queryfilter_created_at: '',
		queryfilter_trashed: false,
		
		// 查询过滤器下拉
		collapse_query: '1',
		
		// 选择下拉过滤器
		options_queryfilter_uid: [],
		loading_queryfilter_uid: false,
		options_queryfilter_applicant: [],
		loading_queryfilter_applicant: false,
		options_queryfilter_category: [
			{label: '平时加班', value: '平时加班'},
			{label: '双休加班', value: '双休加班'},
			{label: '节假日加班', value: '节假日加班'},
		],

		
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
		
		// chart1数据
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
					_this.jiabangetsanalytics(_this.page_current, _this.page_last);
					_this.success(false, '成功', '归档状态改变成功！');
				} else {
					_this.error(false, '失败', '归档状态改变失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '归档状态改变失败！');
			})

		},

		
		// 切换当前页
		oncurrentpagechange: function (currentpage) {
			this.jiabangetsanalytics(currentpage, this.page_last);
		},
		// 切换页记录数
		onpagesizechange: function (pagesize) {
			var _this = this;
			var field = 'PERPAGE_RECORDS_FOR_ANALYTICS';
			var value = pagesize;
			var url = "{{ route('renshi.jiaban.applicant.changeconfigs') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				field: field,
				value: value
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.page_size = pagesize;
					_this.jiabangetsanalytics(1, _this.page_last);
				} else {
					_this.warning(false, 'Warning', 'failed!');
				}
			})
			.catch(function (error) {
				_this.error(false, 'Error', 'failed!');
			})
		},		
		
		jiabangetsanalytics (page, last_page) {
			var _this = this;
			
			if (page > last_page) {
				page = last_page;
			} else if (page < 1) {
				page = 1;
			}
			
			var queryfilter_uid = _this.queryfilter_uid || '';
			var queryfilter_applicant = _this.queryfilter_applicant || '';
			var queryfilter_category = _this.queryfilter_category || '';
			var queryfilter_created_at = _this.queryfilter_created_at;
			
			if (queryfilter_uid == ''
				&& queryfilter_applicant == ''
				&& queryfilter_category == ''
				&& queryfilter_created_at[0] == '' && queryfilter_created_at[1] == '') {
				// _this.delete_disabled = true;
				_this.tabledata = [];
				_this.page_current = 1;
				_this.page_total = 0;
				_this.page_last = 1;
				return false;
			}
			
			if (queryfilter_created_at[0] != '' && queryfilter_created_at[1] != '') {
				queryfilter_created_at = [queryfilter_created_at[0].Format("yyyy-MM-dd 00:00:00"), queryfilter_created_at[1].Format("yyyy-MM-dd 23:59:59")];
			}

			// if (queryfilter_created_at[0]=='' || queryfilter_created_at[0]==undefined) {
			// 	queryfilter_created_at = '';
			// } else {
			// 	const end = new Date();
			// 	const start = new Date();
			// 	// 加8小时
			// 	end.setTime(queryfilter_created_at[1].getTime() + 3600 * 1000 * 8);
			// 	start.setTime(queryfilter_created_at[0].getTime() + 3600 * 1000 * 8);
			// 	// start.setTime(queryfilter_created_at[0].getTime() - 3600 * 1000 * 24 * 365);
			// 	queryfilter_created_at = [start, end];
			// }



// console.log(queryfilter_created_at);return false;
			_this.loadingbarstart();
			var url = "{{ route('renshi.jiaban.jiabangetsanalytics') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					perPage: _this.page_size,
					page: page,
					queryfilter_uid: queryfilter_uid,
					queryfilter_applicant: queryfilter_applicant,
					queryfilter_category: queryfilter_category,
					queryfilter_created_at: queryfilter_created_at,
				}
			})
			.then(function (response) {
				// console.log(response.data.res_paginate);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}

				if (response.data) {
					// _this.delete_disabled = true;
					// _this.tableselect = [];
					
					_this.page_current = response.data.res_paginate.current_page;
					_this.page_total = response.data.res_paginate.total;
					_this.page_last = response.data.res_paginate.last_page;
					_this.tabledata = response.data.res_paginate.data;

					var fulltotal = response.data.res_fulltotal;
					console.log(fulltotal);
					
				}

				// 图表显示
				if (_this.currenttabs > 0) {

					var pie_applicant = [];
					var pie_category = [];
					var pie_department = [];

					
					pie_applicant.push({
						'applicant': fulltotal[0].applicant,
						'duration': parseFloat(fulltotal[0].duration)
					});

					for (let i=1, l=fulltotal.length; i<l; i++) {
// console.log(fulltotal[i].applicant);
						for (let j=0, k=pie_applicant.length; j<k; j++) {
							
							if (pie_applicant[j].applicant == fulltotal[i].applicant) {
								pie_applicant[j].duration += parseFloat(fulltotal[i].duration);
							
							} else {
								pie_applicant.push({
									'applicant': fulltotal[i].applicant,
									'duration': parseFloat(fulltotal[i].duration)
								});
							}


						}



					}

					// console.log(pie_applicant);


					_this.chart1();
				}

				_this.loadingbarfinish();
			})
			.catch(function (error) {
				_this.loadingbarerror();
				_this.error(false, 'Error', error);
			})
		},
		

		// 表archived选择
		onselectchange: function (selection) {
			var _this = this;

			_this.tableselect = [];
			for (var i in selection) {
				_this.tableselect.push(selection[i].id);
			}
			
			_this.delete_disabled = _this.tableselect[0] == undefined ? true : false;
		},


		// jiaban编辑前查看
		jiaban_edit: function (row) {
			var _this = this;
			
			_this.jiaban_edit_id = row.id;
			_this.permission_edit_name = row.name;

			_this.jiaban_edit_uuid = row.uuid;
			_this.jiaban_edit_id_of_agent = row.id_of_agent;
			_this.jiaban_edit_agent = row.agent;
			_this.jiaban_edit_department_of_agent = row.department_of_agent;
			// _this.jiaban_edit_application = JSON.parse(row.application);
			_this.jiaban_edit_application = row.application;
			_this.jiaban_edit_status = row.status;
			_this.jiaban_edit_reason = row.reason;
			_this.jiaban_edit_remark = row.remark;
			_this.jiaban_edit_camera_imgurl = row.camera_imgurl;
			// _this.jiaban_edit_auditing = JSON.parse(row.auditing);
			_this.jiaban_edit_auditing = row.auditing;
			_this.jiaban_edit_created_at = row.created_at;
			_this.jiaban_edit_updated_at = row.updated_at;

			_this.jiaban_edit_auditing_index = row.index_of_auditor;
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
		
		onrestore_archived (row) {
			var _this = this;
			
			var id = row.id;
			
			if (id == undefined) return false;
			
			var url = "{{ route('renshi.jiaban.archived.archivedrestore') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				id: id
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;

				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.jiabangetsanalytics(_this.page_current, _this.page_last);
					_this.success(false, '成功', '恢复成功！');
				} else {
					_this.error(false, '失败', '恢复失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '恢复失败！');
			})

		},

		ontrash_archived () {
			var _this = this;
			
			var tableselect = _this.tableselect;
			
			if (tableselect[0] == undefined) return false;
			
			var url = "{{ route('renshi.jiaban.archived.archivedtrash') }}";
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
					_this.jiabangetsanalytics(_this.page_current, _this.page_last);
					_this.success(false, '成功', '删除成功！');
				} else {
					_this.error(false, '失败', '删除失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '删除失败！');
			})
		},
		
		ondelete_archived () {
			var _this = this;
			
			var tableselect = _this.tableselect;
			
			if (tableselect[0] == undefined) return false;
			
			var url = "{{ route('renshi.jiaban.archived.archiveddelete') }}";
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
					_this.jiabangetsanalytics(_this.page_current, _this.page_last);
					_this.success(false, '成功', '删除成功！');
				} else {
					_this.error(false, '失败', '删除失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '删除失败！');
			})
		},		
		
		// 导出 archived
		onexport_archived () {
			var _this = this;

			var queryfilter_trashed = _this.queryfilter_trashed;
			var queryfilter_created_at = _this.queryfilter_created_at;
			
			if (queryfilter_created_at[0]=='' || queryfilter_created_at[0]==undefined) {
				queryfilter_created_at = ['1970-01-01', '9999-12-31'];
			} else {
				const end = new Date();
				const start = new Date();
				// 加8小时
				// end.setTime(queryfilter_created_at[1].getTime() + 3600 * 1000 * 8);
				// start.setTime(queryfilter_created_at[0].getTime() + 3600 * 1000 * 8);
				end.setTime(queryfilter_created_at[1].getTime());
				start.setTime(queryfilter_created_at[0].getTime());
				// start.setTime(queryfilter_created_at[0].getTime() - 3600 * 1000 * 24 * 365);
				queryfilter_created_at = [start.Format("yyyy-MM-dd hh:mm:ss"), end.Format("yyyy-MM-dd hh:mm:ss")];
			}
			
			var queryfilter_datefrom = queryfilter_created_at[0];
			var queryfilter_dateto = queryfilter_created_at[1];
			
			var url = "{{ route('renshi.jiaban.archived.archivedexport') }}"
				+ "?queryfilter_datefrom=" + queryfilter_datefrom
				+ "&queryfilter_dateto=" + queryfilter_dateto
				+ "&queryfilter_trashed=" + queryfilter_trashed;
			window.setTimeout(function(){
				window.location.href = url;
			}, 1000);
			return false;
		},
		

		// 远程查询申请人ID
		remoteMethod_queryfilter_uid (query) {
			var _this = this;

			if (query !== '') {
				_this.loading_queryfilter_uid = true;
				
				var queryfilter_name = query;
				
				var url = "{{ route('renshi.jiaban.applicant.uidlist') }}";
				axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
				axios.get(url,{
					params: {
						queryfilter_name: queryfilter_name
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
						var json = response.data;
						_this.options_queryfilter_uid = _this.json2selectvalue(json);
					}

					setTimeout(() => {
						_this.loading_queryfilter_uid = false;
					}, 200);
				})
				.catch(function (error) {
					setTimeout(() => {
						_this.loading_queryfilter_uid = false;
					}, 200);
				})				
				
			} else {
				_this.options_queryfilter_uid = [];
			}
		},

		// 远程查询申请人
		remoteMethod_queryfilter_applicant (query) {
			var _this = this;

			if (query !== '') {
				_this.loading_queryfilter_applicant = true;
				
				var queryfilter_name = query;
				
				var url = "{{ route('renshi.jiaban.applicant.applicantlist') }}";
				axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
				axios.get(url,{
					params: {
						queryfilter_name: queryfilter_name
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
						var json = response.data;
						_this.options_queryfilter_applicant = _this.json2selectvalue(json);
					}

					setTimeout(() => {
						_this.loading_queryfilter_applicant = false;
					}, 200);
				})
				.catch(function (error) {
					setTimeout(() => {
						_this.loading_queryfilter_applicant = false;
					}, 200);
				})				
				
			} else {
				_this.options_queryfilter_applicant = [];
			}
		},

		// chart1 pie
		chart1() {
			var myChart = echarts.init(document.getElementById('chart1'));

			var data = this.chart1_data;

			option = {
				title: [{
					text: ''
				}, {
					subtext: '▲ 按人员',
					left: '16.67%',
					top: '50%',
					textAlign: 'center'
				}, {
					subtext: '▲ 按类别',
					left: '50%',
					top: '50%',
					textAlign: 'center'
				}, {
					subtext: '▲ 按部门',
					left: '83.33%',
					top: '50%',
					textAlign: 'center'
				}],
				series: [{
					type: 'pie',
					radius: '40%',
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
					bottom: 200
				}, {
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
				}, {
					type: 'pie',
					radius: '40%',
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
					bottom: 200
				}]
			};

			myChart.setOption(option);

		},



	},
	mounted: function(){
		var _this = this;
		_this.current_nav = '加班管理';
		_this.current_subnav = '统计';
		// 显示所有
		// _this.jiabangetsanalytics(1, 1); // page: 1, last_page: 1

		// GetCurrentDatetime('getcurrentdatetime');
	}
});
</script>
@endsection