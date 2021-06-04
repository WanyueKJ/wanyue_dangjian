<template>
	<view>
		<block v-for="(item,index) in list" :key="index">
			<view class="index-li" @click="goH5" :data-index="index">
				<view class="index-li-top">
					<text class="index-li-top-text">{{item.title}}</text>
					<image class="index-li-top-img" src="/static/images/zhe.png"></image>
				</view>
				<view class="index-li-date">{{item.addtime}}</view>
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
				var index = e.currentTarget.dataset.index;
				if(this.list[index].url_a){ //h5
					uni.navigateTo({
						url:'/pages/webview/index?url='+this.list[index].url_a
					})
				}else{
					uni.navigateTo({
						url:'/pages/webvideo/index?url='+this.list[index].url
					})
				}
			}
		}
	}
</script>

<style>
	.index-li{
		border-bottom: 1rpx solid #F0F0F0;
		padding: 30rpx 0 30rpx 0;
	}
	.index-li-top{
		clear: both;
		overflow: hidden;
		font-size: 38rpx;
	}
	.index-li-top-text{
	    width: 90%;
	    -webkit-line-clamp: 1;
	    text-overflow: ellipsis;
	    display: -webkit-inline-box;
	    -webkit-box-orient: vertical;
	    height: 55rpx;
	    overflow: hidden;
	}
	.index-li-top-img{
		width: 30rpx;
		height: 30rpx;
		float: right;
		margin-top: 10rpx;
	}
	.index-li-date{
	    margin-top: 14rpx;
	    font-size: 28rpx;
	    color: #969696;
	}
</style>
