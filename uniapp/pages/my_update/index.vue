<template>
	<view>
		<view class="my_update-top">
			<view class="avata_wrap" @click="uploadAvatar">
				<image class="my_update-top-avatar" :src="userInfo.avatar" mode="aspectFill"></image>
				<view class="my_update-top-text">点击更换头像</view>
			</view>
		</view>
		
		<form @submit="submit">
			<view class="my_update-li">
				<view class="my_update-li-name">姓名</view>
				<input class="my_update-li-input" type="text" :value="userInfo.user_nickname" name="name" />
			</view>
			
			<button class="submit" form-type="submit">保存</button>
		</form>
	</view>
</template>

<script>
	import request from '@/util/request.js';
	const app = getApp();
	export default {
		data() {
			return {
				list:[],//党支部
				list_key:[],
				list1:[],//党内职务
				list1_key:[],
				userInfo:{},//用户信息
			}
		},
		onLoad(){
			
			var userInfo = app.globalData.userInfo;
			if(userInfo.id){
				var url = app.globalData.site_url+'/appapi/?s=User.GetBaseInfo';
				request.requestApi(url,{uid:userInfo.id,token:userInfo.token}).then(res=>{
					if(res.data.code!=0){
						uni.showToast({
							title:res.data.msg
						})
						
						setTimeout(function(){
							uni.redirectTo({
								url:'/pages/login/index'
							})
						},1500)
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
			uploadAvatar(){ //上传头像
				var uid = app.globalData.userInfo.id;
				var token = app.globalData.userInfo.token;
				var _this = this;
				uni.chooseImage({
				  count: 1,  //最多可以选择的图片总数  
				  success: function (res) {
					//启动上传等待中...  
					uni.showLoading({
					  title: '头像上传中',
					});
					uni.uploadFile({
					  url: app.globalData.site_url+'/appapi/?s=Update.UpdateAvatar',
					  filePath: res.tempFilePaths[0],
					  name: 'file',
					  formData: {
						'filename': 'file',
						uid:uid,
						token:token
					  },
					  success: function (res) {
						  var res = JSON.parse(res.data);
						  if(res.data.code == 700){
							  uni.showToast({
							  	title:res.data.msg,
								icon:'none'
							  })
							  
							  setTimeout(function(){
								  uni.redirectTo({
								  	url:'/pages/login/index'
								  })
							  },1500)
						  }
						  
						  if(res.data.code==0){
							  _this.userInfo.avatar = res.data.info[0].url;
							  uni.showToast({
							  	title:res.data.msg
							  })
						  }else{
							  uni.showToast({
							  	title:res.data.msg,
								icon:'none'
							  })
						  }
					  }
					})
				  }
				})
			},
			submit(e){ //保存信息
				var uid = this.userInfo.id;
				var token = app.globalData.userInfo.token;
				var user_nickname = e.detail.value.name;
				var avatar = this.userInfo.avatar;
				var partybranch = this.userInfo.partybranch;
				var partypost = this.userInfo.partypost;
				
				var fields = {};
				fields.user_nickname = user_nickname;
				fields.avatar = avatar;
				fields.partybranch = partybranch;
				fields.partypost = partypost;
				
				fields = JSON.stringify(fields);
				var url = app.globalData.site_url+'/appapi/?s=User.UpdateFields';
				
				request.requestApi(url,{uid:uid,token:token,fields:fields}).then(res=>{
					if(res.data.code!=0){
						uni.showToast({
							title:res.data.msg,
							icon:'none'
						})
						
					}else{
						uni.showToast({
							title:res.data.msg,
							icon:'none'
						})
						setTimeout(function(){
							uni.navigateBack({
								delta:1
							})
						},1500)

					}
				})
			},
			partyChange(e){ //选择党支部
				this.userInfo.partybranch = this.list_key[e.detail.value];
				this.userInfo.branch = this.list[e.detail.value];
			},
			partyChange1(e){ //选择党内职务
				this.userInfo.partypost = this.list1_key[e.detail.value];
				this.userInfo.post = this.list1[e.detail.value];
			},
		}
	}
</script>

<style>
	
	.submit{
		margin-top: 176rpx;
		padding: 0;
		width: 80%;
		border: 0;
		color: #fff;
		background: linear-gradient(to bottom, #FF9000, #F64330);
		border-radius: 68rpx;
		line-height: 80rpx;
		font-size: 30rpx;
	}
	.my_update-li-input{
	    float: left;
	    width: 70%;
	}
	.my_update-li-name{
	    float: left;
	    width: 25%;
	}
	.my_update-li{
	    width: 90%;
	    margin: 0 auto;
	    padding: 32rpx 0 32rpx 0;
	    border-bottom: 1rpx solid #E8E8E8;
	    clear: both;
	    overflow: hidden;
	}
	.my_update-top-text{
	    text-align: center;
	    font-size: 24rpx;
	    color: #969696;
	    margin-top: 30rpx; 
	}
	.my_update-top-avatar{
	    width: 110rpx;
	    height: 110rpx;
	    border-radius: 70rpx;
	    margin: 0 auto;
	    display: block;
	    margin-top: 100rpx;
	}
	.my_update-top{
	    clear: both;
	    overflow: hidden;
	    width: 90%;
	    margin: 0 auto;
	    padding-bottom: 70rpx;
	    border-bottom: 1rpx solid #E8E8E8;
	}
	
	.avata_wrap {
		width: 200rpx;
		margin: 0 auto;
	}
	
</style>
