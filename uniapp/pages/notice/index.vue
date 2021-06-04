<template>
	<view>
		<view class="deme-content">
			<block v-for="(item,index) in list" :key="index">
				<view class="deme-content-li" @click="goH5" :data-url="item.url">
					<view class="deme-content-li-right">
						<view class="deme-content-li-right-des">{{item.title}}</view>
						<view class="deme-content-li-right-date">{{item.addtime}}</view>
					</view>
				</view>				
			</block>
		
		</view>
	</view>
</template>

<script>
	import request from '@/util/request.js';
	const app = getApp();
	export default {
		data() {
			return {
				list:[],
				page:1,//
				isBottom:false,//有没有到底部
			}
		},
		onLoad(){
			var url = app.globalData.site_url+'/appapi/?s=Home.Notice';
			request.requestApi(url,{}).then(res=>{
				this.list = res.data.info[0].list
			})
		},
		onReachBottom(){
			var _this = this;
			if(this.isBottom==true){
				uni.showToast({
					title:'已经到底部了',
					icon:'none'
				})
				return ;
			}
			var p = this.page+1;
			this.page = p;
			
			var url1 = app.globalData.site_url+'/appapi/?s=Home.Notice';
			request.requestApi(url1,{listid:id,p:p}).then(res1=>{
				if(res1.data.info[0].list.length<10){
					_this.isBottom = true;
				}
				var list = this.list;
				for(var i=0;i<res1.data.info[0].list.length;i++){
					list.push(res1.data.info[0][i]);
				}
				this.list = list;
			})
		},
		methods: {
			goH5(e){//去h5页面
				// uni.navigateTo({
				// 	url:'/pages/webview/index?url='+e.currentTarget.dataset.url
				// })
			}
		}
	}
</script>

<style>
	.deme-content-li-right{
		float: left;
		width: 100%;
	}
	.heng{
		background: #FAFAFA;
		width: 100%;
		height: 10rpx;
	}
	.deme-content-li-right-date{
		color: #969696;
		font-size: 28rpx;
		margin-top: 26rpx;
	}
	.deme-content-li-right-des{
		font-size: 34rpx;
		letter-spacing: 1rpx;
/* 		overflow: hidden;
		-webkit-line-clamp: 1;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-box-orient: vertical; */
		font-weight: 500;
	}
	.deme-content-li{
	    clear: both;
	    overflow: hidden;
	    padding: 36rpx 0 36rpx 0;
	    width: 94%;
	    margin: 0 auto;
	    border-bottom: 1rpx solid #F0F0F0;
	}
	.deme-content{
		background: #fff;
	}
</style>
