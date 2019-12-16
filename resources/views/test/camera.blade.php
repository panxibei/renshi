<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
	<title>test camera</title>
    <link rel="stylesheet" href="{{ asset('css/camera.css') }}">
    <script src="{{ asset('js/camera.js') }}"></script>
</head>
<body>

<div id="app" class="contentarea">

    <button id="startcapture">Start Up (permit camera)</button>
    <br>

    <div class="camera">
        <video id="video">Video stream not available.</video>
        <button id="startbutton">Take photo</button> 
    </div>

    <canvas id="canvas"></canvas>

    <div class="output">
        <!--<img id="photo" alt="The screen capture will appear in this box.">-->
        <!-- <img id="photo" :src="'data:image/png;base64,'+imgsrc"> -->
        <img id="photo" :src="imgsrc" />
        <br>
    </div>

    <br>
    <button @click="submitpic">Submit Pic</button>

</div>

<br><br>

pgsql:<br>
@foreach($data1 as $value)
    <!-- <img src="{{ $value }}"> -->
@endforeach


</body>
<script src="{{ asset('js/vue.min.js') }}"></script>
<script src="{{ asset('js/axios.min.js') }}"></script>

<script type="text/javascript">
var vm_app = new Vue({
	el: '#app',
	data: {

        // imgsrc: 'data:image/png;base64,R0lGODlhWAAfAJEAAAAAAP////8AAGZmZiH5BAAHAP8ALAAAAABYAB8AAALfhI+py+0PX5i02ouz3rxn44XiSHJgiaYqdq7uK7bwTFtyjcN3zqd7D4wBgkTSr4i87AQCCrPCfFoG1IGIWqtabUOLNPCVfgNY8rZTzp4py+Yk7B6nL9pp3T6f3O3KLrQJ6OYlqLdGUTaHuKZYwcjHdfEUOEh4aGiW4Vi4acnZeNkGBlb5Ror5mbmVqLrISgfq9zcqO4uxmup5enuKChk56RRHqKnbmktMnDvxI1kZRbpnmZccXTittXaUtB2gzY3k/U0ULg5EXs5zjo6jvk7T7q4TGz8+T28eka+/zx9RAAA7',
        imgsrc: '',
    },
	methods: {

        submitpic() {
			var _this = this;
			var imgsrc = _this.imgsrc;
            console.log(imgsrc);
            return false;
			
			// if (tableselect[0] == undefined) return false;
			
			var url = "{{ route('test.camera.testcamera') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url,{
				id: 2,
				imgsrc: imgsrc
			})
			.then(function (response) {
                console.log(response.data);return false;

				if (response.data) {
					console.log('成功');
				} else {
					console.log('失败');
				}
			})
			.catch(function (error) {
				console.log('错误');
			})
        },
	},
	mounted: function () {

	}
})
</script>
</html>