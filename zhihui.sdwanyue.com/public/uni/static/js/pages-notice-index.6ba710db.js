(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-notice-index"],{"164c":function(t,e,i){var n=i("ec30");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("6b698012",n,!0,{sourceMap:!1,shadowMode:!1})},3288:function(t,e,i){"use strict";i.r(e);var n=i("60fe"),a=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);e["default"]=a.a},"60fe":function(t,e,i){"use strict";var n=i("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=n(i("dcd2")),o=getApp(),c={data:function(){return{list:[],page:1,isBottom:!1}},onLoad:function(){var t=this,e=o.globalData.site_url+"/appapi/?s=Home.Notice";a.default.requestApi(e,{}).then((function(e){t.list=e.data.info[0].list}))},onReachBottom:function(){var t=this,e=this;if(1!=this.isBottom){var i=this.page+1;this.page=i;var n=o.globalData.site_url+"/appapi/?s=Home.Notice";a.default.requestApi(n,{listid:id,p:i}).then((function(i){i.data.info[0].list.length<10&&(e.isBottom=!0);for(var n=t.list,a=0;a<i.data.info[0].list.length;a++)n.push(i.data.info[0][a]);t.list=n}))}else uni.showToast({title:"已经到底部了",icon:"none"})},methods:{goH5:function(t){}}};e.default=c},a028:function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return n}));var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",[i("v-uni-view",{staticClass:"deme-content"},[t._l(t.list,(function(e,n){return[i("v-uni-view",{key:n+"_0",staticClass:"deme-content-li",attrs:{"data-url":e.url},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.goH5.apply(void 0,arguments)}}},[i("v-uni-view",{staticClass:"deme-content-li-right"},[i("v-uni-view",{staticClass:"deme-content-li-right-des"},[t._v(t._s(e.title))]),i("v-uni-view",{staticClass:"deme-content-li-right-date"},[t._v(t._s(e.addtime))])],1)],1)]}))],2)],1)},o=[]},b050:function(t,e,i){"use strict";i.r(e);var n=i("a028"),a=i("3288");for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);i("d1c4");var c,s=i("f0c5"),r=Object(s["a"])(a["default"],n["b"],n["c"],!1,null,"58a6fc20",null,!1,n["a"],c);e["default"]=r.exports},d1c4:function(t,e,i){"use strict";var n=i("164c"),a=i.n(n);a.a},ec30:function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,".deme-content-li-right[data-v-58a6fc20]{float:left;width:100%}.heng[data-v-58a6fc20]{background:#fafafa;width:100%;height:%?10?%}.deme-content-li-right-date[data-v-58a6fc20]{color:#969696;font-size:%?28?%;margin-top:%?26?%}.deme-content-li-right-des[data-v-58a6fc20]{font-size:%?34?%;letter-spacing:%?1?%;\n/* \t\toverflow: hidden;\n\t\t-webkit-line-clamp: 1;\n\t\ttext-overflow: ellipsis;\n\t\tdisplay: -webkit-box;\n\t\t-webkit-box-orient: vertical; */font-weight:500}.deme-content-li[data-v-58a6fc20]{clear:both;overflow:hidden;padding:%?36?% 0 %?36?% 0;width:94%;margin:0 auto;border-bottom:%?1?% solid #f0f0f0}.deme-content[data-v-58a6fc20]{background:#fff}",""]),t.exports=e}}]);