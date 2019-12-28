<template>
	<div>
	
		<Modal ref="ref1" v-model="vm_app.modal_camera_show" title="拍照 - 请允许开启访问摄像头的权限" width="360">
			<div style="text-align:center">
			<p>

				<div class="camera">
					<video id="video">Video stream not available.</video>
					<button id="startbutton">点击拍照</button>
				</div>

				<br>

				<canvas id="canvas"></canvas>

				<br>

				<div class="output">
					<img id="photo" :src="imgUrl" alt="">
					<br>
					* 注意事项：请允许开启访问摄像头的权限。<br>如果无法显示图像，请刷新页面重试。
				</div>

			</p>
			</div>	
		</Modal>

	</div>
</template>
 
<script>
	module.exports = {
		data: function () {
			return {
				// camera's png url
				imgUrl: '',

			}
		},
		methods: {
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

			alert_logout () {
				this.error(false, '会话超时', '会话超时，请重新登录！');
				window.setTimeout(function(){
					window.location.href = "{{ route('portal') }}";
				}, 2000);
				return false;
			},



		}
	}
</script>

<style scoped>

</style>