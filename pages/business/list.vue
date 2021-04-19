<template>
	<view class="content">
		<view class="navbar" :style="{position:headerPosition,top:headerTop}">
			<view class="nav-item" :class="{current: filterIndex === 0}" @click="tabClick(0)">
				综合排序
			</view>
			<view class="nav-item" :class="{current: filterIndex === 1}" @click="tabClick(1)">
				销量优先
			</view>
			<view class="nav-item" :class="{current: filterIndex === 2}" @click="tabClick(2)">
				好评优先
			</view>
		</view>
		<view class="business-list">
			<view 
				v-for="(item, index) in businessList" :key="index"
				class="business-item"
				@click="navToDetailPage(item)">
				<view class="image-wrapper">
					<image :src="item.image" mode="aspectFill"></image>
				</view>
				<view class="business-content">
					<text class="title clamp">{{item.name}}</text>
					<text class="time_limit">营业时间：<text>{{item.time_limit}}</text></text>
					<view>
						<text class="sales_num">销量：<text>{{item.number}}</text></text>
						<text class="score">好评率：<text>{{item.score}}</text></text>
					</view>					
					<text class="status">营业中</text>
				</view>				
			</view>
		</view>
		<uni-load-more :status="loadingType"></uni-load-more>
		
	</view>
</template>

<script>
	var app = require("../../common/common.js");
	import uniLoadMore from '@/components/uni-load-more/uni-load-more.vue';
	export default {
		components: {
			uniLoadMore	
		},
		data() {
			return {
				headerPosition:"fixed",
				headerTop:"0px",
				loadingType: 'more', //加载更多状态
				filterIndex: 0, 
				priceOrder: 0, //1 价格从低到高 2价格从高到低
				page: 0,
				page_count: 1,
				businessList: []
			};
		},
		
		onLoad(options){
			var cid = options.cid;	//分类id
			// #ifdef H5
			this.headerTop = document.getElementsByTagName('uni-page-head')[0].offsetHeight+'px';
			// #endif
			this.loadData();
		},
		onPageScroll(e){
			//兼容iOS端下拉时顶部漂移
			if(e.scrollTop>=0){
				this.headerPosition = "fixed";
			}else{
				this.headerPosition = "absolute";
			}
		},
		//下拉刷新
		onPullDownRefresh(){
			this.loadData('refresh');
		},
		//加载更多
		onReachBottom(){
			this.loadData();
		},
		methods: {
			//加载商品 ，带下拉刷新和上滑加载
			async loadData(type='add', loading) {
				var that = this;		
				//判断是否还有下一页，没有是nomore，没有更多直接返回
				that.loadingType  = that.page >= that.page_count ? 'nomore' : 'more';
				if(type === 'add'){
					if(that.loadingType === 'nomore'){
						return;
					}
					that.page += 1;
				}else if(type === 'refresh') {
					that.businessList = [];
					that.page = 1;
				}
				
				let businessList = that.businessList;
				var sortBy = '';
				var sortType = '';
				if(that.filterIndex === 1) {
					sortBy = 'number';
					sortType = 'DESC';
				}else if(that.filterIndex === 2) {
					sortBy = 'score';
					sortType = 'DESC';
				}				
				
				uni.request({
					url: app.apiHost + "business/get_list",
					method: 'GET',
					data:{page:that.page,sortBy:sortBy,sortType:sortType},
					success: function (res) {
						if(res.data.data.list.length > 0){
							that.businessList = businessList.concat(res.data.data.list);
							that.page_count = res.data.data.page_count;
						}else{
							that.loadingType = 'nomore';
						}
					}
				})
				
				if(type === 'refresh'){
					if(loading === 1){
						uni.hideLoading();
					}else{
						uni.stopPullDownRefresh();
					}
				}
			},
			//筛选点击
			tabClick(index){
				var that = this;
				if(that.filterIndex === index && index !== 2){
					return;
				}
				that.filterIndex = index;
				uni.pageScrollTo({
					duration: 300,
					scrollTop: 0
				})
				that.loadData('refresh', 1);
				uni.showLoading({
					title: '正在加载...'
				})
			},
			//详情
			navToDetailPage(item){
				//测试数据没有写id，用title代替
				let id = item.id;
				uni.navigateTo({
					url: '/pages/business/business?id='+id
				})
			},
			stopPrevent(){}
		},
	}
</script>

<style lang="scss">
	page, .content{
		background: #fff;
	}
	.content{
		padding-top: 96rpx;
	}

	.navbar{
		position: fixed;
		left: 0;
		top: var(--window-top);
		display: flex;
		width: 100%;
		height: 80rpx;
		background: #fff;
		box-shadow: 0 2rpx 10rpx rgba(0,0,0,.06);
		z-index: 10;
		.nav-item{
			flex: 1;
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100%;
			font-size: 30rpx;
			color: $font-color-dark;
			position: relative;
			&.current{
				color: $base-color;
				&:after{
					content: '';
					position: absolute;
					left: 50%;
					bottom: 0;
					transform: translateX(-50%);
					width: 120rpx;
					height: 0;
					border-bottom: 4rpx solid $base-color;
				}
			}
		}
		.p-box{
			display: flex;
			flex-direction: column;
			.yticon{
				display: flex;
				align-items: center;
				justify-content: center;
				width: 30rpx;
				height: 14rpx;
				line-height: 1;
				margin-left: 4rpx;
				font-size: 26rpx;
				color: #888;
				&.active{
					color: $base-color;
				}
			}
			.xia{
				transform: scaleY(-1);
			}
		}
	}

	/* 店家列表 */
	.business-list{
		display:flex;
		flex-wrap:wrap;
		background: #f5f5f5;
		.business-item{
			background-color: #FFFFFF;
			width: 100%;
			padding: 0rpx 2% 40rpx 2%;
			overflow: hidden;
		}
		.image-wrapper{
			float: left;
			width: 30%;
			height: 30vw;
			border-radius: 3px;
			overflow: hidden;
			image{
				width: 100%;
				height: 100%;
				opacity: 1;
			}
		}
		.business-content {
			float: left;
			width: 66%;
			padding-left: 2%;
			color: #999999;
			font-size: 26rpx;
			.title {
				font-size: 36rpx;
				color: #333333;
				line-height: 80rpx;
			}
			.time_limit {
				line-height: 50rpx;
			}
			.sales_num {
				line-height: 50rpx;
				text {
					color: #f94e4e;
				}
			}
			.score {
				padding-left: 20%;
				text {
					color: #f94e4e;
				}
			}
			.status {
				line-height: 50rpx;
			}
		}	
	}
	

</style>
