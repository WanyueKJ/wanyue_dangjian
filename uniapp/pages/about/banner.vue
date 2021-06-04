<template>
	<view>
		
	<!-- #ifndef H5 -->
	<!-- <uni-nav-bar :border="false" @clickLeft="back" left-icon="back" title="万岳教育">
		
	</uni-nav-bar> -->
	<!-- #endif -->
		
	<web-view :src="url"></web-view>
		
	</view>
</template>

<script>
	// import uniNavBar from '@/components/uni-ui/uni-nav-bar/uni-nav-bar.vue';
	
	export default{
		components: {
			// uniNavBar,
		},
		data(){
			return{
				url:''	
			}
		},
		onLoad(option) {
			
			this.url = JSON.parse(decodeURIComponent(option.url));
		},
		methods:{
			back(){
				uni.navigateBack({
					delta: 1
				});
			}
		}
	}
</script>

<style>
	
</style>
