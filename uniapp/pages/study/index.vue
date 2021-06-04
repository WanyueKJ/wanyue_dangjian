<template>
	<view>
		<!-- #ifndef H5 -->
		<navTitle title="学习手册"></navTitle>
		<!-- #endif -->
		
		<view class="index-article">
			<view class="index-article-top">
				<text class="index-article-top-shu"></text>
				<text v-if="configInfo.small_switch" class="index-article-top-text">党史知识</text>
				<image class="index-article-top-zhe" src="/static/images/zhe.png"></image>
				<view class="index-article-top-more" @click="goMoreDangshi">更多</view>
			</view>
			<view class="index-article-list">
				<studyList listType="1" :list="history"></studyList>
			</view>
		</view>
		
		<view class="heng"></view>
		
		<view class="index-article">
			<view class="index-article-top">
				<text class="index-article-top-shu"></text>
				<text class="index-article-top-text">{{lession_title}}</text>
				<image class="index-article-top-zhe" src="/static/images/zhe.png"></image>
				<view class="index-article-top-more" @click="goM1">更多</view>
			</view>
			<view class="index-article-list">
				<seeList :list="list"></seeList>	
			</view>
		</view>
				
	</view>
</template>

<script>
	import navTitle from '@/components/nav-title/nav-title.vue';
	import studyList from '@/components/study-list/study-list.vue';
	import seeList from '@/components/see-list/see-list.vue';
	
	import request from '@/util/request.js';
	const app = getApp();
	export default {
		components:{
			navTitle,
			studyList,
			seeList
		},
		onShow(){
		
			var url = app.globalData.site_url+'/appapi/?s=Home.Learnindex';
			request.requestApi(url,{}).then(res=>{
				this.list = res.data.info[0].window;
				this.list1 = res.data.info[0].lession;
				this.history = res.data.info[0].danghistory;
				this.lession_title = res.data.info[0].lession_title;
				this.listType = res.data.info[0].listtype;
				this.duwu_name = res.data.info[0].duwu_name;
			})
			
			var url = app.globalData.site_url+'/appapi/?s=Home.GetConfig';
			request.requestApi(url,{}).then(res=>{
				this.configInfo = res.data.info[0];
			})
		},
		data() {
			return {
				list:[],//建党之窗
				list1:[],//党课
				configInfo:[],
				lession_title: '', //上方标签标题
				history: [],
				listType: '0', 
				duwu_name: '',
			}
		},
		methods: {
			goM1(){ //建党窗
				uni.navigateTo({
					url:'/pages/study_1/index'
				})
			},
			goMoreDangshi(){ //党史
				uni.navigateTo({
					url: '/pages/danghistory/more',
				});
			}
		}
	}
</script>

<style>
	.heng{
		background: #FAFAFA;
		width: 100%;
		height: 10rpx;
		margin-top: 30rpx;
	}
	.index-article-list{
		width: 94%;
		margin: 0 auto;
	}
	.index-article-top-more{
		float: right;
		color: #969696;
		font-size: 32rpx;
		margin-right: 15rpx;
	}
	.index-article-top-zhe{
		float: right;
		width: 32rpx;
		height: 30rpx;
		position: relative;
		top: 6rpx;
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
		clear: both;
		overflow: hidden;
	}
</style>
