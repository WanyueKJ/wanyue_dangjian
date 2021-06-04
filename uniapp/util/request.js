
//请求接口
function requestApi(url,data){
  return new Promise((reslove, reject) => {
	uni.request({
	  url: url,
	  data: data,
	  success: (res) => {
		  if(res.data.data.code==700){
			  uni.showToast({
			  	title:res.data.data.msg,
				icon:'none'
			  })
			  
			  setTimeout(function(){
				uni.redirectTo({
					url:'/pages/login/index'
				})
			  },1500)
			  
			  return ;
		  }
		 reslove(res.data, res);
	  },
	  fail: (msg) => {
		reject('请求失败');
	  }
	})
  });
}


module.exports = {
	requestApi: requestApi
}