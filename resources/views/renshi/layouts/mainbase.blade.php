<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<title>
@section('my_title')
{{$config['SITE_TITLE']}}  Ver: {{$config['SITE_VERSION']}}
@show
</title>
<link rel="stylesheet" href="{{ asset('statics/iview/styles/iview.css') }}">
<style type="text/css">
	/* 解决闪烁问题的CSS */
	[v-cloak] {	display: none; }
</style>
<style type="text/css">
.layout{
    border: 1px solid #d7dde4;
    background: #f5f7f9;
    position: relative;
    border-radius: 4px;
    overflow: hidden;
}
.layout-header-bar{
	background: #fff;
	box-shadow: 0 1px 1px rgba(0,0,0,.1);
}
.layout-logo{
    width: 100px;
    height: 30px;
    <!--background: #5b6270;-->
    border-radius: 3px;
    float: left;
    position: relative;
    top: 15px;
    left: 20px;
}
.layout-breadcrumb{
	<!-- padding: 10px 15px 0; -->
    width: 100px;
    height: 30px;
    <!--background: #5b6270;-->
    border-radius: 3px;
    float: left;
    position: relative;
    top: 5px;
    left: 20px;
}
.layout-nav{
	float: right;
	position: relative;
    <!--width: 420px;-->
    margin: 0 auto;
    margin-right: 210px;
}
.layout-footer-center{
    text-align: center;
}
/* 穿梭框 */
.ivu-transfer-list{
	height: 320px;
	width: 260px;
}
</style>
@yield('my_style')
<script src="{{ asset('js/functions.js') }}"></script>
@yield('my_js')
</head>
<body>
<div id="app" v-cloak>
    <div class="layout">
        <Layout>
			<Layout>
            <!--头部导航-->
			<div style="z-index: 999;">
            <Header :style="{position: 'fixed', width: '100%', marginLeft: '200px'}">
                <Layout>
				<i-menu mode="horizontal" theme="light" active-name="3" @on-select="name=>topmenuselect(name)">
                    <!--<div class="layout-logo">qqqqqqqqqqqq</div>-->
					
					<!--面包屑-->
					<div class="layout-breadcrumb">
						<Breadcrumb>
							<Breadcrumb-item to="{{route('renshi.jiaban.todo')}}">系统首页</Breadcrumb-item>
							<Breadcrumb-item to="#">@{{ current_nav }}</Breadcrumb-item>
							<Breadcrumb-item>@{{ current_subnav }}</Breadcrumb-item>
						</Breadcrumb>
					</div>
					
					<!--头部导航菜单-->
                    <div class="layout-nav">
						<!--Item 1-->
                        <Menu-item name="1">
							<Badge dot :offset="[20, 0]">
								<Icon type="ios-mail" size="20"/>
							</Badge>
                            
                        </Menu-item>
						<!--Item 2-->
                        <Menu-item name="2">
							<Dropdown @click.native="event => dropdownuser(event.target.innerText.trim())">
								<Badge dot :offset="[20, 0]">
									<Icon type="ios-document" size="20"/>
								</Badge>
								<Dropdown-menu slot="list" style="width: 260px">
									<Dropdown-item>
									<strong>Task: xxxxx1</strong>
										<i-progress :percent="55" status="active"></i-progress>
									</Dropdown-item>
									<Dropdown-item divided>
									<strong>Task: xxxxx2</strong>
										<i-progress :percent="55" status="active"></i-progress>
									</Dropdown-item>
									<Dropdown-item divided>
									<strong>Task: xxxxx3</strong>
										<i-progress :percent="55" status="active"></i-progress>
									</Dropdown-item>
								</Dropdown-menu>
							</Dropdown>
                        </Menu-item>
						<!--Item 3-->
						<Submenu name="3">
							<template slot="title">
								<Icon type="ios-person"></Icon>{{ $user['displayname'] ?? 'Unknown User'}}
							</template>
							<!--
							<Menu-Group title="使用">
								<Menu-Item name="3-1">新增和启动</Menu-Item>
								<Menu-Item name="3-2">活跃分析</Menu-Item>
								<Menu-Item name="3-3">时段分析</Menu-Item>
							</Menu-Group>
							-->
							<Menu-Item name="3-1"><Icon type="ios-create-outline"></Icon>修改密码</Menu-Item>
							<Menu-Item name="3-2"><Icon type="ios-exit-outline"></Icon>退出登录</Menu-Item>
						</Submenu>
                    </div>
                </i-menu>
				</Layout>

				<!--上部标签组-->
				<Layout :style="{padding: '0 2px', marginLeft: '10px'}">
					<div>
						@section('my_tag')
						
						<!--
						<Tag type="dot">标签一</Tag>
						<Tag type="dot" closable>标签三</Tag>
						<Tag v-if="show" @on-close="handleClose" type="dot" closable color="blue">可关闭标签</Tag>
						-->
						@show
					</div>
				</Layout>
            </Header>
			</div>
			</Layout>

            <Layout>
                <!--左侧导航菜单-->
				<Sider hide-trigger :style="{background: '#fff', position: 'fixed', height: '100vh', left: 0, overflow: 'auto'}">
					<div style="height: 60px;">
						<div class="layout-logo"><a href="{{route('admin.config.index')}}">{{$config['SITE_TITLE']}} 后台管理</a></div>
					</div>
					<div id="menu">
						<i-menu :active-name="sideractivename" theme="light" width="auto" :open-names="sideropennames" @on-select="name=>navmenuselect(name)" accordion>

							<Submenu name="1">
									<template slot="title">
											<Icon type="ios-key"></Icon> 加班管理
									</template>
									<Menu-item name="1-1"><Icon type="ios-person"></Icon> 申请</Menu-item>
									<Menu-item name="1-2"><Icon type="ios-person"></Icon> 处理</Menu-item>
									<Menu-item name="1-3"><Icon type="ios-people"></Icon> 归档</Menu-item>
							</Submenu>
		
						</i-menu>
					</div>
                </Sider>
			</Layout>
			
			<div><br><br><br><br></div>
			<Layout :style="{padding: '0 12px 24px', marginLeft: '200px'}">
				<!--内容主体-->
				<Content :style="{padding: '0px 12px', minHeight: '280px', background: '#fff'}">
				<!-- 主体 -->
				@section('my_body')
				@show
				<!-- /主体 -->

				</Content>
			</Layout>

 			<!-- 底部 -->
			<Footer class="layout-footer-center">
			@section('my_footer')
			<a href="{{route('portal')}}">{{$config['SITE_TITLE']}}</a>&nbsp;&nbsp;{{$config['SITE_COPYRIGHT']}}
			@can('permission_super_admin')
				<a href="{{route('admin.config.index')}}"><Icon type="ios-cog-outline"></Icon></a>
			@endcan
			
			@show
			</Footer>
			<!-- /底部 -->
			
        </Layout>
		<!-- 返回顶部 -->
		<Back-top></Back-top>
    </div>
</div>

<script src="{{ asset('js/vue.min.js') }}"></script>
<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="{{ asset('js/bluebird.min.js') }}"></script>
<script src="{{ asset('statics/iview/iview.min.js') }}"></script>
@section('my_js_others')
<script>
function navmenuselect (name) {
	switch(name)
	{
	case '1-1':
	  window.location.href = "{{route('renshi.jiaban.applicant')}}";
	  break;
	case '1-2':
	  window.location.href = "{{route('renshi.jiaban.todo')}}";
	  break;

	case '2-1-1':
	  window.location.href = "";
	  break;
	case '2-1-2':
	  window.location.href = "";
	  break;
	case '2-1-3':
	  window.location.href = "";
	  break;

	case '2-2-1':
	  window.location.href = "";
	  break;
	case '2-2-2':
	  window.location.href = "";
	  break;

	case '2-3-1':
	  window.location.href = "";
	  break;
	case '2-3-2':
	  window.location.href = "";
	  break;
	case '2-3-3':
	  window.location.href = "";
	  break;

	case '3-1':
	  window.location.href = "{{route('admin.user.index')}}";
	  break;
	case '3-2':
	  window.location.href = "{{route('admin.role.index')}}";
	  break;
	case '3-3':
	  window.location.href = "{{route('admin.permission.index')}}";
	  break;

	}
}

function topmenuselect (name) {
	switch(name)
	{
	case '1-1':
	  window.location.href = "";
	  break;
	case '1-2':
	  window.location.href = "";
	  break;

	case '2-1-1':
	  window.location.href = "";
	  break;
	case '2-1-2':
	  window.location.href = "";
	  break;
	case '2-1-3':
	  window.location.href = "";
	  break;

	case '2-2-1':
	  window.location.href = "";
	  break;
	case '2-2-2':
	  window.location.href = "";
	  break;

	case '2-3-1':
	  window.location.href = "";
	  break;
	case '2-3-2':
	  window.location.href = "";
	  break;
	case '2-3-3':
	  window.location.href = "";
	  break;

	case '3-1':
	  window.location.href = "";
	  break;
	case '3-2':
	  window.location.href = "{{route('admin.logout')}}";
	  break;
	case '3-3':
	  window.location.href = "";
	  break;

	}
}
</script>
@show
</body>
</html>
