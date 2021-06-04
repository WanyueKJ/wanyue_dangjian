<template>
	<view>
		<view class="deme-content">
			<view class="deme-content-li">
				<image class="deme-content-li-img" :src="info.thumb">
				<view class="deme-content-li-right">
					<view class="deme-content-li-right-name">{{info.name}}</view>
					<view class="deme-content-li-right-type">{{info.branch}} | {{info.post}}</view>
				</view>
			</view>	
			
			<view class="deme-text">个人简介</view>
			<view class="deme-content-li-right-des">{{info.content}}</view>
		</view>
	</view>
</template>

<script>
	import request from '@/util/request.js';
	const app = getApp();
	export default {
		data() {
			return {
				info:[],//详细信息
			}
		},
		onLoad(options){

			var url = app.globalData.site_url+'/appapi/?s=Home.Getpartershow';
			request.requestApi(url,{id:options.id}).then(res=>{
				this.info = res.data.info[0].data;
			})
		},
		methods: {
			
		}
	}
</script>

<style>
	.deme-text{
		margin-top: 34rpx;
		font-size: 34rpx;
		font-weight: 500;
	}
	.deme-content{
		width: 92%;
		margin: 0 auto;
	}
	.deme-content-li-right-des{
	    font-size: 28rpx;
	    color: #646464;
	    letter-spacing: 1rpx;
	    line-height: 40rpx;
	    margin-top: 24rpx;
	}
	.deme-content-li-right-type{
		font-size: 28rpx;
		color: #646464;
		margin-top: 20rpx;
		letter-spacing: 1rpx;
	}
	.deme-content-li-right-name{
	    font-size: 34rpx;
	    font-weight: 500;
	}
	.deme-content-li-right{
	    width: 70%;
	    float: right;
	}
	.deme-content-li-img{
	    float: left;
	    width: 27%;
	    height: 230rpx;
	}
	.deme-content-li{
	    clear: both;
	    overflow: hidden;
	    padding: 36rpx 0 36rpx 0;
	    border-bottom: 1rpx solid #F0F0F0;
	}
</style>
