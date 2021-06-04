<template>
	<view>
		<block v-for="(item,index) in list">
			<view class="message-date">{{item.addtime}}</view>
			<view class="message-text">{{item.conotent}}</view>
		</block>

	</view>
</template>

<script>
	import request from '@/util/request.js';
	const app = getApp();
	export default {
		data() {
			return {
				page:1,//
				list:[],//
				isBottom:false,//有没有到底部
			}
		},
		onLoad(){
			var uid = app.globalData.userInfo.id;
			var token = app.globalData.userInfo.token;
			var url = app.globalData.site_url+'/appapi/?s=User.Msg';
			request.requestApi(url,{uid:uid,token:token,p:1}).then(res=>{
				this.list = res.data.info[0].list;
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
			
			var url1 = app.globalData.site_url+'/appapi/?s=User.Msg';
			request.requestApi(url1,{uid:uid,token:token,p:p}).then(res1=>{
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
			
		}
	}
</script>

<style>
	.message-text{
	    border: 1rpx solid #F0F0F0;
	    width: 85%;
	    margin: 0 auto;
	    border-radius: 10rpx;
	    padding: 20rpx;
	    font-size: 28rpx;
	    color: #646464;
	    line-height: 44rpx;
	    letter-spacing: 1rpx;
	}
	.message-date{
	    text-align: center;
	    padding: 40rpx 0 40rpx 0;
	    color: #969696;
	    font-size: 24rpx;
	}
</style>
