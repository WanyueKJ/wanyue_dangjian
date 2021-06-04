<template>
	<view>
		<block v-for="(item,index) in list" :key="id">
			<view class="article-list-li" @click="goH5" :data-url="item.url">
				<image class="article-list-li-thumb" :src="item.thumb"></image>
				<view class="article-list-li-right">
					<view class="article-list-li-right-title">{{item.title}}</view>
					<view class="article-list-li-right-date">结束时间：{{item.endtime}}</view>
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
	.article-list-li-right-date{
		font-size: 28rpx;
		margin-top: 46rpx;
		color: #969696;
	}
	.article-list-li-right-title{
		font-size: 36rpx;
		letter-spacing: 1rpx;
		font-weight: 500;
		overflow: hidden;
		-webkit-line-clamp: 2;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		height: 94rpx;
	}
	.article-list-li-right{
		float: left;
		width: 64%;
		margin-left: 4%;
	}
	.article-list-li{
		clear: both;
		overflow: hidden;
		height: 170rpx;
		padding: 40rpx 0 40rpx 0;
		border-bottom: 1rpx solid #F0F0F0;
		width: 100%;
	}
	.article-list-li-thumb{
	    width: 32%;
	    height: 100%;
	    float: left;
	}
</style>
