<template>
	<view v-if="configInfo.small_switch ==0" style="position: fixed;top: 0;bottom: 0;right:0;left: 0;background-image: url('http://dj.sdwanyue.com/uniapp/login_bg.png');background-size: 100% 100%;">
		
		<image class="login-logo" :style="{'margin-top':navHeight}" src="../../static/images/login_logo_wanyue.png" mode=""></image>
		<image v-if="configInfo.small_switch == 0" class="login-logo-text" src="/static/images/login_text.png"></image>
		
			<form @submit="doLogin">
				<input class="login-input" @input="changePhone" type="number" value="" placeholder-class="login-input-pl" placeholder="请输入手机号" name="login_name" />
				
				<view class="login-input-view">
					<input style="margin-top: 30rpx;width: 55%;float: left;" class="login-input" type="number" value="" placeholder-class="login-input-pl" placeholder="请输入验证码" name="login_pass" />
					<text class="login-input-code" @click="getCode">{{codeText}}</text>
				</view>
				
				<button form-type="submit" class="login-button">注册/登录</button>
			</form>
			
	</view>
	<view v-else-if="configInfo.small_switch ==1" style="position: fixed;top: 0;bottom: 0;right:0;left: 0;background-image: url('http://dj.sdwanyue.com/uniapp/login_bg1.png');background-size: 100% 100%;">
		
		<image class="login-logo" :style="{'margin-top':navHeight}" src="../../static/images/login_logo_wanyue.png" mode=""></image>
		<image v-if="configInfo.small_switch == 0" class="login-logo-text" src="/static/images/login_text.png"></image>	
		<form @submit="doLogin">
			<input class="login-input" @input="changePhone" type="number" value="" placeholder-class="login-input-pl" placeholder="请输入手机号" name="login_name" />
			
			<view class="login-input-view">
				<input style="margin-top: 30rpx;width: 55%;float: left;" class="login-input" type="number" value="" placeholder-class="login-input-pl" placeholder="请输入验证码" name="login_pass" />
				<text class="login-input-code" @click="getCode">{{codeText}}</text>
			</view>
			
			<button form-type="submit" class="login-button">注册/登录</button>
		</form>
		
	</view>
</template>

<script>
	import request from '@/util/request.js';
	const app = getApp();
	export default {
		data() {
			return {
				navHeight:0,//导航栏高度
				margin_top:'',
				codeText:'获取验证码',
				phone:'',
				mac:'',
				configInfo:[],
			}
		},
		onLoad(){
			this.navHeight = app.globalData.navHeight+'rpx';
			
			var url = app.globalData.site_url+'/appapi/?s=Home.GetConfig';
			request.requestApi(url,{}).then(res=>{
				this.configInfo = res.data.info[0];
			})
		},
		methods: {
			changePhone(e){ //输入手机号
				this.phone = e.detail.value;
			},
			getCode(){ //获取验证码
				if(this.phone.length!=11){
					uni.showToast({
						title:'请输入正确手机号',
						icon:'none'
					})
					
					return ;
				}
				this.codeText = '重新获取';
				
				var url = app.globalData.site_url+'/appapi/?s=Login.GetCode';
				var data = {mobile:this.phone};
				request.requestApi(url,data).then(res=>{
					
					if(res.data.code==0){
						this.mac = res.data.info[0].mac
					}
					uni.showToast({
						title:res.data.msg,
						icon:'none'
					})
			
				})
			},
			//点击忘记密码
			goForget(){
				uni.navigateTo({
					url:'/pages/forget/index'
				})
			},
			//点击去注册
			goReg(){
				uni.navigateTo({
					url:'/pages/reg/index'
				})
			},
			//点击登录
			doLogin(e){
				
				let user_login = e.detail.value.login_name;
				let user_pass = e.detail.value.login_pass;
				if(user_login==''){
					uni.showToast({
						title:'请输入手机号',
						icon:'none'
					})
					return ;
				}
				
				if(user_login.length!=11){
					uni.showToast({
						title:'请输入正确手机号',
						icon:'none'
					})
					return ;
				}
				
				let url = app.globalData.site_url+'/appapi/?s=Login.UserLogin';
				let data = {
					user_login:user_login,
					user_pass:user_pass,
					mac:this.mac
				};
				request.requestApi(url,data).then(res=>{
					if(res.data.code==0){
						app.globalData.login_score = res.data.info[0].login_score;
						app.globalData.first_login = res.data.info[0].first_login;
						
						uni.setStorage({
						  key:"userInfo",
						  data:res.data.info[0]
						})
						app.globalData.userInfo = res.data.info[0];
						
						uni.switchTab({
							url:'/pages/index/index'
						});

					}else{
						uni.showToast({
							title:res.data.msg,
							icon:'none'
						})
					}
					
				})
				
			}
		}
	}
</script>

<style>
	.login-input-code{
	    float: right;
	    margin-top: 58rpx;
	    color: rgba(255,255,255,0.7);
	    border-bottom: 2rpx solid rgba(255,255,255,0.5);
	    display: block;
	    margin-right: 20rpx;
	    padding-bottom: 10rpx;
	}
	.login-input-view{
		width: 81%;
		margin: 0 auto;
		clear: both;
		overflow: hidden;
	}
	.login-bottom-right{
		float: right;
		color: #FFFFFF;
	}
	.login-bottom-left{
		float: left;
	}
	.login-bottom{
		width: 64%;
		margin: 0 auto;
		clear: both;
		overflow: hidden;
		color: #FFFFFF;
		font-size: 32rpx;
		margin-top: 40rpx;
	}
	.login-button{
		padding: 0;
		margin: 0;
		width: 81%;
		margin: 0 auto;
		border-radius: 64rpx;
		line-height: 90rpx;
		color: #fff;
		background: linear-gradient(to bottom, #FF9000, #F64330);
		font-size: 32rpx;
		letter-spacing: 2rpx;
		margin-top: 60rpx;
	}
	.login-input{
		width: 75%;
		margin: 0 auto;
		border: 1px solid rgba(255,255,255,0.5);
		border-radius: 53rpx;
		height: 80rpx;
		line-height: 80rpx;
		display: block;
		padding-left: 6%;
		margin-top: 104rpx;
		color: #fff;
		background-color: rgba(0,0,0,0.1);
	}
	.login-input-pl{
		color: rgba(255,255,255,0.7);
	}

	.login-logo{
		width: 180rpx;
		height: 130rpx;
		margin: 0 auto;
		display: block;
	}
	.login-logo-name{
		width: 198rpx;
		height: 32rpx;
		display: block;
		margin: 0 auto;
		margin-top: 20rpx;
	}
	.login-logo-text{
		width: 431rpx;
		height: 47rpx;
		display: block;
		margin: 0 auto;
		margin-top: 64rpx;
	}
	
	button::after {
		  border: none;
	}
	
</style>
