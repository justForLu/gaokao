<template>
	<view>
		<view class="user-section">
			<image class="bg" src="/static/user-bg.jpg"></image>
			<text class="bg-upload-btn yticon icon-paizhao"></text>
			<view class="portrait-box">
				<image class="portrait" :src="userInfo.portrait || '/static/missing-face.png'"></image>
				<text class="pt-upload-btn yticon icon-paizhao"></text>
			</view>
		</view>
	</view>
</template>

<script>
	import {  
	    mapState,  
	    mapMutations  
	} from 'vuex';  
	export default {
		data() {
			return {
				
			};
		},
		computed:{
			...mapState(['userInfo']),
		}
	}
</script>

<style lang="scss">
	page{
		background: $page-color-base;
	}
	.user-section{
		display:flex;
		align-items:center;
		justify-content: center;
		height: 460rpx;
		padding: 40rpx 30rpx 0;
		position:relative;
		.bg{
			position:absolute;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			filter: blur(1px);
			opacity: .7;
		}
		.portrait-box{
			width: 200rpx;
			height: 200rpx;
			border:6rpx solid #fff;
			border-radius: 50%;
			position:relative;
			z-index: 2;
		}
		.portrait{
			position: relative;
			width: 100%;
			height: 100%;
			border-radius: 50%;
		}
		.yticon{
			position:absolute;
			line-height: 1;
			z-index: 5;
			font-size: 48rpx;
			color: #fff;
			padding: 4rpx 6rpx;
			border-radius: 6rpx;
			background: rgba(0,0,0,.4);
		}
		.pt-upload-btn{
			right: 0;
			bottom: 10rpx;
		}
		.bg-upload-btn{
			right: 20rpx;
			bottom: 16rpx;
		}
	}


</style>
