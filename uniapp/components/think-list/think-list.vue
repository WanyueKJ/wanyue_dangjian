<template>
	<view>
		<block v-for="(item,index) in list">
			<view class="think-li" @click="goH5" :data-url="item.url">
				<view class="think-li-title">
					{{item.title}}
					<text v-if="item.status==0" class="think-li-status">审核中</text>
					<text v-if="item.status==2" class="think-li-status">审核失败</text>
				</view>
				<view class="think-li-bottom">
					<image class="think-li-bottom-avatar" :src="item.avatar"></image>
					<text class="think-li-bottom-name">{{item.user_nickname}} | {{item.branch}}</text>
					<text class="think-li-bottom-mes">{{item.count}}阅读 {{item.zan}}点赞</text>
				</view>
			</view>
		</block>
	</view>
</template>

<script>
	export default {
		props:{
			list:{
				type:Array,
				value:[]
			}
		},
		data() {
			return {
				
			}
		},
		methods: {
			goH5(e){
				uni.navigateTo({
					url:'/pages/webview/index?url='+e.currentTarget.dataset.url
				})
			}
		}
	}
</script>

<style>
	.think-li-status{
		color: red;
		border: 1rpx solid red;
		font-size: 22rpx;
		padding: 0rpx 4rpx 0rpx 4rpx;
		margin-left: 10rpx;
	}
	.think-li-bottom-mes{
	    color: #B4B4B4;
	    font-size: 26rpx;
	    float: right;
	    margin-top: 12rpx;
	}
	.think-li-bottom-name{
	    font-size: 28rpx;
	    color: #646464;
	    position: relative;
	    bottom: 16rpx;
	    left: 16rpx;
	}
	.think-li-bottom-avatar{
		width: 60rpx;
		height: 60rpx;
		border-radius: 50rpx;
	}
	.think-li-bottom{
	    clear: both;
	    overflow: hidden;
	    margin-top: 24rpx;
	}
	.think-li-title{
	    font-size: 34rpx;
	    letter-spacing: 1rpx;
	    font-weight: 500;
	}
	.think-li{
	    width: 94%;
	    margin: 0 auto;
	    padding: 30rpx 0 20rpx 0;
	    border-bottom: 1rpx solid #F0F0F0;
	}
</style>
