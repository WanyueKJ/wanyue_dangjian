<template>
	<view>
		<view class="nav-article">
			<articleList :list="list"></articleList>
		</view>
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
			var _this = this;
			var url1 = app.globalData.site_url+'/appapi/?s=Home.getScanning';
			request.requestApi(url1,{p:1}).then(res1=>{
				_this.list = res1.data.info;
			})
			
		},
		onReachBottom(){
			
			var _this = this;
			var p = this.page+1;
			var id = this.id;
			this.page = p;
			
			var url1 = app.globalData.site_url+'/appapi/?s=Home.getScanning';
			request.requestApi(url1,{p:p}).then(res1=>{
				if(res1.data.info.length<10){
					_this.isBottom = true;
				}
				var list = this.list;
				for(var i=0;i<res1.data.info.length;i++){
					list.push(res1.data.info[i]);
				}
				this.list = list;
			})
		},
		methods: {

		}
	}
</script>

<style>
	.nav-article{
	    width: 94%;
	    margin: 0 auto;
	}
	.nav{
		width: 100%;
		height: 54px;
	}
	.article-nav{
		white-space: nowrap;
		overflow: hidden;
		height: 54px;
	}
	.article-nav-li{
	    padding: 14rpx 20rpx 14rpx 20rpx;
	    border-radius: 6rpx;
	    background: #F5F5F5;
	    color: #787878;
	    margin-left: 3%;
	    font-size: 28rpx;
	    letter-spacing: 1rpx;
		display: inline-block;
		margin-top: 30rpx;
		white-space: normal;
	}
	.article-nav-li-select{
		background: linear-gradient(to bottom, #FF9000, #F64330);
		color: #fff;
	}
</style>
