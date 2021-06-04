<template>
	<view>
		<!-- #ifndef H5 -->
		<nav-title title="首页"></nav-title>
		<!-- #endif -->
		
		<view class="index-swiper">
			<swiper class="index-swiper-swiper" indicator-active-color="#ffffff" indicator-dots="true" autoplay="true" loop="true" circular="true">
				<block v-for="(item,index) in slideList" :key="index">
					<swiper-item>
						<image @click="bannerTo(item, index)" class="index-swiper-swiper-img" :src="item.image"></image>
					</swiper-item>
				</block>
			</swiper>
		</view>
		
		<view class="index-gao">
		  <image src="/static/images/gonggao.png" class="index-gao-text"></image>
		  <swiper class="index-gao-swiper" autoplay="true" interval="1500" circular="true" duration="500" vertical="true" display-multiple-items="1">
			<block v-for="(item,index) in notice" :key="id">
			  <swiper-item>
				<view @click="goNotice" class="index-gao-swiper-text">{{item.title}}</view>
			  </swiper-item>
			</block>
		  </swiper>
		</view>
		
		<view class="index-article">
			<view class="index-article-top">
				<text class="index-article-top-shu"></text>
				<text class="index-article-top-text">推荐文章</text>
				<image class="index-article-top-zhe" src="/static/images/zhe.png"></image>
				<view class="index-article-top-more" @click="goArticle">更多</view>
			</view>
			<view class="index-article-list">
				<articleList :list="list"></articleList>
			</view>
		</view>
	</view>
</template>

<script>
	import navTitle from '@/components/nav-title/nav-title.vue';
	import articleList from '@/components/article-list/article-list.vue';
	import request from '@/util/request.js';

	const app = getApp();
	export default {
		
		components:{
			navTitle,
			articleList,
		},
		data() {
			return {
				articleList:[],//文章列表
				slideList:[],//轮播
				notice:[],
				configInfo:[],
				list: [], //要闻速览
				duwu_name: '', //有声读物名
			}
		},
		onShareTimeline: function(res) {},
		onShareAppMessage: function(res) {},
		onLoad() {
		},
		onShow(){
			
			var _this = this;
			var url = app.globalData.site_url+'/appapi/?s=Home.Getindex';
			
			uni.request({
			  url: url,
			  success: (res) => {
				this.list = res.data.data.info[0].list;
				this.slideList = res.data.data.info[0].slide;
				this.duwu_name = res.data.data.info[0].duwu_name;
			  },
			  fail: (msg) => {
				  
			  }
			});
			
			var url1 = app.globalData.site_url+'/appapi/?s=Home.Notice';
			request.requestApi(url1,{}).then(res=>{
				_this.notice = res.data.info[0].list;
			});
			
			var url = app.globalData.site_url+'/appapi/?s=Home.GetConfig';
			request.requestApi(url,{}).then(res=>{
				this.configInfo = res.data.info[0];
			});
			
		},
		
		methods: {
			bannerTo(item, index) {
				
			},
			goScanning(){ //要闻速览
				uni.navigateTo({
					url:'/pages/scanning/index'
				});
			},
			goNotice(){ //通知公告
				uni.navigateTo({
					url:'/pages/notice/index'
				});
			},
			goArticle(){ //查看更多文章 改成要闻速览
				uni.navigateTo({
					url:'/pages/scanning/index'
				});
			}
		}
	}
</script>

<style>
	
	page{
		background: #FAFAFA;
		height: 100%;
	}
	
	.index-gao-swiper-text{
		width: 100%;
		overflow: hidden;
		-webkit-line-clamp: 1;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		font-size: 32rpx;
	}
	.index-gao-swiper{
		float: left;
		width: 70%;
		height: 40rpx;
		position: relative;
		bottom: 8rpx;
	}
	.index-gao-text{
		float: left;
		width: 133rpx;
		height: 30rpx;
		margin-right: 20rpx;
	}
	.index-gao{
	    background: #fff;
	    margin-top: 10rpx;
		padding: 20rpx 3% 10rpx 3%;
		clear: both;
		overflow: hidden;
	}
	.index-article-list{
		width: 94%;
		margin: 0 auto;
	}
	.index-article-top-more{
		float: right;
		color: #969696;
		font-size: 34rpx;
		margin-right: 15rpx;
	}
	.index-article-top-zhe{
	    float: right;
	    width: 30rpx;
	    height: 30rpx;
	    position: relative;
	    top: 8rpx;
	    margin-right: 3%;
	}
	.index-article-top-text{
	    font-size: 36rpx;
	    letter-spacing: 1rpx;
	    font-weight: 500;
	    position: relative;
	    left: 14rpx;
	    bottom: 7rpx;
	}
	.index-article-top-shu{
		display: inline-block;
	    width: 6rpx;
	    height: 36rpx;
	    background: linear-gradient(to bottom, #FF9000, #F64330);
	    margin-left: 3%;
	}
	.index-article-top{
	    clear: both;
	    overflow: hidden;
	    border-bottom: 1px solid #F0F0F0;
	    padding-top: 24rpx;
	    padding-bottom: 20rpx;
	}
	.index-article{
		background: #fff;
		margin-top: 10rpx;
	}
	
	.index-nav-li-text{
		margin-top: 14rpx;
		display: block;
	}
	.index-nav-li-img{
		width: 66rpx;
		height: 66rpx;
		display: block;
		margin: 0 auto;
	}
	.index-nav-li{
		width: 25%;
		text-align: center;
		font-size: 26rpx;
		color: #646464;
		letter-spacing: 1rpx;
		padding: 40rpx 0 0 0;
		float: left;
	}
	.index-nav{
		padding: 0 3% 0 3%;
		clear: both;
		overflow: hidden;
		padding-bottom: 40rpx;
		background: #fff;
	}
	.index-swiper{
		width: 100%;
		height: 360rpx;
	}
	.index-swiper-swiper{
		width: 100%;
		height: 100%;
	}
	.index-swiper-swiper-img{
		width: 100%;
		height: 100%;
	}
</style>
