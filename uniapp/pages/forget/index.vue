<template>
	<view>
		<form @submit="doForget">
			<view class="reg-li">
				<view class="reg-li-left">登录密码</view>
				<input class="reg-li-input" value="" name="login_pass" placeholder="请输入您的密码(必填)" placeholder-class="reg-li-input-pl" />
			</view>
			<view class="reg-li">
				<view class="reg-li-left">确认密码</view>
				<input class="reg-li-input" value="" name="login_pass1" placeholder="请确认您的密码(必填)" placeholder-class="reg-li-input-pl" />
			</view>
			
			<button class="reg-button" form-type="submit">立即修改</button>
		</form>

	</view>
</template>

<script>
	//公共方法
	import request from '@/util/request.js';
	const app = getApp();
	export default {
		data() {
			return {
				codeText:'获取验证码',
				phone:'',//手机号
				mac:'',
			}
		},
		onLoad(){

		},
		methods: {
			changePhone(e){ //输入手机号
				this.phone = e.detail.value;
			},
			doForget(e){ //修改密码
				var user_login = e.detail.value.phone;
				var user_pass = e.detail.value.login_pass;
				var user_pass2 = e.detail.value.login_pass1;
				var code = e.detail.value.code;
				var mac = this.mac;

				if(user_login==''){
					uni.showToast({
						title:'请填写手机号',
						icon:'none'
					})
					return ;
				}
				if(user_pass==''){
					uni.showToast({
						title:'请填写密码',
						icon:'none'
					})
					return ;
				}
				if(user_pass2==''){
					uni.showToast({
						title:'请填写确认密码',
						icon:'none'
					})
					return ;
				}
				
				var url = app.globalData.site_url+'/appapi/?s=Login.UserFindPass';
				var data = {
					uid: app.globalData.userInfo.id,
					token: app.globalData.userInfo.token,
					user_login:user_login,
					user_pass:user_pass,
					user_pass2:user_pass2,
					mac:mac,
				};
				request.requestApi(url,data).then(res=>{
					if(res.data.code==0){
						uni.showToast({
							icon: 'none',
							title: '修改成功'
						});
						setTimeout(function(){
							uni.navigateBack({
								delta:1
							})
						},1500)

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
	.reg-button{
		padding: 0;
		margin: 0;
		width: 81%;
		margin: 0 auto;
		border-radius: 64rpx;
		line-height: 82rpx;
		color: #fff;
		background: linear-gradient(to bottom, #FF9000, #F64330);
		font-size: 30rpx;
		letter-spacing: 2rpx;
		margin-top: 100rpx;
	}
	.reg-li-getcode{
		color: #C9C9C9;
		font-size: 30rpx;
	}
	.reg-li-input-pl{
		color: #C9C9C9;
		font-size: 30rpx;
	}
	.reg-li-input{
		width: 70%;
		float: left;
	}
	.reg-li{
		width: 84%;
		margin: 0 auto;
		clear: both;
		overflow: hidden;
		padding: 40rpx 0 40rpx 0;
		border-bottom: 1px solid #E8E8E8;
	}
	.reg-li-left{
		float: left;
		width: 30%;
	}
</style>
