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
				hid: 0, //党建历史id
				historyList: [],
			}
		},
		onLoad(option){
			if(option.id != undefined) {
				this.hid = option.id;
				this.getKnowledgeById(option.id);
			}
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
			
		},
		methods: {
			getKnowledgeById(hid) {
				
				let that = this;
				uni.request({
					url: app.globalData.site_url+'/appapi/?s=Home.GetKnowledgeById',
					method: 'GET',
					data: {
						'hid' : hid
					},
					success: res => {
						if(res.data.data.code == 0) {
							that.list = res.data.data.info[0];
						}
					},
					fail: () => {
						uni.showToast({
							icon: 'none',
							title: '网络错误,请稍后再试'
						});
					},
				});
			}	
		}
		
		
	}
</script>

<style>
	.content{
		width: 94%;
		margin: 0 auto;
	}
</style>
