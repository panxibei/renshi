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
<Divider orientation="left">加班处理信息</Divider>

<Tabs type="card" v-model="currenttabs">
	<Tab-pane Icon="ios-create-outline" label="处理列表">
	
		<Collapse v-model="collapse_query">
			<Panel name="1">
				查询过滤器
				<p slot="content">
				
					<i-row :gutter="16">
						<i-col span="5">
							代理申请人&nbsp;&nbsp;
							<i-input v-model.lazy="queryfilter_applicant" @on-change="jiabangetstodo(page_current, page_last)" size="small" clearable style="width: 100px"></i-input>
						</i-col>
						<i-col span="8">
							提交时间&nbsp;
							<Date-picker v-model.lazy="queryfilter_created_at" @on-change="jiabangetstodo(page_current, page_last)" type="datetimerange" format="yyyy-MM-dd HH:mm" size="small" placeholder="" style="width:250px"></Date-picker>
						</i-col>
						<i-col span="2">
							@hasanyrole('role_super_admin')
								<Checkbox v-model="queryfilter_trashed" @on-change="jiabangetstodo(page_current, page_last)">已删除</Checkbox>
							@else
								&nbsp;
							@endhasanyrole
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
				<i-button @click="ontrash_todo()" :disabled="delete_disabled" icon="ios-trash-outline" type="warning" size="small">批量删除</i-button>&nbsp;<br>&nbsp;
			</i-col>
			<i-col span="2">
				&nbsp;
				<!-- <i-button type="default" size="small" @click="onexport_todo()"><Icon type="ios-download-outline"></Icon> 导出列表</i-button> -->
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
			
				<Modal v-model="modal_jiaban_edit" title="处理 - 加班单" width="800" footer-hide="true">
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
							
							<span v-if="jiaban_edit_auditing">
								<span v-for="(auditing, index) in jiaban_edit_auditing">

									<i-row :gutter="16">
										<i-col span="24">
										<span v-if="index!=0"><Divider dashed></Divider></span>
										</i-col>
									</i-row>

									<i-row :gutter="16">
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

									<i-row :gutter="16">
										<i-col span="24">
											意见&nbsp;&nbsp;
											<i-input v-model.lazy="auditing.opinion" type="textarea" readonly="true" :autosize="{minRows: 2,maxRows: 5}"></i-input>
										</i-col>
									</i-row>

								</span>
							</span>
							<span v-else>暂无内容</span>
							
							</i-col>
						</i-row>

						<!-- &nbsp;
						<i-row :gutter="16">
						<br>
							<i-col span="24">
							status&nbsp;&nbsp;
							@{{ jiaban_edit_status }}
							</i-col>
						</i-row> -->
						
						&nbsp;
					
					</div>

					
					<div slot="footer">
						<div v-if="jiaban_edit_status!=99 && jiaban_edit_status!=0">
							<div style="text-align:center;font-size:14px;">
								意&nbsp;&nbsp;&nbsp;&nbsp;见
								<i-input v-model.lazy="jiaban_edit_opinion" type="textarea" :autosize="{minRows: 2,maxRows: 5}"></i-input>
							</div>
							<br><br>
							<i-button icon="ios-thumbs-up" type="primary" size="large" long :loading="modal_jiaban_pass_loading" @click="jiaban_edit_pass(jiaban_edit_id)">同 意</i-button>
							<br><br>
							<i-button icon="ios-thumbs-down-outline" type="text" size="large" long :loading="modal_jiaban_deny_loading" @click="jiaban_edit_deny(jiaban_edit_id)">否 决</i-button>
						</div>	
						<div v-else>
							<i-button type="primary" size="large" long @click="modal_jiaban_edit=false">关 闭</i-button>
						</div>
					</div>
					
					<br>
					
					<span class="print_display" media="print">打印时间：<span id="getcurrentdatetime"></span></span>
				</span>	
				</Modal>
		
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
		
		sideractivename: '1-2',
		sideropennames: ['1'],
				
		//分页
		page_current: 1,
		page_total: 1, // 记录总数，非总页数
		page_size: {{ $user['configs']['PERPAGE_RECORDS_FOR_TODO'] ?? 5 }},
		page_last: 1,

		tablecolumns: [
			{
				type: 'selection',
				width: 60,
				align: 'center',
				fixed: 'left'
			},
            // {
            //     type: 'expand',
            //     width: 50,
            //     render: (h, params) => {

            //         return h('div', [
						
            //             h('i-table', {
            //                 props: {
            //                     columns: vm_app.tablecolumnssub,
            //                     data: JSON.parse(params.row.info)
            //                 },
            //                 style: {
            //                     // colspan: 8
            //                 },
			// 				on: {
			// 					"on-selection-change": (selection) => {
			// 						vm_app.onselectchangesub(selection)
			// 					}
			// 				}

            //             },
            //             ''
            //             ),
			// 		]);
            //     }
            // },
			{
				title: '序号',
				type: 'index',
				align: 'center',
				width: 70,
				indexMethod: (row) => {
					return row._index + 1 + vm_app.page_size * (vm_app.page_current - 1)
				}
			},
			// {
			// 	title: '',
			// 	key: 'id',
			// 	sortable: true,
			// 	width: 0,
			// 	render: (h, params) => {
			// 		return h('div');
			// 	},
			// },
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
			// {
			// 	title: '',
			// 	key: 'applicant',
			// 	width: 0,
			// 	render: (h, params) => {
			// 		return h('div');
			// 	},
			// },
			// {
			// 	title: '',
			// 	key: 'department_of_applicant',
			// 	width: 0,
			// 	render: (h, params) => {
			// 		return h('div');
			// 	},
			// },
			// {
			// 	title: '',
			// 	key: 'category',
			// 	width: 0,
			// 	render: (h, params) => {
			// 		return h('div');
			// 	},
			// },
			// {
			// 	title: '',
			// 	key: 'kaishi_riqi',
			// 	width: 0,
			// 	render: (h, params) => {
			// 		return h('div');
			// 	},
			// },
			// {
			// 	title: '',
			// 	key: 'jiesu_riqi',
			// 	width: 0,
			// 	render: (h, params) => {
			// 		return h('div');
			// 	},
			// },
			// {
			// 	title: '',
			// 	key: 'duration',
			// 	width: 0,
			// 	render: (h, params) => {
			// 		return h('div');
			// 	},
			// },
			{
				title: '进度',
				key: 'progress',
				width: 140,
				render: (h, params) => {
					// return h('div', {}, params.row.progress + '%')
					if (params.row.progress == 0) {
						return h('div', {}, [
							h('Progress',{
								props: {
									percent: 99,
									status: 'wrong',
									}
								}
							)
						])
					} else {
						return h('div', {}, [
							h('Progress',{
								props: {
									percent: params.row.progress,
									status: 'active',
									}
								}
							)
						])
					}
				}
			},
			{
				title: '状态',
				key: 'status',
				width: 90,
				render: (h, params) => {
					if (params.row.archived == 1) {
						return h('div', {}, [
							h('Icon',{
								props: {
									type: 'ios-archive-outline',
									// size: 14,
									}
								}
							),
							h('span',' 已归档')
						])
					} else if (params.row.status == 99) {
						return h('div', {}, [
							h('Icon',{
								props: {
									type: 'ios-checkmark-circle-outline',
									// size: 14,
									}
								}
							),
							h('span',{
								style:{
									color: '#19be6b'
								}
							},' 已结案')
						])
					} else if (params.row.status == 0) {
						return h('div', {}, [
							h('Icon',{
								props: {
									type: 'ios-close-circle-outline',
									// size: 14,
									}
								}
							),
							h('span',{
								style:{
									color: '#ed4014'
								}
							},' 已否决')
						])
					} else {
						return h('div', {}, [
							h('Icon',{
								props: {
									type: 'ios-help-circle-outline',
									// size: 14,
									}
								}
							),
							h('span',{
								style:{
									color: '#ff9900'
								}
							},' 待处理')
						])
					}
				},
			},
			// {
			// 	title: '',
			// 	key: 'liyou',
			// 	width: 0,
			// 	render: (h, params) => {
			// 		return h('div');
			// 	},
			// },
			// {
			// 	title: '',
			// 	key: 'remark',
			// 	width: 0,
			// 	render: (h, params) => {
			// 		return h('div');
			// 	},
			// },
			// {
			// 	title: '',
			// 	key: 'auditing',
			// 	width: 0,
			// 	render: (h, params) => {
			// 		return h('div');
			// 	},
			// },
			{
				title: '提交时间',
				key: 'created_at',
				sortable: true,
				width: 160
			},
			// {
			// 	title: '',
			// 	key: 'updated_at',
			// 	width: 0,
			// 	render: (h, params) => {
			// 		return h('div');
			// 	},
			// },
			{
				title: '操作',
				key: 'action',
				align: 'center',
				@hasanyrole('role_super_admin')
					width: 200,
				@else
					width: 80,
				@endhasanyrole
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
						}, '处理'),
						@hasanyrole('role_super_admin')
						h('Button', {
							props: {
								type: 'warning',
								size: 'small'
							},
							style: {
								marginRight: '5px'
							},
							on: {
								click: () => {
									vm_app.onrestore_todo(params.row)
								}
							}
						}, '恢复'),
						h('Button', {
							props: {
								type: 'error',
								size: 'small'
							},
							style: {
								marginRight: '5px'
							},
							on: {
								click: () => {
									vm_app.ondelete_todo(params.row)
								}
							}
						}, '彻底删除'),
						@endhasanyrole
					]);
				},
				fixed: 'right'
			}
		],
		tabledata: [],
		tableselect: [],
		
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
		jiaban_edit_id_of_agent: '',
		jiaban_edit_agent: '',
		jiaban_edit_department_of_agent: '',
		jiaban_edit_application: '',
		// jiaban_edit_applicant: '',
		// jiaban_edit_department_of_applicant: '',
		// jiaban_edit_category: '',
		// jiaban_edit_start_date: '',
		// jiaban_edit_end_date: '',
		// jiaban_edit_duration: '',
		jiaban_edit_status: 0,
		jiaban_edit_reason: '',
		jiaban_edit_remark: '',
		jiaban_edit_camera_imgurl: '',
		jiaban_edit_opinion: '',
		jiaban_edit_auditing: '',
		jiaban_edit_auditing_circulation: '',
		jiaban_edit_auditing_uid: '',
		jiaban_edit_created_at: '',
		jiaban_edit_updated_at: '',
		
		// 删除
		delete_disabled: true,
		delete_disabled_sub: true,

		// tabs索引
		currenttabs: 0,
		
		// 查询过滤器
		queryfilter_applicant: '',
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
			this.jiabangetstodo(currentpage, this.page_last);
		},
		// 切换页记录数
		onpagesizechange: function (pagesize) {
			var _this = this;
			var field = 'PERPAGE_RECORDS_FOR_TODO';
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
					_this.jiabangetstodo(1, _this.page_last);
				} else {
					_this.warning(false, 'Warning', 'failed!');
				}
			})
			.catch(function (error) {
				_this.error(false, 'Error', 'failed!');
			})
		},		
		
		jiabangetstodo: function(page, last_page){
			var _this = this;
			
			if (page > last_page) {
				page = last_page;
			} else if (page < 1) {
				page = 1;
			}
			
			var queryfilter_applicant = _this.queryfilter_applicant;
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
			var url = "{{ route('renshi.jiaban.jiabangetstodo') }}";
			axios.defaults.headers.get['X-Requested-With'] = 'XMLHttpRequest';
			axios.get(url,{
				params: {
					perPage: _this.page_size,
					page: page,
					queryfilter_applicant: queryfilter_applicant,
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
				
				_this.jiabangetstodo(_this.page_current, _this.page_last);
				
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
		jiaban_edit_pass (jiaban_id) {
			var _this = this;

			var jiaban_id = jiaban_id;
			var jiaban_id_of_agent = _this.jiaban_edit_id_of_agent;
			var opinion = _this.jiaban_edit_opinion;
			// console.log(jiaban_id_of_agent);
			// return false;

			_this.modal_jiaban_pass_loading = true;

			_this.$Message.loading('正在提交...');

			var url = "{{ route('renshi.jiaban.todo.pass') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				jiaban_id: jiaban_id,
				jiaban_id_of_agent: jiaban_id_of_agent,
				opinion: opinion,
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;
				
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.jiabangetstodo(_this.page_current, _this.page_last);
					_this.success(false, '成功', '成功通过！');
					// setTimeout(() => {
					// 	this.modal_jiaban_pass_loading = false;
					// 	this.modal_jiaban_edit = false;
					// 	this.$Message.success('成功通过！');
					// }, 2000);

				} else {
					_this.error(false, '失败', '提交通过失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '提交通过失败！');
			})




			// setTimeout(() => {
				this.modal_jiaban_pass_loading = false;
				this.modal_jiaban_edit = false;
				// this.$Message.success('成功通过！');
			// }, 2000);
		},

		// 否决
		jiaban_edit_deny (jiaban_id) {
			var _this = this;

			var jiaban_id = jiaban_id;
			var jiaban_id_of_agent = _this.jiaban_edit_id_of_agent;
			var opinion = _this.jiaban_edit_opinion;
			// console.log(jiaban_id_of_agent);
			// return false;

			_this.modal_jiaban_deny_loading = true;

			_this.$Message.loading('正在提交...');

			var url = "{{ route('renshi.jiaban.todo.deny') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url, {
				jiaban_id: jiaban_id,
				jiaban_id_of_agent: jiaban_id_of_agent,
				opinion: opinion,
			})
			.then(function (response) {
				// console.log(response.data);
				// return false;
				
				if (response.data['jwt'] == 'logout') {
					_this.alert_logout();
					return false;
				}
				
				if (response.data) {
					_this.jiabangetstodo(_this.page_current, _this.page_last);
					_this.success(false, '成功', '成功否决！');
					// setTimeout(() => {
					// 	this.modal_jiaban_pass_loading = false;
					// 	this.modal_jiaban_edit = false;
					// 	this.$Message.success('成功通过！');
					// }, 2000);

				} else {
					_this.error(false, '失败', '提交否决失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '提交否决失败！');
			})




			// setTimeout(() => {
				this.modal_jiaban_deny_loading = false;
				this.modal_jiaban_edit = false;
				// this.$Message.success('成功否决！');
			// }, 2000);








			// this.modal_jiaban_deny_loading = true;

			// setTimeout(() => {
			// 	this.modal_jiaban_deny_loading = false;
			// 	this.modal_jiaban_edit = false;
			// 	this.$Message.success('成功否决！');
			// }, 2000);
		},


		onrestore_todo (row) {
			var _this = this;
			
			var id = row.id;
			
			if (id == undefined) return false;
			
			var url = "{{ route('renshi.jiaban.todo.todorestore') }}";
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
					_this.jiabangetstodo(_this.page_current, _this.page_last);
					_this.success(false, '成功', '恢复成功！');
				} else {
					_this.error(false, '失败', '恢复失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '恢复失败！');
			})

		},

		ontrash_todo () {
			var _this = this;
			
			var tableselect = _this.tableselect;
			
			if (tableselect[0] == undefined) return false;
			
			var url = "{{ route('renshi.jiaban.todo.todotrash') }}";
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
					_this.jiabangetstodo(_this.page_current, _this.page_last);
					_this.success(false, '成功', '删除成功！');
				} else {
					_this.error(false, '失败', '删除失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '删除失败！');
			})
		},
		
		ondelete_todo () {
			var _this = this;
			
			var tableselect = _this.tableselect;
			
			if (tableselect[0] == undefined) return false;
			
			var url = "{{ route('renshi.jiaban.todo.tododelete') }}";
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
					_this.jiabangetstodo(_this.page_current, _this.page_last);
					_this.success(false, '成功', '删除成功！');
				} else {
					_this.error(false, '失败', '删除失败！');
				}
			})
			.catch(function (error) {
				_this.error(false, '错误', '删除失败！');
			})
		},











		

		
		// 导出权限
		onexport_todo: function(){
			alert('功能待完成！');
			return false;

			// var url = "{{ route('admin.permission.excelexport') }}";
			// window.setTimeout(function(){
			// 	window.location.href = url;
			// }, 1000);
			// return false;
		},










	},
	mounted: function(){
		var _this = this;
		_this.current_nav = '加班管理';
		_this.current_subnav = '处理';
		// 显示所有
		_this.jiabangetstodo(1, 1); // page: 1, last_page: 1

		GetCurrentDatetime('getcurrentdatetime');
	}
});
</script>
@endsection