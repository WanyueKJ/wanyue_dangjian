<template>
	<view>
		<view class="history-list">
			<articleList :list="list"></articleList>
		</view>
		
		
		<view v-if="list.length>0" class="logout" @click="deleteList">清除记录</view>
	</view>
</template>

<script>
	import articleList from '@/components/article-list/article-list.vue';
	import request from '@/util/request.js';
	const app = getApp();
	export default {
		components:{
			articleList
		},
		data() {
			return {
				list:[],//文章列表
				page:1,//分页
				isBottom:false,//有没有到底部
			}
		},
		onLoad(){
			var uid = app.globalData.userInfo.id;
			var token = app.globalData.userInfo.token;
			var url = app.globalData.site_url+'/appapi/?s=User.History';
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
			var uid = app.globalData.userInfo.id;
			var token = app.globalData.userInfo.token;
			
			var url1 = app.globalData.site_url+'/appapi/?s=User.History';
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
			deleteList(){ //清除记录
				var uid = app.globalData.userInfo.id;
				var token = app.globalData.userInfo.token;
				var url1 = app.globalData.site_url+'/appapi/?s=User.Cleanhistory';
				request.requestApi(url1,{uid:uid,token:token}).then(res1=>{
					if(res1.data.code==0){
						this.list = [];
					}
					uni.showToast({
						title:res1.data.msg,
						icon:'none'
					})
				})
			}
		}
	}
</script>

<style>
	.logout{
		width: 210rpx;
		height: 80rpx;
		text-align: center;
		margin: 0 auto;
		margin-top: 120rpx;
		color: #B4B4B4;
		border-radius: 60rpx;
		font-size: 28rpx;
		line-height: 80rpx;
		border: 1rpx solid #B4B4B4;
	}
	.history-list{
		width: 94%;
		margin: 0 auto;
	}
</style>
