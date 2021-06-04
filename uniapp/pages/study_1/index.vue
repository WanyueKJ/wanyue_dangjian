<template>
	<view>
		<view class="content">
			<seeList :list="list"></seeList>
		</view>
		
	</view>
</template>

<script>
	import seeList from '@/components/see-list/see-list.vue';
	import request from '@/util/request.js';
	const app = getApp();
	export default {
		components:{
			seeList
		},
		data() {
			return {
				list:[],//列表
				page:1,//分页
				isBottom:false,//有没有到底部
			}
		},
		onLoad(){
			var url = app.globalData.site_url+'/appapi/?s=Home.Windowlist';
			request.requestApi(url,{}).then(res=>{
				this.list = res.data.info[0].window;
			})
		},
		onReachBottom(){
			var _this = this;
		
			var p = this.page+1;
			this.page = p;
			
			var url1 = app.globalData.site_url+'/appapi/?s=Home.Windowlist';
			request.requestApi(url1,{p:p}).then(res1=>{
				if(res1.data.info[0].window.length<10){
					_this.isBottom = true;
				}
				var list = this.list;
				for(var i=0;i<res1.data.info[0].window.length;i++){
					list.push(res1.data.info[0].window[i]);
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
