<template>
	<view>
		<!-- #ifndef H5 -->
		<nav-title title="我的"></nav-title>
		<!-- #endif -->
		
		<view class="my-top" @click="goUpdate">
			<image class="my-top-avatar" :src="userInfo.avatar"></image>
			<view class="my-top-mes">
				<view class="my-top-mes-name">{{userInfo.user_nickname}}</view>
				<view class="my-top-mes-type"></view>
			</view>
			<image class="my-top-zhe" src="/static/images/zhe.png"></image>
		</view>
		
		<view class="heng"></view>
		
		<view class="my-li" @click="goHistory">
			<image class="my-li-img" src="/static/images/my3.png"></image>
			<text class="my-li-text">浏览历史</text>
			<image class="my-li-zhe" src="/static/images/zhe.png"></image>
		</view>
		<view class="my-li" @click="goMessage">
			<image class="my-li-img" src="/static/images/my4.png"></image>
			<text class="my-li-text">消息通知</text>
			<image class="my-li-zhe" src="/static/images/zhe.png"></image>
		</view>
		<view class="my-li" @click="resetPass">
			<image class="my-li-img" src="/static/images/xiugai_icon.png"></image>
			<text class="my-li-text">修改密码</text>
			<image class="my-li-zhe" src="/static/images/zhe.png"></image>
		</view>
		
		<view class="logout" @click="logOut">退出登录</view>
	</view>
</template>

<script>
	import navTitle from '@/components/nav-title/nav-title.vue';
	import request from '@/util/request.js';
	const app = getApp();
	export default {
		components:{
			navTitle
		},
		data() {
			return {
				userInfo:{}
			}
		},
		onShow(){
			var userInfo = app.globalData.userInfo;
			if(userInfo.id){
				var url = app.globalData.site_url+'/appapi/?s=User.GetBaseInfo';
				request.requestApi(url,{uid:userInfo.id,token:userInfo.token}).then(res=>{
					if(res.data.code!=0){
						uni.showToast({
							title:res.data.msg,
							icon:'none'
						})
						
						if(res.data.code == 700) {
							setTimeout(function(){
								uni.redirectTo({
									url:'/pages/login/index'
								})
							},1500);
						}
						
					}else{
						this.userInfo = res.data.info[0].userinfo;
					}
				})
			}else{
				uni.redirectTo({
					url:'/pages/login/index'
				})
			}

		},
		methods: {
			
			// 重置密码
			resetPass(){
				uni.navigateTo({
					url: '../forget/index',
				});
			},
			logOut(){ //退出登录
				uni.removeStorage({
				  key: 'userInfo',
				})
				app.globalData.userInfo = {};
				
				uni.redirectTo({
					url:'/pages/login/index'
				})
			},
			goMessage(){ //消息通知
				uni.navigateTo({
					url:'/pages/notice/index'
				})
			},
			goHistory(){ //浏览历史
				uni.navigateTo({
					url:'/pages/my_history/index'
				})
			},
			goCollection(){ //我的收藏
				uni.navigateTo({
					url:'/pages/my_collection/index'
				})
			},
			goThink(){ //我发表的
				uni.navigateTo({
					url:'/pages/my_think/index'
				})
			},
			goUpdate(){ //去修改用户信息
				uni.navigateTo({
					url:'/pages/my_update/index'
				})
			}
		}
	}
</script>

<style>
	.logout{
		width: 42%;
		height: 90rpx;
		text-align: center;
		margin: 0 auto;
		margin-top: 120rpx;
		color: #B4B4B4;
		background: rgba(180,180,180,0.1);
		border-radius: 60rpx;
		font-size: 30rpx;
		line-height: 90rpx;
	}
	.my-li-zhe{
	    width: 30rpx;
	    height: 30rpx;
	    float: right;
	    margin-top: 10rpx;
	}
	.my-li-text{
		font-size: 30rpx;
	    margin-left: 20rpx;
	    letter-spacing: 1rpx;
	    position: relative;
	    bottom: 5rpx;
	}
	.my-li-img{
	    width: 32rpx;
	    height: 32rpx;
	}
	.my-li{
	    width: 92%;
	    margin: 0 auto;
	    padding: 30rpx 0 30rpx 0;
	    border-bottom: 1rpx solid #F0F0F0;
	}
	.heng{
		background: #FAFAFA;
		width: 100%;
		height: 10rpx;
	}
	.my-top-zhe{
		width: 30rpx;
		height: 30rpx;
		float: right;
		margin-top: 22rpx;
		margin-right: 4%;
	}
	.my-top-mes-type{
	    font-size: 22rpx;
	    margin-top: 10rpx;
	    color: #969696;
	}
	.my-top-mes-name{
	    font-size: 30rpx;
	    letter-spacing: 1rpx;
	    font-weight: 500;
	}
	.my-top{
	    clear: both;
	    overflow: hidden;
	    padding: 50rpx 0 50rpx 0;
	}
	.my-top-avatar{
	    float: left;
	    width: 110rpx;
	    height: 110rpx;
	    border-radius: 100rpx;
	    margin-left: 4%;
	}
	.my-top-mes{
	    float: left;
	    margin-left: 3%;
	    margin-top: 12rpx;
	}
</style>
