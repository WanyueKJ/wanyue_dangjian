<template>
	<view>
		<view class="content">
			<studyList listType="1" :list="list"></studyList>
		</view>
	</view>
</template>

<script>
	import studyList from '@/components/study-list/study-list.vue';
	import request from '@/util/request.js';
	const app = getApp();
	export default {
		components:{
			studyList
		},
		data() {
			return {
				list:[],//
				page:1,//
				isBottom:false,//有没有到底部
			}
		},
		onLoad(){
			var url = app.globalData.site_url+'/appapi/?s=Home.DanghistoryList';
			request.requestApi(url,{}).then(res=>{
				this.list = res.data.info[0];
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
			
			var url1 = app.globalData.site_url+'/appapi/?s=Home.DanghistoryList';
			request.requestApi(url1,{p:p}).then(res1=>{
				if(res1.data.info[0].length<10){
					_this.isBottom = true;
				}
				var list = this.list;
				for(var i=0;i<res1.data.info[0].length;i++){
					list.push(res1.data.info[0]);
				}
				this.list = list;
			})
		},
		methods: {
			
		}
	}
</script>

<style>
	.content{
		width: 94%;
		margin: 0 auto;
	}
</style>
