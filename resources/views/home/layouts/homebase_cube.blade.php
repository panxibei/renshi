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
{{$SITE_TITLE}}  Ver: {{$SITE_VERSION}}
@show
</title>
<link rel="stylesheet" href="{{ asset('statics/cube/cube.min.css') }}">
<style type="text/css">
	/* 解决闪烁问题的CSS */
	[v-cloak] {	display: none; }
</style>
<style type="text/css">
.header {
	position: relative;
	height: 44px;
	line-height: 44px;
	text-align: center;
	/* background-color: #edf0f4; */
	/* box-shadow: 0 1px 6px #ccc; */
	-webkit-backface-visibility: hidden;
	backface-visibility: hidden;
	z-index: 5;
}

</style>
@yield('my_style')
<script src="{{ asset('js/functions.js') }}"></script>
@yield('my_js')
</head>
<body>
<div id="app" v-cloak>

    <Layout>
        <!-- 头部 -->
        <br><br><br>
        @section('my_logo_and_title')
        <Header class="header">
        <h1>
            {{$SITE_TITLE}}
            <br>
            <small style="{line-height:11px;font-size:11px;color:#93999f;}">
                {{$SITE_VERSION}}
            </small>
        </h1>
        </Header>
        <br><br><br><br><br><br>
        @show
        <!-- /头部 -->

        <Content>
        <!-- 主体 -->
        @section('my_body')
        @show
        <!-- /主体 -->
        </Content>
    </Layout>

    <Footer>
        <!-- 底部 -->
        <Footer style="{position: relative;text-align: center;}">
        @section('my_footer')
        <br>
        <a href="{{route('portal')}}">{{$SITE_TITLE}}</a>
        {{$SITE_COPYRIGHT}}
        @show
        </Footer>
        <!-- /底部 -->
    </Footer>

	
</div>
<script src="{{ asset('js/vue.min.js') }}"></script>
<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="{{ asset('js/bluebird.min.js') }}"></script>
<script src="{{ asset('statics/cube/cube.min.js') }}"></script>
@section('my_js_others')
<script>

</script>
@show
</body>
</html>