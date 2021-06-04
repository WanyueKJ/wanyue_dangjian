<template>
	<view>
		<view class="deme-top">
			<picker :range="type_list" @change="onChange">
				<image class="deme-top-img" src="/static/images/san.png"></image>
				<text class="deme-top-text">{{d_name}}</text>
			</picker>
		</view>
		<view class="heng"></view>
		<view class="deme-content">
			<block v-for="(item,index) in list" :key="index">
				<view class="deme-content-li" @click="goDetail" :data-id="item.id">
					<image class="deme-content-li-img" :src="item.thumb">
					<view class="deme-content-li-right">
						<view class="deme-content-li-right-name">{{item.name}}</view>
						<view class="deme-content-li-right-type">{{item.branch}} | {{item.post}}</view>
						<view class="deme-content-li-right-des">个人简介：{{item.content}}</view>
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
				index:-1,//
				type_list:[],//类型
				type_list_id:[],//类型id
				list:[],//员
				page:1,//分页
				d_name:'',
				id:0,//id
				isBottom:false,//有没有到底部
			}
		},
		onLoad(){
			var url = app.globalData.site_url+'/appapi/?s=Home.Getparter';
			let that = this;
			request.requestApi(url,{branchid:0,p:1}).then(res=>{
				this.list = res.data.info[0];
				this.d_name = res.data.info[1];
			});
			
			var configInfo = app.globalData.configInfo;
			this.type_list_id = configInfo.partylist.partybranch.key;
			this.type_list = configInfo.partylist.partybranch.value;
		},
		onReachBottom(){
			var _this = this;
			
			var p = this.page+1;
			var id = this.id;
			this.page = p;
			
			var url = app.globalData.site_url+'/appapi/?s=Home.Getparter';
			request.requestApi(url,{branchid:id,p:p}).then(res1=>{
				if(res1.data.info[0].length<10){
					_this.isBottom = true;
				}
				var list = this.list;
				for(var i=0;i<res1.data.info[0].length;i++){
					list.push(res1.data.info[0][i]);
				}
				this.list = list;
			})
		},
		methods: {
			goDetail(e){
				uni.navigateTo({
					url:"/pages/demeanor_detail/index?id="+e.currentTarget.dataset.id
				})
			},
			onChange(e){ //选择部
				this.index = e.detail.value;
				var id = this.type_list_id[e.detail.value];
				this.id = id;
				this.d_name = this.type_list[e.detail.value];
				var url = app.globalData.site_url+'/appapi/?s=Home.Getparter';
				request.requestApi(url,{branchid:id,p:1}).then(res=>{
					this.list = res.data.info[0];
					this.page = 1;
				})
			}
		}
	}
</script>

<style>
	.heng{
		background: #FAFAFA;
		width: 100%;
		height: 10rpx;
	}
	.deme-content-li-right-des{
	    font-size: 26rpx;
	    color: #646464;
	    letter-spacing: 1rpx;
	    line-height: 40rpx;
	    height: 114rpx;
	    overflow: hidden;
	    margin-top: 24rpx;
		-webkit-line-clamp: 3;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-box-orient: vertical;
	}
	.deme-content-li-right-type{
		font-size: 26rpx;
		color: #646464;
		margin-top: 20rpx;
		letter-spacing: 1rpx;
	}
	.deme-content-li-right-name{
	    font-size: 32rpx;
	    font-weight: 500;
	}
	.deme-content-li-right{
	    width: 70%;
	    float: right;
	}
	.deme-content-li-img{
	    float: left;
	    width: 27%;
	    height: 230rpx;
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
	.deme-top-text{
		margin-left: 12rpx;
		font-size: 30rpx;
		position: relative;
		top: 4rpx;
	}
	.deme-top-img{
		width: 16rpx;
		height: 10rpx;
		margin-left: 3%;
		margin-top: 50rpx;
	}
	.deme-top{
	    width: 100%;
	    background: #fff;
	    height: 100rpx;
	}
</style>
