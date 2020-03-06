<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
    // return view('index');
// });

// Renshi cube 路由
Route::group(['prefix'=>'renshi', 'namespace'=>'Renshi', 'middleware'=>['jwtauth']], function() {

	// 111111111111111
	// 显示applicantcube页面
	Route::get('jiabancubeApplicant', 'JiabancubeController@jiabancubeApplicant')->name('renshi.jiaban.applicantcube');

	// 显示applicant list cube页面
	Route::get('jiabancubeApplicantList', 'JiabancubeController@jiabancubeApplicantList')->name('renshi.jiaban.applicantcube.list');

	// applicantcubeCreate
	Route::post('applicantcubeCreate', 'JiabancubeController@applicantcubeCreate')->name('renshi.jiaban.applicantcube.applicantcubecreate');

	// jiabancubeGetsApplicant
	Route::get('jiabancubeGetsApplicant', 'JiabancubeController@jiabancubeGetsApplicant')->name('renshi.jiaban.applicantcube.applicantcubegets');

	// jiabangetsanalytics
	Route::get('jiabancubeGetsAnalytics', 'JiabancubeController@jiabancubeGetsAnalytics')->name('renshi.jiaban.applicantcube.jiabangetsanalytics');

});


// Renshi路由
// Route::group(['prefix'=>'renshi', 'namespace'=>'Admin', 'middleware'=>['jwtauth','permission:permission_admin_permission|permission_super_admin']], function() {
Route::group(['prefix'=>'renshi', 'namespace'=>'Renshi', 'middleware'=>['jwtauth']], function() {

	// 111111111111111
	// 显示applicant页面
	Route::get('jiabanApplicant', 'JiabanController@jiabanApplicant')->name('renshi.jiaban.applicant');

	// jiaban gets列表
	Route::get('jiabanGetsApplicant', 'JiabanController@jiabanGetsApplicant')->name('renshi.jiaban.jiabangetsapplicant');

	// applicant页面 查询employee_uid
	Route::get('uidList', 'JiabanController@uidList')->name('renshi.jiaban.applicant.uidlist');

	// applicant页面 查询auditing
	Route::get('auditingList', 'JiabanController@auditingList')->name('renshi.jiaban.applicant.auditinglist');

	// applicant页面 查询employee
	Route::get('employeeList', 'JiabanController@employeeList')->name('renshi.jiaban.applicant.employeelist');

	// applicant页面 批量录入1
	Route::post('applicantCreate1', 'JiabanController@applicantCreate1')->name('renshi.jiaban.applicant.applicantcreate1');

	// applicant页面 批量录入2
	Route::post('applicantCreate2', 'JiabanController@applicantCreate2')->name('renshi.jiaban.applicant.applicantcreate2');

	// applicant页面 软删除
	Route::post('applicantTrash', 'JiabanController@applicantTrash')->name('renshi.jiaban.applicant.applicanttrash');

	// applicant页面 软删除恢复
	Route::post('applicantRestore', 'JiabanController@applicantRestore')->name('renshi.jiaban.applicant.applicantrestore');

	// applicant页面 硬删除
	Route::post('applicantDelete', 'JiabanController@applicantDelete')->name('renshi.jiaban.applicant.applicantdelete');

	// applicant页面 归档
	Route::post('applicantArchived', 'JiabanController@applicantArchived')->name('renshi.jiaban.applicant.applicantarchived');

	// applicant页面 查询department
	// Route::get('departmentList', 'JiabanController@departmentList')->name('renshi.jiaban.applicant.departmentlist');
	
	// 列出当前用户拥有的角色
	// Route::get('department2Applicant', 'JiabanController@department2Applicant')->name('renshi.jiaban.applicant.department2applicant');

	// 列出Tree用户
	Route::get('loadApplicant', 'JiabanController@loadApplicant')->name('renshi.jiaban.applicant.loadapplicant');

	// 列出loadapplicantgroup
	Route::get('loadApplicantGroup', 'JiabanController@loadApplicantGroup')->name('renshi.jiaban.applicant.loadapplicantgroup');

	// loadapplicantgroupdetails
	Route::get('loadApplicantGroupDetails', 'JiabanController@loadApplicantGroupDetails')->name('renshi.jiaban.applicant.loadapplicantgroupdetails');

	// 新增人员组
	Route::post('createApplicantGroup', 'JiabanController@createApplicantGroup')->name('renshi.jiaban.applicant.createapplicantgroup');

	// 删除人员组
	Route::post('deleteApplicantGroup', 'JiabanController@deleteApplicantGroup')->name('renshi.jiaban.applicant.deleteapplicantgroup');

	// 配置变更
	Route::post('changeConfigs', 'JiabanController@changeConfigs')->name('renshi.jiaban.applicant.changeconfigs');

	// 导出列表
	Route::get('applicantExport', 'JiabanController@applicantExport')->name('renshi.jiaban.applicant.applicantexport');


	// 22222222222
	// 显示todo页面
	Route::get('jiabanTodo', 'JiabanController@jiabanTodo')->name('renshi.jiaban.applicant_todo');

	// jiaban gets列表
	Route::get('jiabanGetsTodo', 'JiabanController@jiabanGetsTodo')->name('renshi.jiaban.jiabangetstodo');

	// todo页面 pass
	Route::post('todoPass', 'JiabanController@todoPass')->name('renshi.jiaban.todo.pass');

	// todo页面 deny
	Route::post('todoDeny', 'JiabanController@todoDeny')->name('renshi.jiaban.todo.deny');

	// todo页面 软删除
	Route::post('todoTrash', 'JiabanController@todoTrash')->name('renshi.jiaban.todo.todotrash');

	// todo页面 软删除恢复
	Route::post('todoRestore', 'JiabanController@todoRestore')->name('renshi.jiaban.todo.todorestore');
	
	// todo 硬删除
	Route::post('todoDelete', 'JiabanController@todoDelete')->name('renshi.jiaban.todo.tododelete');
	

	// 333333333
	// 显示applicant页面
	Route::get('jiabanConfirm', 'ConfirmController@jiabanConfirm')->name('renshi.jiaban.confirm');

	// jiaban gets列表
	Route::get('jiabanGetsConfirm', 'ConfirmController@jiabanGetsConfirm')->name('renshi.jiaban.jiabangetsconfirm');


	// 444444444
	// 显示todo页面
	Route::get('jiabanConfirmTodo', 'ConfirmController@jiabanConfirmTodo')->name('renshi.jiaban.confirm_todo');

	// jiaban gets列表
	Route::get('jiabanGetsConfirmTodo', 'ConfirmController@jiabanGetsConfirmTodo')->name('renshi.jiaban.jiabangetsconfirmtodo');




	// 555555555
	// 显示archived页面
	Route::get('jiabanArchived', 'JiabanController@jiabanArchived')->name('renshi.jiaban.archived');

	// archived gets列表
	Route::get('jiabanGetsArchived', 'JiabanController@jiabanGetsArchived')->name('renshi.jiaban.jiabangetsarchived');

	// archived页面 软删除
	Route::post('archivedTrash', 'JiabanController@archivedTrash')->name('renshi.jiaban.archived.archivedtrash');

	// archived页面 软删除恢复
	Route::post('archivedRestore', 'JiabanController@archivedRestore')->name('renshi.jiaban.archived.archivedrestore');
	
	// archived 硬删除
	Route::post('archivedDelete', 'JiabanController@archivedDelete')->name('renshi.jiaban.archived.archiveddelete');

	// 导出列表
	Route::get('archivedExport', 'JiabanController@archivedExport')->name('renshi.jiaban.archived.archivedexport');


	// 66666666
	// 显示Analytics页面
	Route::get('jiabanAnalytics', 'JiabanController@jiabanAnalytics')->name('renshi.jiaban.analytics');

	// Analytics gets列表
	Route::get('jiabanGetsAnalytics', 'JiabanController@jiabanGetsAnalytics')->name('renshi.jiaban.jiabangetsanalytics');

	// Analytics 查询applicant
	Route::get('applicantList', 'JiabanController@applicantList')->name('renshi.jiaban.applicant.applicantlist');

	
});


// 中日程分析页面f
Route::group(['prefix'=>'bpjg', 'namespace'=>'Bpjg', 'middleware'=>['jwtauth','permission:permission_bpjg_zrcfx|permission_super_admin']], function() {
	Route::get('zrcfx', 'zrcfxController@zrcfxIndex')->name('bpjg.zrcfx.index');
	Route::post('zrcfximport', 'zrcfxController@zrcfxImport')->name('bpjg.zrcfx.zrcfximport');
	Route::get('zrcdownload', 'zrcfxController@zrcDownload')->name('bpjg.zrcfx.zrcdownload');
	Route::get('relationgets', 'zrcfxController@relationGets')->name('bpjg.zrcfx.relationgets');
	Route::post('relationupdate', 'zrcfxController@relationUpdate')->name('bpjg.zrcfx.relationupdate');
	Route::post('relationcreate', 'zrcfxController@relationCreate')->name('bpjg.zrcfx.relationcreate');
	Route::post('relationdelete', 'zrcfxController@relationDelete')->name('bpjg.zrcfx.relationdelete');
	Route::get('relationexport', 'zrcfxController@relationExport')->name('bpjg.zrcfx.relationexport');
	Route::post('relationimport', 'zrcfxController@relationImport')->name('bpjg.zrcfx.relationimport');
	Route::get('relationdownload', 'zrcfxController@relationDownload')->name('bpjg.zrcfx.relationdownload');
	Route::get('zrcfxfunction', 'zrcfxController@zrcfxFunction')->name('bpjg.zrcfx.zrcfxfunction');
	Route::get('resultgets', 'zrcfxController@resultGets')->name('bpjg.zrcfx.resultgets');
	Route::get('resultexport', 'zrcfxController@resultExport')->name('bpjg.zrcfx.resultexport');
});

// 品质日报页面
Route::group(['prefix'=>'smt', 'namespace'=>'Smt', 'middleware'=>['jwtauth','permission:permission_smt_qcreport|permission_super_admin']], function() {
	Route::get('qcreportIndex', 'qcreportController@qcreportIndex')->name('smt.qcreport.index');
	Route::get('qcreportgets', 'qcreportController@qcreportGets')->name('smt.qcreport.qcreportgets');
	Route::get('bulianggets', 'qcreportController@buliangGets')->name('smt.qcreport.bulianggets');
	Route::get('getsaomiao', 'qcreportController@getSaomiao')->name('smt.qcreport.getsaomiao');
	Route::post('qcreportcreate', 'qcreportController@qcreportCreate')->name('smt.qcreport.qcreportcreate');
	Route::post('qcreportupdate', 'qcreportController@qcreportUpdate')->name('smt.qcreport.qcreportupdate');
	Route::post('qcreportdelete', 'qcreportController@qcreportDelete')->name('smt.qcreport.qcreportdelete');
	Route::get('qcreportexport', 'qcreportController@qcreportExport')->name('smt.qcreport.qcreportexport');
	Route::post('qcreportimport', 'qcreportController@qcreportImport')->name('smt.qcreport.qcreportimport');
	Route::get('chart1', 'qcreportController@chart1')->name('smt.qcreport.chart1');
	Route::get('chart2', 'qcreportController@chart2')->name('smt.qcreport.chart2');
	Route::get('getdianmei', 'qcreportController@getDianmei')->name('smt.qcreport.getdianmei');
});


// 生产日报页面
Route::group(['prefix'=>'smt', 'namespace'=>'Smt', 'middleware'=>['jwtauth','permission:permission_smt_pdreport|permission_super_admin']], function() {
	Route::get('pdreportIndex', 'pdreportController@pdreportIndex')->name('smt.pdreport.index');
	Route::get('dailyreportgets', 'pdreportController@dailyreportGets')->name('smt.pdreport.dailyreportgets');
	Route::get('getjizhongming', 'pdreportController@getJizhongming')->name('smt.pdreport.getjizhongming');
	Route::get('pdreportexport', 'pdreportController@pdreportExport')->name('smt.pdreport.pdreportexport');
	Route::post('dailyreportcreate', 'pdreportController@dailyreportCreate')->name('smt.pdreport.dailyreportcreate');
	Route::post('dailyreportdelete', 'pdreportController@dailyreportDelete')->name('smt.pdreport.dailyreportdelete');
	Route::post('dandangzhechange', 'pdreportController@dandangzheChange')->name('smt.pdreport.dandangzhechange');
	Route::post('querenzhechange', 'pdreportController@querenzheChange')->name('smt.pdreport.querenzhechange');
});


// MPoint页面
Route::group(['prefix'=>'smt', 'namespace'=>'Smt', 'middleware'=>['jwtauth','permission:permission_smt_mpoint|permission_super_admin']], function() {
	Route::get('mpoint', 'pdreportController@mpoint')->name('smt.pdreport.mpoint');
	Route::get('mpointgets', 'pdreportController@mpointGets')->name('smt.pdreport.mpointgets');
	Route::post('mpointcreate', 'pdreportController@mpointCreate')->name('smt.pdreport.mpointcreate');
	Route::post('mpointupdate', 'pdreportController@mpointUpdate')->name('smt.pdreport.mpointupdate');
	Route::post('mpointdelete', 'pdreportController@mpointDelete')->name('smt.pdreport.mpointdelete');
	Route::post('mpointimport', 'pdreportController@mpointImport')->name('smt.pdreport.mpointimport');
	Route::get('mpointdownload', 'pdreportController@mpointDownload')->name('smt.pdreport.mpointdownload');
});


// AOTA门户页面
Route::group(['prefix'=>'', 'namespace'=>'Main', 'middleware'=>['jwtauth']], function() {
	Route::get('/', 'mainController@mainPortal')->name('portal');
	Route::get('portal', 'mainController@mainPortal')->name('portal');
	Route::get('portalcube', 'mainController@mainPortalcube')->name('portalcube');
	Route::get('portalcubeuser', 'mainController@portalcubeUser')->name('portalcubeuser');
	Route::get('configgets', 'mainController@configGets')->name('smt.configgets');

	// logout
	Route::get('logout', 'mainController@logout')->name('main.logout');
});


// AOTA配置页面
Route::group(['prefix'=>'smt', 'namespace'=>'Main', 'middleware'=>['jwtauth','permission:permission_smt_config|permission_super_admin']], function() {
	Route::get('config', 'mainController@mainConfig')->name('smt.config');;
	Route::post('configcreate', 'mainController@configCreate')->name('smt.configcreate');
	Route::post('configupdate', 'mainController@configUpdate')->name('smt.configupdate');
});


// release页面
Route::group(['prefix'=>'release', 'namespace'=>'Main', 'middleware'=>['jwtauth']], function() {
	Route::get('/', 'mainController@mainRelease')->name('release');
	Route::get('releasegets', 'mainController@mainReleasegets')->name('release.releasegets');
});


// home模块
Route::group(['prefix' => 'login', 'namespace' =>'Home'], function() {
	Route::get('/', 'LoginController@index')->name('login');
	Route::get('cube', 'LogincubeController@index')->name('logincube');
	Route::post('checklogin', 'LoginController@checklogin')->name('login.checklogin');
	Route::post('checklogincube', 'LogincubeController@checklogin')->name('logincube.checklogin');
	// Route::post('checklogin', 'LoginController@checklogin')->name('login.checklogin');
});


// AdminController路由 修改密码
Route::group(['prefix'=>'admin', 'namespace'=>'Admin', 'middleware'=>['jwtauth','permission:permission_admin_changepassword|permission_super_admin']], function() {
	// 修改密码
	Route::post('passwordchange', 'AdminController@passwordChange')->name('admin.password.change');
});


// AdminController路由
Route::group(['prefix'=>'admin', 'namespace'=>'Admin', 'middleware'=>['jwtauth','permission:permission_super_admin']], function() {
	// 显示system页面
	Route::get('systemIndex', 'AdminController@systemIndex')->name('admin.system.index');
	
	// 获取config数据信息
	Route::get('systemList', 'AdminController@systemList')->name('admin.system.list');


	// 显示config页面
	Route::get('configIndex', 'AdminController@configIndex')->name('admin.config.index');

	// 获取config数据信息
	Route::get('configList', 'AdminController@configList')->name('admin.config.list');

	// 获取group数据信息
	Route::get('groupList', 'AdminController@groupList')->name('admin.group.list');
	

	// 修改config数据
	Route::post('configChange', 'AdminController@configChange')->name('admin.config.change');

	// logout
	Route::get('logout', 'AdminController@logout')->name('admin.logout');

});


// UserController路由
Route::group(['prefix'=>'user', 'namespace'=>'Admin', 'middleware'=>['jwtauth','permission:permission_admin_user|permission_super_admin']], function() {

	// 显示user页面
	Route::get('userIndex', 'UserController@userIndex')->name('admin.user.index');

	// 获取user数据信息
	Route::get('userList', 'UserController@userList')->name('admin.user.list');

	// 列出指定的用户
	Route::get('uidList', 'UserController@uidList')->name('admin.user.uidlist');

	// 创建user
	Route::post('userCreate', 'UserController@userCreate')->name('admin.user.create');

	// 禁用user（软删除）
	Route::post('userTrash', 'UserController@userTrash')->name('admin.user.trash');

	// 删除user
	Route::post('userDelete', 'UserController@userDelete')->name('admin.user.delete');

	// 编辑user
	Route::post('userUpdate', 'UserController@userUpdate')->name('admin.user.update');

	// 测试excelExport
	Route::get('excelExport', 'UserController@excelExport')->name('admin.user.excelexport');

	// 清除user的ttl
	Route::post('userclsttl', 'UserController@userClsttl')->name('admin.user.clsttl');

	// 列出当前用户拥处理用户 申请->批量 OK
	Route::get('userHasAuditing1Applicant', 'UserController@userHasAuditing1Applicant')->name('admin.user.userhasauditing1applicant');

	// 列出当前用户拥处理用户 确认->批量 OK
	Route::get('userHasAuditing1Confirm', 'UserController@userHasAuditing1Confirm')->name('admin.user.userhasauditing1confirm');

	// 列出当前用户的处理用户 申请->单独 OK
	Route::get('userHasAuditing2', 'UserController@userHasAuditing2')->name('admin.user.userhasauditing2');

	// 列出当前用户的处理用户 确认->单独 OK
	Route::get('userHasAuditing2Confirm', 'UserController@userHasAuditing2Confirm')->name('admin.user.userhasauditing2confirm');

	// 添加处理用户
	Route::post('auditingAdd', 'UserController@auditingAdd')->name('admin.user.auditingadd');

	// 添加处理用户 确认->单独
	Route::post('auditingAddConfirm', 'UserController@auditingAddConfirm')->name('admin.user.auditingaddconfirm');

	// 更新处理用户
	Route::post('auditingUpdate', 'UserController@auditingUpdate')->name('admin.user.auditingupdate');

	// 排序移动处理用户
	Route::post('auditingSort', 'UserController@auditingSort')->name('admin.user.auditingsort');

	// 排序移动处理用户 confirm
	Route::post('auditingSortConfirm', 'UserController@auditingSortConfirm')->name('admin.user.auditingsortconfirm');

	// 删除处理用户
	Route::post('auditingRemove', 'UserController@auditingRemove')->name('admin.user.auditingremove');

	// 删除处理用户 confirm
	Route::post('auditingRemoveConfirm', 'UserController@auditingRemoveConfirm')->name('admin.user.auditingremoveconfirm');

	// 加载外部数据源用户
	Route::get('getExternalUsers', 'UserController@getExternalUsers')->name('admin.user.getexternalusers');
	

});


// RoleController路由
Route::group(['prefix'=>'role', 'namespace'=>'Admin', 'middleware'=>['jwtauth','permission:permission_admin_role|permission_super_admin']], function() {

	// 显示role页面
	Route::get('roleIndex', 'RoleController@roleIndex')->name('admin.role.index');

	// 列出所有用户
	Route::get('userList', 'RoleController@userList')->name('admin.role.userlist');

	// 列出所有角色
	Route::get('roleList', 'RoleController@roleList')->name('admin.role.rolelist');

	// 列出所有权限
	Route::get('permissionList', 'RoleController@permissionList')->name('admin.role.permissionlist');

	// 列出所有待删除的角色
	Route::get('roleListDelete', 'RoleController@roleListDelete')->name('admin.role.rolelistdelete');

	// 创建role
	Route::post('roleCreate', 'RoleController@roleCreate')->name('admin.role.create');

	// 编辑role
	Route::post('roleUpdate', 'RoleController@roleUpdate')->name('admin.role.update');
	
	// 删除角色
	Route::post('roleDelete', 'RoleController@roleDelete')->name('admin.role.roledelete');

	// 列出当前用户拥有的角色
	Route::get('userHasRole', 'RoleController@userHasRole')->name('admin.role.userhasrole');

	// 更新当前用户的角色
	Route::post('userUpdateRole', 'RoleController@userUpdateRole')->name('admin.role.userupdaterole');

	// 列出当前用户可追加的角色
	// Route::get('userGiveRole', 'RoleController@userGiveRole')->name('admin.role.usergiverole');

	// 赋予role
	Route::post('roleGive', 'RoleController@roleGive')->name('admin.role.give');
	// 移除role
	// Route::post('roleRemove', 'RoleController@roleRemove')->name('admin.role.remove');

	// 根据角色查看哪些用户
	Route::get('roleToViewUser', 'RoleController@roleToViewUser')->name('admin.role.roletoviewuser');

	// 权限同步到指定角色
	Route::post('syncPermissionToRole', 'RoleController@syncPermissionToRole')->name('admin.role.syncpermissiontorole');

	// 查询角色列表
	Route::get('roleGets', 'RoleController@roleGets')->name('admin.role.rolegets');
	
	// 测试excelExport
	Route::get('excelExport', 'RoleController@excelExport')->name('admin.role.excelexport');
	
});


// PermissionController路由
Route::group(['prefix'=>'permission', 'namespace'=>'Admin', 'middleware'=>['jwtauth','permission:permission_admin_permission|permission_super_admin']], function() {

	// 显示permission页面
	Route::get('permissionIndex', 'PermissionController@permissionIndex')->name('admin.permission.index');

	// 角色列表
	Route::get('permissionGets', 'PermissionController@permissionGets')->name('admin.permission.permissiongets');

	// 创建permission
	Route::post('permissionCreate', 'PermissionController@permissionCreate')->name('admin.permission.create');

	// 编辑permission
	Route::post('permissionUpdate', 'PermissionController@permissionUpdate')->name('admin.permission.update');
	
	// 删除permission
	Route::post('permissionDelete', 'PermissionController@permissionDelete')->name('admin.permission.permissiondelete');

	// 赋予permission
	Route::post('permissionGive', 'PermissionController@permissionGive')->name('admin.permission.give');
	// 移除permission
	Route::post('permissionRemove', 'PermissionController@permissionRemove')->name('admin.permission.remove');

	// 列出当前角色拥有的权限
	Route::get('roleHasPermission', 'PermissionController@roleHasPermission')->name('admin.permission.rolehaspermission');

	// 更新当前角色的权限
	Route::post('roleUpdatePermission', 'PermissionController@roleUpdatePermission')->name('admin.permission.roleupdatepermission');
	
	// 列出所有待删除的权限
	Route::get('permissionListDelete', 'PermissionController@permissionListDelete')->name('admin.permission.permissionlistdelete');

	// 列出所有权限
	Route::get('permissionList', 'PermissionController@permissionList')->name('admin.permission.permissionlist');

	// 根据权限查看哪些角色
	Route::get('permissionToViewRole', 'PermissionController@permissionToViewRole')->name('admin.permission.permissiontoviewrole');

	// 角色同步到指定权限
	Route::post('testUsersPermission', 'PermissionController@testUsersPermission')->name('admin.permission.testuserspermission');
	
	// 测试excelExport
	Route::get('excelExport', 'PermissionController@excelExport')->name('admin.permission.excelexport');

	// 列出所有角色
	Route::get('roleList', 'PermissionController@roleList')->name('admin.permission.rolelist');

	// 列出所有用户
	Route::get('userList', 'PermissionController@userList')->name('admin.permission.userlist');
	
});


// 测试用
// Route::group(['prefix'=>'test', 'namespace'=>'Test', 'middleware'=>['jwtauth','permission:permission_super_admin']], function() {
Route::group(['prefix'=>'test', 'namespace'=>'Test', 'middleware'=>[]], function() {
	Route::get('test', 'testController@test');
	Route::get('phpinfo', 'testController@phpinfo');
	Route::get('ldap', 'testController@ldap');
	Route::get('scroll', 'testController@scroll');
	Route::get('mint', 'testController@mint');
	Route::get('muse', 'testController@muse');
	Route::get('vant', 'testController@vant');
	Route::get('cube', 'testController@cube');
	Route::get('pgsql', 'testController@pgsql');

	// 测试camera
	Route::get('camera', 'testController@camera');
	Route::post('testCamera', 'testController@testCamera')->name('test.camera.testcamera');

	// 测试邮件
	Route::get('mail', 'testController@mail');

	// 测试echarts
	Route::get('echarts', 'testController@echarts');
});
