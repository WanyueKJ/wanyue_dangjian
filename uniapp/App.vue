<script>
	import request from '@/util/request.js';
	export default {
		globalData: {
			site_url:'https://zhihui.sdwanyue.com', //网站域名
			navHeight:0,//导航栏高度
			userInfo:'',//用户信息
			configInfo:[],//配置信息
			front_auth: [], // 前台用户权限
			login_score: 0,
			first_login: 0,
		},  
		onLaunch: function() {
			
			var _this = this;
			//获取本地用户缓存，看看是否登录
			uni.getStorage({
			  key: 'userInfo',
			  success (res) { //有本地缓存登录过
				_this.globalData.userInfo = res.data;
			  }
			})
			
			uni.getSystemInfo({ 
			  success: res => {
				//导航高度
				this.globalData.navHeight = res.statusBarHeight * (750 / res.windowWidth) + 97;
			  }, fail(err) {}
			});
		},
		onShow: function() {
			var url = this.globalData.site_url+'/appapi/?s=Home.GetConfig';
			request.requestApi(url,{}).then(res=>{
				this.globalData.configInfo = res.data.info[0];
			})
			
			
			const updateManager = uni.getUpdateManager();
		
			updateManager.onCheckForUpdate(function (res) {
			  // 请求完新版本信息的回调
		
			})
		
			updateManager.onUpdateReady(function () {
			  uni.showModal({
				title: '更新提示',
				content: '新版本已经准备好，是否重启应用？',
				success: function (res) {
				  if (res.confirm) {
					// 新的版本已经下载好，调用 applyUpdate 应用新版本并重启
					updateManager.applyUpdate()
				  }
				}
			  })
			});
		
			updateManager.onUpdateFailed(function () {
				uni.showToast({
				  title: '新版本下载失败',
				  icon:'none'
				})
			})
			
		},
		onHide: function() {
	
		}
	}
</script>

<style>
	/*每个页面公共css */
	@import url("/static/dj.css");
	
	/deep/.uni-scroll-view ::-webkit-scrollbar {
		/* 隐藏滚动条，但依旧具备可以滚动的功能 */
		display: none;
		width: 0;
		height: 0;
		color: transparent;
		background: transparent;
	}
	
	/deep/::-webkit-scrollbar {
		display: none;
		width: 0;
		height: 0;
		color: transparent;
		background: transparent;
	}
	
	/* 弹窗层级设为最高 */
	uni-toast{
		z-index: 99999;
	}
	
	uni-modal {
		z-index: 99999;
	}

	
</style>
