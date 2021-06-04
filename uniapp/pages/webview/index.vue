<template>
	<view>
		<web-view :src="url"></web-view>
	</view>
</template>

<script>
	const app = getApp();
	export default {
		data() {
			return {
				url:''
			}
		},
		onLoad(options){
			var uid = app.globalData.userInfo.id;
			var token = app.globalData.userInfo.token;
			this.url = options.url+'?uid='+uid+'&token='+token;
		},
		methods: {
			
		}
	}
</script>

<style>

</style>
