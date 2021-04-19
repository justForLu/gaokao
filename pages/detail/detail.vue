<template>
	<view>
		<swiper class="carousel" indicator-dots=true circular=true interval="3000" duration="700">
			<swiper-item v-for="(item,index) in data.imgList" :key="index">
				<view class="image-wrapper">
					<image
						:src="item.src" 
						:class="item.loaded" 
						mode="aspectFill"
						@load="imageOnLoad('imgList', index)" 
					></image>
				</view>
			</swiper-item>
		</swiper>
		<view class="scroll-view-wrapper">
			<scroll-view class="episode-panel" :class="{Skeleton:!loaded}" scroll-x>
				<view v-for="(item, index) in data.episodeList" :key="index"
					:class="{current: currentEpd===item}"
					@click="changeEpd(index)"
				>
					{{item}}
				</view>
			</scroll-view>
		</view>
		<view class="info">
			<view class="title">
				<text :class="{Skeleton:!loaded}">{{data.title}}</text>
				<text :class="{Skeleton:!loaded}">{{data.title2}}</text>
			</view>
			<text class="yticon icon-xia"></text>
		</view>
		<view class="actions">
			<text class="yticon icon-fenxiang2" @click="share"></text>
			<text class="yticon icon-Group-"></text>
			<text class="yticon icon-shoucang" :class="{active: data.favorite}" @click="favorite"></text>
		</view>

		<!-- 猜你喜欢 -->
		<view class="guess">
			<view class="section-tit">猜你喜欢</view>
			<view class="guess-list">
				<view 
					v-for="(item, index) in data.guessList" :key="index"
					class="guess-item"
				>
					<view class="image-wrapper">
						<image 
							:src="item.src" 
							:class="item.loaded" 
							mode="aspectFill"
							@load="imageOnLoad('guessList', index)" 
						></image>
					</view>
					<text class='title clamp' :class="{Skeleton:!loaded}">{{item.title}}</text>
					<text class="clamp" :class="{Skeleton:!loaded}">{{item.title2}}</text>
				</view>
			</view>
		</view>
		<!-- 评论 -->
		<view class="evalution">
			<view class="section-tit">评论</view>
			<view class="eva-list" :class="{Skeleton:!loaded}">
				<view  v-for="(item, index) in data.evaList" :key="index"
					class="eva-item"
				>
					<image :src="item.src" mode="aspectFill"></image>
					<view class="eva-right">
						<text>{{item.nickname}}</text>
						<text>{{item.time}}</text>
						<view class="zan-box">
							<text>{{item.zan}}</text>
							<text class="yticon icon-shoucang"></text>
						</view>
						<text class="content">{{item.content}}</text>
					</view>
				</view>
			</view>
		</view>
		<!-- 分享 -->
		<share 
			ref="share" 
			:contentHeight="580"
			:shareList="shareList"
		></share>
	</view>
</template>

<script>
	import share from '@/components/share';
	export default {
		components: {
			share
		},
		data() {
			return {
				loaded: false,
				currentEpd: 1,
				data: {
					guessList: [{},{},{},{}] 
				},
				shareList: []
			};
		},
		async onLoad(){
			let detailData = await this.$api.json('detailData');
			let shareList = await this.$api.json('shareList');
			this.loaded = true;
			this.data = detailData;
			this.shareList = shareList;
			uni.setNavigationBarTitle({
				title: detailData.title
			})
		},
		methods:{
			imageOnLoad(key,index){
				this.$set(this.data[key][index], 'loaded', 'loaded');
			},
			changeEpd(index){
				let list = this.data.episodeList;
				let epd = list[index];
				this.$api.msg(`切换到第${epd}项`);
				this.currentEpd = epd;
			},
			//分享
			share(){
				this.$refs.share.toggleMask();	
			},
			//收藏
			favorite(){
				this.data.favorite = !this.data.favorite;
			}
		},
		//处理遮罩层物理返回键
		onBackPress(){
			if(this.$refs.share.show){
				this.$refs.share.toggleMask();
				return true;
			}
		}
	}
</script>

<style lang="scss">
	page{
		background: $page-color-base;
	}

	.carousel {
		height: 200px;
		.image-wrapper{
			display: flex;
			justify-content: center;
			align-content: center;
			width: 100%;
			height: 100%;
			overflow: hidden;

			image {
				width: 100%;
				height: 100%;
			}
		}
	}
	.scroll-view-wrapper{
		display:flex;
		align-items:center;
		height: 90rpx;
		padding: 20rpx 0 20rpx 40rpx;
		background: #fff;
	}
	.episode-panel {
		white-space: nowrap;
		width: 100%;
		view {
			display: inline-block;
			margin-right: 30rpx;
			width: 56rpx;
			font-size: $font-lg;
			color: $font-color-base;
			&.current{
				color: #07a7a7;
			}
		}
	}

	.info {
		display: flex;
		align-items: center;
		padding: 10rpx 40rpx;
		background: #fff;

		.title {
			flex: 1;
			display: flex;
			flex-direction: column;
			font-size: $font-lg + 4rpx;
			color: $font-color-dark;

			text:last-child {
				font-size: $font-sm;
				color: $font-color-light;
				margin-top: 4rpx;
				&.Skeleton{
					width:220rpx;
				}
			}
		} 
		.yticon {
			font-size: 44rpx;
			color: $font-color-base;
			margin: 0 10rpx 0 30rpx;
		}
	}

	.actions {
		padding: 10rpx 28rpx;
		background: #fff;

		.yticon {
			font-size: 46rpx;
			color: $font-color-base;
			padding: 10rpx 12rpx;
			&.active{
				color: #ff4443;
			}
			&:nth-child(2) {
				font-size: 50rpx;
			}
		}
	}

	.section-tit {
		font-size: $font-base+2rpx;
		color: $font-color-dark;
		margin-bottom: 30rpx;
		text-align:center;
	}

	.guess {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		padding: 30rpx 40rpx 10rpx;
		margin-top: 16rpx;
		background: #fff;
	}

	.guess-list {
		display: flex;
		flex-wrap: wrap;
		width: 100%;
	}
	.guess-item {
		display: flex;
		flex-direction: column;
		flex: 1;
		overflow: hidden;
		min-width: 40%;
		margin-right: 26rpx;
		padding-bottom: 40rpx;
	
		&:nth-child(2n) {
			margin-right: 0;
		}
	
		image {
			width: 100%;
			height: 200rpx;
			border-radius: 10rpx;
		}
	
		text {
			font-size: $font-sm;
			color: $font-color-light;
			&.Skeleton{
				width: 180rpx;
				&.title{
					width: 140rpx;
				}
			}
			&.title{
				font-size: $font-base+2rpx;
				color: $font-color-dark;
				margin-top:16rpx;
				margin-bottom: 8rpx;
			}
		}
	}
	.evalution{
		display:flex;
		flex-direction:column;
		background: #fff;
		margin-top: 16rpx;
		padding: 40rpx 0;
	}

	.eva-item{
		display:flex;
		padding: 20rpx 40rpx;
		image{
			width: 60rpx;
			height: 60rpx;
			border-radius: 50px;
			flex-shrink: 0;
			margin-right: 24rpx;
		}
	}
	.eva-right{
		display:flex;
		flex-direction:column;
		flex: 1;
		font-size: $font-sm + 2rpx;
		color: $font-color-light;
		position:relative;
		.zan-box{
			display:flex;
			align-items:base-line;
			position:absolute;
			top: 10rpx;
			right: 10rpx;
			.yticon{
				margin-left: 8rpx; 
			}
		}
		.content{
			font-size: $font-base;
			color: #333;
			padding-top:20rpx;
		}
	}
</style>
