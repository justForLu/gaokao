<template>
	<view class="section">
		<view class="header-bg">
			
		</view>
		<view class="header">
			<view class="title-box">
				<view class="shop-img">
					<image src="../../static/test.jpg"></image>
					<text class="title">
						{{business_info.name}}
					</text>
				</view>				
				<view class="sale-num">
					<view>销量：{{business_info.number}}</view>
					<view>好评率：{{business_info.score}}</view>
				</view>
				<view class="notice">
					公告：{{business_info.notice}}
				</view>
			</view>
		</view>
		<view class="shop-menu">
			<text class="menu menu-goods" :class="{active: menuChoice==='goods'}" @click="changeMenu('goods')">
				点菜
			</text>
			<text class="menu menu-eva" :class="{active: menuChoice==='evaluate'}" @click="changeMenu('evaluate')">
				评价
			</text>
			<text class="menu menu-shop" :class="{active: menuChoice==='shop'}" @click="changeMenu('shop')">
				商家
			</text>
		</view>
		<view class="content">
			<view class="goods" v-show="menuChoice === 'goods'">
				<scroll-view scroll-y class="left-aside">
					<view v-for="(item, index) in cateList" :key="index" class="cate-list" :class="{active: index === 0}" @click="tabtap(item)">
						{{item.name}}
					</view>
				</scroll-view>
				<scroll-view scroll-with-animation scroll-y class="goods-content" @scroll="asideScroll" :scroll-top="tabScrollTop">
					<view v-for="cate in cateList" :key="cate.id" :id="'main-'+cate.id">
						<text class="cate-name">{{cate.name}}</text>
						<view class="goods-list">
							<view @click="navToList(cate.id, goods.id)" v-if="goods.category_id === cate.id" class="goods-box" v-for="(goods, g_index) in cate.goods" :key="goods.id">
								<view class="goods-img">
									<image :src="goods.image"></image>
								</view>
								<view class="goods-info">
									<view class="goods-name">{{goods.name}}</view>
									<view class="num-score">
										<text class="goods-num">销量：<text>{{goods.number}}</text></text>
										<text class="goods-score">评价：<text>{{goods.score}}</text></text>
									</view>
									<view class="goods-price"><text>￥</text>{{goods.price}}</view>
									<view class="buy-num">
										<view class="reduce-btn" @click="reduceCart(g_index, goods.id)" :class="{redDhow: cartList.g_index > 0}">
											<image src="../../static/reduce-btn.png"></image>
										</view>
										<view>
											<text class="goods_num">1</text>
										</view>
										<view class="add-btn" @click="addCart(g_index, goods.id)">
											<image src="../../static/add-btn.png"></image>
										</view>										
									</view>
								</view>
							</view>
						</view>
					</view>
				</scroll-view>
				
				<!--购物车-->
				<view class="cart">
					<view class="cart-img" @click="showCart()">						
						<image src="../../static/cart.png" width='100%' height='100%'></image>
						<view class="cart-num">22</view>
					</view>
					<view class="cart-price">
						<text>￥</text>55
					</view>
					<view class="cart-btn" @click="navToList()">
						去结算
					</view>
				</view>				
				<!--购物车列表-->
				<view class="cart-list" v-show="cartListStatus === 1">
					<view class="cart-bg" @click="hiddenCart()"></view>
					
					<view class="goods-list">
						<view class="cart-tap-box">
							<view class="cart-tap">
								
							</view>
						</view>						
						<view class="goods-box">
							<view class="goods-img">
								<image src="../../static/test.jpg"></image>
							</view>
							<view class="goods-info">
								<view class="goods-name">测试测试测试测试测试，测试测试测试</view>
								<view class="goods-price"><text>￥</text>10</view>
								<view class="buy-num">
									<view class="reduce-btn">
										<image src="../../static/reduce-btn.png"></image>
									</view>
									<view>
										<text class="goods_num">1</text>
									</view>
									<view class="add-btn">
										<image src="../../static/add-btn.png"></image>
									</view>										
								</view>
							</view>
						</view>
						<view class="goods-box">
							<view class="goods-img">
								<image src="../../static/test.jpg"></image>
							</view>
							<view class="goods-info">
								<view class="goods-name">测试测试测试测试测试，测试测试测试</view>
								<view class="goods-price"><text>￥</text>10</view>
								<view class="buy-num">
									<view class="reduce-btn">
										<image src="../../static/reduce-btn.png"></image>
									</view>
									<view>
										<text class="goods_num">1</text>
									</view>
									<view class="add-btn">
										<image src="../../static/add-btn.png"></image>
									</view>										
								</view>
							</view>
						</view>
					</view>
				</view>
				
			</view>
			<view class="evaluate" v-show="menuChoice === 'evaluate'">
				评价
			</view>
			<view class="shop" v-show="menuChoice === 'shop'">
				商家
			</view>
		</view>
		
	</view>
</template>

<script>
	var app = require("../../common/common.js");
	export default {
		data() {
			return {
				sizeCalcState: false,
				tabScrollTop: 0,
				currentId: 1,
				menuChoice: 'goods',
				cartListStatus: 0,
				business_id: 0,
				business_info: [],
				cateList: [],
				cartList: [],
			}
		},
		onLoad(options){
			var that = this;
			that.business_id = options.id;
			that.getCate();
			that.getBusiness();
		},
		methods: {
			async getCate(){
				var that = this;
				uni.request({
					url: app.apiHost + "category/get_cat_goods",
					method: 'GET',
					data:{business_id: that.business_id},
					success: function (res) {
						that.cateList = res.data.data;
					}
				})
			},
			async getBusiness() {
				var that = this;
				uni.request({
					url: app.apiHost + "business/get_info",
					method: 'GET',
					data:{business_id: that.business_id},
					success: function (res) {
						that.business_info = res.data.data;
					}
				})
			},
			addCart: function(index, goods_id) {
				
			},
			reduceCart: function(index, goods_id) {
				
			},
			hiddenCart: function() {
				var that = this;
				
				that.cartListStatus = 0;
			},
			showCart: function() {
				var that = this;
				
				that.cartListStatus = 1;
			},
			//一级分类点击
			tabtap(item){
				if(!this.sizeCalcState){
					this.calcSize();
				}
				
				this.currentId = item.id;
				let index = this.slist.findIndex(sitem=>sitem.pid === item.id);
				this.tabScrollTop = this.slist[index].top;
			},
			//右侧栏滚动
			asideScroll(e){
				if(!this.sizeCalcState){
					this.calcSize();
				}
				let scrollTop = e.detail.scrollTop;
				let tabs = this.slist.filter(item=>item.top <= scrollTop).reverse();
				if(tabs.length > 0){
					this.currentId = tabs[0].pid;
				}
			},
			//计算右侧栏每个tab的高度等信息
			calcSize(){
				let h = 0;
				this.slist.forEach(item=>{
					let view = uni.createSelectorQuery().select("#main-" + item.id);
					view.fields({
						size: true
					}, data => {
						item.top = h;
						h += data.height;
						item.bottom = h;
					}).exec();
				})
				this.sizeCalcState = true;
			},
			navToList(){
				uni.navigateTo({
					url: `/pages/order/createOrder`
				})
			},
			changeMenu: function(menu) {
				var that = this;
				
				that.menuChoice = menu;
			}
		}
	}
</script>

<style lang='scss'>
	page,
	.section {
		height: 100%;
		background-color: #f5f5f5;
	}
	
	.header-bg {
		width: 100%;
		height: 200rpx;
		background-color: #ffc23d;
		z-index: 1;
	}
	
	.header {
		width: 90%;
		margin: 0px auto;
		background-color: #FFFFFF;
		margin-top: -40rpx;
		border-radius: 10rpx;
		.title-box {
			overflow: hidden;
			height: 300rpx;
			.shop-img {
				image {
					width: 120rpx;
					height: 120rpx;
					position: absolute;
					top: 120rpx;
					left: 9vw;
					border-radius: 6rpx;
				}
				.title {
					color: #333333;
					font-size: 36rpx;
					padding-left: 180rpx;
					line-height: 96rpx;
				}
			}
			.sale-num {
				width: 100%;
				overflow: hidden;
				padding-left: 4vw;
				view {
					float: left;
					width: 40%;
					line-height: 64rpx;
					text-align: left;
					font-size: 26rpx;
					color: #999999;
				}
			}
			
			.notice {
				padding-left: 4vw;
				line-height: 64rpx;
				font-size: 26rpx;
				color: #999999;
			}
		}
	}
	
	.shop-menu {
		overflow: hidden;
		padding-left: 5vw;
		padding-top: 40rpx;
		padding-bottom: 10rpx;
		.menu {
			float: left;
			margin-right: 100rpx;
			color: #999999;			
		}
		.active{
			color: #000000;
			padding-bottom: 10rpx;
			border-bottom: 6rpx #ffad2c solid;
		}
	}
	
	.content {
		&.show {
			display: block;
		}
		&.none {
			display: none;
		}
		.goods {
			display: flex;
			height: 100%;
			
			.left-aside {
				flex-shrink: 0;
				width: 200rpx;
				height: 100%;
				background-color: #f8f8f8;
			}
			.cate-list {
				display: flex;
				align-items: center;
				justify-content: center;
				width: 100%;
				height: 100rpx;
				font-size: 28rpx;
				color: $font-color-base;
				position: relative;
				&.active{
					color: #ffad2c;
					background: #FFFFFF;
					&:before{
						content: '';
						position: absolute;
						left: 0;
						top: 50%;
						transform: translateY(-50%);
						height: 36rpx;
						width: 8rpx;
						background-color: #ffad2c;
						border-radius: 0 4px 4px 0;
						opacity: .8;
					}
				}
			}
			
			.goods-content{
				flex: 1;
				overflow: hidden;
				padding-left: 20rpx;
				background-color: #FFFFFF;
			}
			.cate-name{
				display: flex;
				align-items: center;
				height: 70rpx;
				padding-top: 8rpx;
				font-size: 28rpx;
				color: $font-color-dark;
			}
			.goods-list{
				display: flex;
				flex-wrap: wrap;
				width: 100%;
				background: #fff;
				padding-top: 12rpx;
				&:after{
					content: '';
					flex: 99;
					height: 0;
				}
			}
			.goods-box{
				flex-shrink: 0;
				display: flex;
				justify-content: left;
				align-items: left;
				flex-direction: row;
				width: 100%;
				font-size: 26rpx;
				color: #666;
				padding-bottom: 40rpx;
				padding-right: 20rpx;
				
				image{
					width: 22vw;
					height: 22vw;
					border-radius: 10rpx;
				}
				
				.goods-info {
					padding-left: 20rpx;
					
					.goods-name {
						font-size: 32rpx;
						color: #000;
						min-height: 8vw;
					}
					
					.num-score {
						height: 52rpx;
						line-height: 52rpx;
						.goods-num, .goods-score {
							padding-right: 20rpx;
							text {
								color: #f94e4e;
							}
						}
					}
					
					.goods-price {
						padding-top: 20rpx;
						font-size: 34rpx;
						color: #fb5443;
						text {
							font-size: 24rpx;
						}
					}
					
					.buy-num {
						display: flex;
						justify-content: flex-end;
						flex-direction: row;
						width: 300rpx;
						position: relative;
						bottom: 44rpx;
						
						image {
							width: 40rpx;
							height: 40rpx;
						}						
						text {
							display: block;
							width: 48rpx;
							text-align: center;
						}
						
						.reduce-btn {
							display: none;
						}
					}
				}
			}
			.cart {
				display: flex;
				justify-content: flex-end;
				flex-direction: row;				
				position: fixed;
				bottom: 0rpx;
				background-color: #FFFFFF;
				width: 100%;
				height: 100rpx;				
				
				.cart-img {
					padding-top: 22rpx;
					display: flex;
					justify-content: left;
					align-items: left;
					flex-direction: row;
					.cart-num {
						width: 34rpx;
						height: 34rpx;
						line-height: 34rpx;
						text-align: center;
						color: #fff;
						font-size: 24rpx;
						background-color: #fb5443;
						z-index: 9;
						border-radius: 34rpx;
						position: relative;
						right: 16rpx;
						bottom: 6rpx;
					}
					image {
						width: 60rpx;
						height: 60rpx;
					}					
				}
				
				.cart-price {
					margin-left: 20rpx;
					padding-top: 24rpx;
					color: #fb5443;
					text {
						font-size: 24rpx;
						font-weight: bold;
					}
				}
				
				.cart-btn {
					margin-left: 200rpx;
					background-color: #ffc23d;
					color: #fff;
					padding: 0rpx 52rpx;
					line-height: 100rpx;
				}
			}
			.cart-list {
				width: 100%;
				position: fixed;
				bottom: 100rpx;
				
				.cart-bg {
					height: 100vh;
					background-color: #000000;
					opacity: 0.4;
					z-index: 9999;
				}
				.cart-tap-box {
					width: 100%;
					background-color: #000000;
					opacity: 0.4;
				}
				.cart-tap {
					width: 100%;
					height: 28rpx;
					border-top-right-radius: 14rpx;
					border-top-left-radius: 14rpx;
					background-color: #ffc23d;
				}
				.goods-list {
					padding-top: 0rpx;
					.goods-box {
						height: 23vw;
						padding: 20rpx 40rpx;
						border-bottom: 1px #f5f5f5 solid;
						.goods-img {
							image {
								width: 16vw;
								height: 16vw;
							}
						}
						.goods-info {
							.goods-name {
								font-size: 24rpx;
							}
							.buy-num {
								width: 70vw;
							}
						}
					}
					.buy-num {
						
					}
				}
			}
		}
		
		.evaluate {
			
		}
		
		.shop {
			
		}
	}
	
	
	
	
</style>
