<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
	<title>test camera</title>
    <link rel="stylesheet" href="{{ asset('statics/iview/styles/iview.css') }}">
    <link rel="stylesheet" href="{{ asset('css/camera.css') }}">
    <link rel="stylesheet" href="{{ asset('statics/print/print.min.css') }}">
    
</head>
<body>

<div id="app" class="contentarea">

<form method="post" action="#" id="printJS-form">
    aaaaaaaaaaaaaaaaa
 </form>

    <br>
    <button @click="printJS('app', 'html')">Print</button>
    <br>
    <br>
    必须允许
    <br>

    <button id="startcapture" @click="vm_app.modal_camera_show=true">Start Up (permit camera)</button>
    <br><br>

    imgUrl:<br>
    @{{ camera_imgurl.substr(0, 40) }}
    <br>
    <button @click="submitpic">Submit Pic</button>


    <br><br>

    pgsql:<br>
    @foreach($data1 as $value)
        <img src="{{ $value }}" width="320px" height="240px">
    @endforeach

    <br><br>



    <br><br>


    <my-passwordchange></my-passwordchange>

    <my-camera></my-camera>


<br><br>

fafdasf
<br><br>

</div>




</body>
<script src="{{ asset('js/vue.min.js') }}"></script>
<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="{{ asset('js/bluebird.min.js') }}"></script>
<script src="{{ asset('statics/iview/iview.min.js') }}"></script>
<script src="{{ asset('js/camera.js') }}"></script>
<script src="{{ asset('js/httpVueLoader.js') }}"></script>
<script src="{{ asset('statics/print/print.min.js') }}"></script>

<script type="text/javascript">
var vm_app = new Vue({
    el: '#app',
	components: {
		'my-passwordchange': httpVueLoader("{{ asset('components/my-passwordchange.vue') }}"),
		'my-camera': httpVueLoader("{{ asset('components/my-camera.vue') }}")
	},
	data: {

        modal_password_edit: false,
        
        modal_camera_show: false,
        camera_imgurl: '',


        // imgsrc: 'data:image/png;base64,R0lGODlhWAAfAJEAAAAAAP////8AAGZmZiH5BAAHAP8ALAAAAABYAB8AAALfhI+py+0PX5i02ouz3rxn44XiSHJgiaYqdq7uK7bwTFtyjcN3zqd7D4wBgkTSr4i87AQCCrPCfFoG1IGIWqtabUOLNPCVfgNY8rZTzp4py+Yk7B6nL9pp3T6f3O3KLrQJ6OYlqLdGUTaHuKZYwcjHdfEUOEh4aGiW4Vi4acnZeNkGBlb5Ror5mbmVqLrISgfq9zcqO4uxmup5enuKChk56RRHqKnbmktMnDvxI1kZRbpnmZccXTittXaUtB2gzY3k/U0ULg5EXs5zjo6jvk7T7q4TGz8+T28eka+/zx9RAAA7',
        imgsrc: '',
    },
	methods: {

        submitpic() {
			var _this = this;
			var imgurl = _this.camera_imgurl;
			// var imgurl = 'data:image/png;base64,R0lGODlhWAAfAJEAAAAAAP////8AAGZmZiH5BAAHAP8ALAAAAABYAB8AAALfhI+py+0PX5i02ouz3rxn44XiSHJgiaYqdq7uK7bwTFtyjcN3zqd7D4wBgkTSr4i87AQCCrPCfFoG1IGIWqtabUOLNPCVfgNY8rZTzp4py+Yk7B6nL9pp3T6f3O3KLrQJ6OYlqLdGUTaHuKZYwcjHdfEUOEh4aGiW4Vi4acnZeNkGBlb5Ror5mbmVqLrISgfq9zcqO4uxmup5enuKChk56RRHqKnbmktMnDvxI1kZRbpnmZccXTittXaUtB2gzY3k/U0ULg5EXs5zjo6jvk7T7q4TGz8+T28eka+/zx9RAAA7';
            // console.log(imgurl);return false;
			
			var url = "{{ route('test.camera.testcamera') }}";
			axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
			axios.post(url,{
				id: 2,
				imgurl: imgurl
			})
			.then(function (response) {
                // console.log(response.data);return false;

				if (response.data) {
                    console.log('成功');
                    alert('成功');
				} else {
                    console.log('失败');
                    alert('失败');
				}
			})
			.catch(function (error) {
                console.log(error);
                alert('error');
			})
        },


        printContent() {
            // document.getElementById('printBtn').style.display="none";
            // let subOputputRankPrint = document.getElementById("invoiceBox");
            let newContent = document.getElementById("app");

            document.body.innerHTML = newContent;
            window.print();
        }

	},
	mounted: function () {

	}
})
</script>
</html>