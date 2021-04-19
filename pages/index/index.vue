<template>
	<view class="container">
		<view class="title-box">
			中夏教育
		</view>
		
		<view class="mp-search-box">
			<input class="ser-input" @click="choiceSchool" type="text" placeholder="输入关键字搜索"  />
		</view>
		
		<!-- 头部轮播 -->
		<view class="carousel-section">
			<!-- 标题栏和状态栏占位符 -->
			<view class="titleNview-placing"></view>
			<!-- 背景色区域 -->
			<view class="titleNview-background" :style="{backgroundColor:titleNViewBackground}"></view>
			<swiper class="carousel" :autoplay="true" :interval="4000" circular @change="swiperChange">
				<swiper-item v-for="(item, index) in carouselList" :key="index" class="carousel-item" @click="navToDetailPage({title: '轮播广告'})">
					<image :src="item.src" />
				</swiper-item>
			</swiper>
			<!-- 自定义swiper指示器 -->
			<view class="swiper-dots">
				<text class="num">{{swiperCurrent+1}}</text>
				<text class="sign">/</text>
				<text class="num">{{swiperLength}}</text>
			</view>
		</view>
		<!-- 分类 -->
		<view class="cate-section">
			<view class="cate-item" @click="catToList(1)">
				<image src="/static/temp/c1.png"></image>
				<text>活动</text>
			</view>
			<view class="cate-item" @click="catToList(2)">
				<image src="/static/temp/c4.png"></image>
				<text>报名</text>
			</view>
			<view class="cate-item" @click="catToList(3)">
				<image src="/static/temp/c7.png"></image>
				<text>缴费</text>
			</view>
		</view>
		
		<!-- 最新活动 -->
		<view class="f-header m-t">
			<view class="tit-box">
				<text class="tit">最新活动</text>
			</view>
		</view>
		
		<view class="business-section">
			<view v-for="(item, index) in businessList" :key="index"
				class="business-item"
				@click="navToDetailPage(item)">
				<view class="image-wrapper">
					<image :src="item.image" mode="aspectFill"></image>
				</view>
				<view class="business-content">
					<text class="title clamp">{{item.name}}</text>
					<!-- <text class="time_limit">营业时间：<text>{{item.time_limit}}</text></text>
					<view>
						<text class="sales_num">销量：<text>{{item.number}}</text></text>
						<text class="score">好评率：<text>{{item.score}}</text></text>
					</view>					
					<text class="status">{{item.status_name}}</text> -->
					<view>针对性教学，为您在假期快速提高，赢在起点，针对性教学，为您在假期快速提高，赢在起点</view>
				</view>				
			</view>
		</view>
		
	</view>
</template>

<script>
	var app = require("../../common/common.js");
	var jsonData = require('../../Json.js');
	export default {
		data() {
			return {
				titleNViewBackground: '',
				swiperCurrent: 0,
				swiperLength: 0,
				carouselList: [],
				businessList: [],				
			};
		},

		onLoad: function(option) {
			this.getIndexConfig();
			this.getBanner();
			this.getBusiness();
		},
		methods: {
			getBusiness: function () {
				var that = this;
				uni.request({
					url: app.apiHost + "business/get_list",
					method: 'GET',
					data:{},
					success: function (res) {
						that.businessList = res.data.data.list;
					}
				})
			},
			getBanner: function() {
				var that = this;
				that.carouselList = jsonData.default.carouselList;
				that.swiperLength = jsonData.default.carouselList.length;
			},
			//轮播图切换修改背景色
			swiperChange(e) {
				const index = e.detail.current;
				this.swiperCurrent = index;
				this.titleNViewBackground = this.carouselList[index].background;
			},
			//详情页
			navToDetailPage(item) {
				//测试数据没有写id，用title代替
				let id = item.title;
				uni.navigateTo({
					url: `/pages/product/product?id=${id}`
				})
			},
			getIndexConfig: function() {
				var that = this;
				uni.request({
					url: app.apiHost + "index/get_config",
					method: 'GET',
					data:{},
					success: function (res) {
						var resData = res.data.data;
						that.schoolName = resData.school_info.name;
					}
				})
			},			
			catToList: function(cid){
				uni.navigateTo({
					url: '/pages/business/list'
				})
			}
		}
	}
</script>

<style lang="scss">
	.title-box{
		height: 80rpx;
		background-color: #1bbc3d;
		overflow: hidden;
		padding: 20rpx;
		text-align: center;
		color: #ffffff;		
	}
	.mp-search-box{
		left: 0;
		top: 30rpx;
		width: 100%;
		padding: 10rpx 80rpx 30rpx;
		background-color: #1bbc3d;
		.ser-input{
			flex:1;
			height: 56rpx;
			line-height: 56rpx;
			text-align: left;
			font-size: 28rpx;
			color:$font-color-base;
			border-radius: 40rpx;
			background-color: #fff;
			padding-left: 16rpx;
		}
	}
	page{
		.cate-section{
			position:relative;
			z-index:5;
			border-radius:16rpx 16rpx 0 0;
			margin-top:-20rpx;
		}
		.carousel-section{
			padding: 0;
			.titleNview-placing {
				padding-top: 0;
				height: 0;
			}
			.carousel{
				.carousel-item{
					padding: 0;
				}
			}
			.swiper-dots{
				left:45rpx;
				bottom:40rpx;
			}
		}
	}
	
	page {
		background: #f5f5f5;
	}
	.m-t{
		margin-top: 16rpx;
	}
	/* 头部 轮播图 */
	.carousel-section {
		position: relative;
		padding-top: 20rpx;

		.titleNview-placing {
			height: var(--status-bar-height);
			padding-top: 44px;
			box-sizing: content-box;
		}

		.titleNview-background {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 426rpx;
			transition: .4s;
		}
	}
	.carousel {
		width: 100%;
		height: 350rpx;

		.carousel-item {
			width: 100%;
			height: 100%;
			padding: 0 28rpx;
			overflow: hidden;
		}

		image {
			width: 100%;
			height: 100%;
			border-radius: 10rpx;
		}
	}
	.swiper-dots {
		display: flex;
		position: absolute;
		left: 60rpx;
		bottom: 15rpx;
		width: 72rpx;
		height: 36rpx;
		background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAABkCAYAAADDhn8LAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTMyIDc5LjE1OTI4NCwgMjAxNi8wNC8xOS0xMzoxMzo0MCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6OTk4MzlBNjE0NjU1MTFFOUExNjRFQ0I3RTQ0NEExQjMiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6OTk4MzlBNjA0NjU1MTFFOUExNjRFQ0I3RTQ0NEExQjMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTcgKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6Q0E3RUNERkE0NjExMTFFOTg5NzI4MTM2Rjg0OUQwOEUiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6Q0E3RUNERkI0NjExMTFFOTg5NzI4MTM2Rjg0OUQwOEUiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4Gh5BPAAACTUlEQVR42uzcQW7jQAwFUdN306l1uWwNww5kqdsmm6/2MwtVCp8CosQtP9vg/2+/gY+DRAMBgqnjIp2PaCxCLLldpPARRIiFj1yBbMV+cHZh9PURRLQNhY8kgWyL/WDtwujjI8hoE8rKLqb5CDJaRMJHokC6yKgSCR9JAukmokIknCQJpLOIrJFwMsBJELFcKHwM9BFkLBMKFxNcBCHlQ+FhoocgpVwwnv0Xn30QBJGMC0QcaBVJiAMiec/dcwKuL4j1QMsVCXFAJE4s4NQA3K/8Y6DzO4g40P7UcmIBJxbEesCKWBDg8wWxHrAiFgT4fEGsB/CwIhYE+AeBAAdPLOcV8HRmWRDAiQVcO7GcV8CLM8uCAE4sQCDAlHcQ7x+ABQEEAggEEAggEEAggEAAgQACASAQQCCAQACBAAIBBAIIBBAIIBBAIABe4e9iAe/xd7EAJxYgEGDeO4j3EODp/cOCAE4sYMyJ5cwCHs4rCwI4sYBxJ5YzC84rCwKcXxArAuthQYDzC2JF0H49LAhwYUGsCFqvx5EF2T07dMaJBetx4cRyaqFtHJ8EIhK0i8OJBQxcECuCVutxJhCRoE0cZwMRyRcFefa/ffZBVPogePihhyCnbBhcfMFFEFM+DD4m+ghSlgmDkwlOgpAl4+BkkJMgZdk4+EgaSCcpVX7bmY9kgXQQU+1TgE0c+QJZUUz1b2T4SBbIKmJW+3iMj2SBVBWz+leVfCQLpIqYbp8b85EskIxyfIOfK5Sf+wiCRJEsllQ+oqEkQfBxmD8BBgA5hVjXyrBNUQAAAABJRU5ErkJggg==);
		background-size: 100% 100%;

		.num {
			width: 36rpx;
			height: 36rpx;
			border-radius: 50px;
			font-size: 24rpx;
			color: #fff;
			text-align: center;
			line-height: 36rpx;
		}

		.sign {
			position: absolute;
			top: 0;
			left: 50%;
			line-height: 36rpx;
			font-size: 12rpx;
			color: #fff;
			transform: translateX(-50%);
		}
	}
	/* 分类 */
	.cate-section {
		display: flex;
		justify-content: space-around;
		align-items: center;
		flex-wrap:wrap;
		padding: 30rpx 22rpx; 
		background: #fff;
		.cate-item {
			display: flex;
			flex-direction: column;
			align-items: center;
			font-size: $font-sm + 2rpx;
			color: $font-color-dark;
		}
		/* 原图标颜色太深,不想改图了,所以加了透明度 */
		image {
			width: 88rpx;
			height: 88rpx;
			margin-bottom: 14rpx;
			border-radius: 50%;
			opacity: .7;
			box-shadow: 4rpx 4rpx 20rpx rgba(250, 67, 106, 0.3);
		}
	}
	.ad-1{
		width: 100%;
		height: 210rpx;
		padding: 10rpx 0;
		background: #fff;
		image{
			width:100%;
			height: 100%; 
		}
	}
	/* 最新活动 */
	.f-header{
		display:flex;
		align-items:center;
		height: 140rpx;
		padding: 6rpx 30rpx 8rpx;
		background: #fff;		
		.tit-box{
			flex: 1;
			display: flex;
			flex-direction: column;
		}
		.tit{
			font-size: $font-lg +2rpx;
			color: #font-color-dark;
			line-height: 1.3;
		}
	}
	
	.business-section{
		padding: 0 30rpx;
		background: #fff;
		.business-item{
			background-color: #FFFFFF;
			width: 100%;
			padding: 0rpx 2% 40rpx 2%;
			overflow: hidden;
		}
		.image-wrapper{
			float: left;
			width: 40%;
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
			width: 58%;
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
			view {
				text-indent:2em;
			}
		}		
	}
</style>
