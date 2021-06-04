<template>
	<view>
		<block v-for="(item,index) in list">
			<view class="study-li" @click="goH5" :data-index="index">
				<image class="study-li-img" :src="item.thumb">
				<image v-if="!item.url_a && (listType != '1')" class="study-li-play" src="/static/images/play.png">
				<view class="study-li-des">{{item.title}}</view>
				<view class="study-li-date">{{item.addtime}}</view>
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
			},
			listType: {
				type: String,
				value: ''
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
				} else if(this.listType == '1') {
					uni.navigateTo({
						url:'/pages/danghistory/index?id='+this.list[index].id
					})
				}
				else{
					uni.navigateTo({
						url:'/pages/reading_list/index?id='+this.list[index].id
					})
				}
			}
		}
	}
</script>

<style>
	.study-li-play{
	    position: absolute;
	    width: 68rpx;
	    height: 68rpx;
	    right: 40%;
	    top: 21%;
	}
	.study-li-date{
	    font-size: 28rpx;
	    color: #969696;
	}
	.study-li-des{
		letter-spacing: 1rpx;
		width: 100%;
		-webkit-line-clamp: 2;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		height: 92rpx;
		overflow: hidden;
		font-size: 32rpx;
	}
	.study-li-img{
	    width: 100%;
	    height: 230rpx;
	}
	.study-li{
	    width: 48.5%;
	    float: left;
	    margin-top: 20rpx;
		position: relative;
	}
	.study-li:nth-of-type(2n){
		margin-left: 3%;
	}
</style>
