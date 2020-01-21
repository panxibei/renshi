<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
@routes
<title>
@section('my_title')
{{$config['SITE_TITLE']}}  Ver: {{$config['SITE_VERSION']}}
@show
</title>
<link rel="stylesheet" href="{{ asset('statics/cube/cube.min.css') }}">
<style type="text/css">
	/* 解决闪烁问题的CSS */
	[v-cloak] {	display: none; }
</style>
@yield('my_style')
<script src="{{ asset('js/functions.js') }}"></script>
@yield('my_js')
</head>
<body>
<div id="app" v-cloak>

    <Layout>
        @section('my_logo_and_title')
        <Header>
            <!-- 头部 -->
            <!-- /头部 -->
        </Header>
        @show

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
        <a href="{{route('portal')}}">{{$config['SITE_TITLE']}}</a>
        {{$config['SITE_COPYRIGHT']}}
        @show
        </Footer>
        <!-- /底部 -->
    </Footer>


</div>
<script src="{{ asset('js/vue.min.js') }}"></script>
<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="{{ asset('js/bluebird.min.js') }}"></script>
<script src="{{ asset('statics/cube/cube.min.js') }}"></script>
<script src="{{ asset('statics/echarts/echarts.min.js') }}"></script>
@section('my_js_others')
<script>

</script>
@show
</body>
</html>