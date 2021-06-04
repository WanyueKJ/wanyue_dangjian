<template>
	<view>
		<view :style="{'height':navHeight}" class="nav-title-top">
			<image v-if="configInfo.small_switch == 0" class="nav-title-top-logo" src="/static/images/nav-logo.png"></image>
			<image v-if="configInfo.small_switch == 1" style="width: 142rpx;height: 66rpx;" class="nav-title-top-logo" src="/static/images/nav-logo.png"></image>
			<text class="nav-title-top-title">{{title}}</text>
		</view>
		
	</view>
</template>

<script>
	import request from '@/util/request.js';
	const app = getApp();
	export default {
		components: {
		},
		//属性
		props: {
			title: {
				type: String,//属性类型
				value: "",
			},
		},
		created(){
			this.navHeight = app.globalData.navHeight+'rpx';
			
			var url = app.globalData.site_url+'/appapi/?s=Home.GetConfig';
			request.requestApi(url,{}).then(res=>{
				this.configInfo = res.data.info[0];
			})
		},
		data() {
			return {
				navHeight:0,//导航栏高度
				configInfo:[]
			}
		},
		methods: {
			
		}
	}
</script>

<style>
	.nav-title-top-title{
		position: relative;
		top: 42%;
		transform: translateY(-50%);
		font-weight: 700;
		font-size: 17px !important;
		color: #000000;
	}
	.nav-title-top{
		height: 68px;
		clear: both;
		overflow: hidden;
		background-color: #fff;
		text-align: center;
		font-size: 32rpx;
		color: #000;
		position: relative;
	}
	.nav-title-top-logo{
		width: 266rpx;
		height: 61rpx;
		position: absolute;
		left: 30rpx;
		top: 60%;
		transform: translateY(-50%);
	}
</style>
