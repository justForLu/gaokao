<template>
	<view class="content">
		<view class="row b-b">
			<text class="tit">联系人</text>
			<input class="input" type="text" v-model="name" placeholder="收货人姓名" placeholder-class="placeholder" />
		</view>
		<view class="row b-b">
			<text class="tit">手机号</text>
			<input class="input" type="number" v-model="mobile" placeholder="收货人手机号码" placeholder-class="placeholder" />
		</view>
		<view class="row b-b">
			<text class="tit">地址</text>
			<text @click="choiceSchool" class="input">
				{{schoolName}}
			</text>
			<text class="yticon icon-shouhuodizhi"></text>			
		</view>
		<!--三级联动选中区域-->
		<view class="choice-school-box" v-if="showSchoolChoice">
		<!-- 遮罩层 -->
			<view class="choice-school-mask">
				
			</view>
			<view class="choice-school-content">
				<view class="choice-school-btn">
					<view class="choice-school-header-btn-cancel-box" @click="pickerCancel">
						<text class="choice-school-btn-cancel">取消</text>
					</view>
					<view class="choice-school-header-btn-submit-box" @click="pickerConfirm">
						<text class="choice-school-btn-submit">确定</text>
					</view>
				</view>
				<view class="choice-school-city">
					<picker-view indicator-style="height: 50px;" class="city-box" :value="indexVal"  @change="cityChange">
						<picker-view-column class="city-column">
							<view v-for="(item,index) in provinceList" class="city-view" :key='index'>{{item.title}}</view>
						</picker-view-column>
						
						<picker-view-column class="city-column">
							 <view v-for="(item,index) in cityList" class="city-view" :key="index">{{item.title}}</view>
						</picker-view-column>
						 
						<picker-view-column class="city-column">
							<view v-for="(item,index) in areaList" class="city-view" :key="index">{{item.title}}</view>
						</picker-view-column>
						
						<picker-view-column class="school-column">
							<view v-for="(item,index) in schoolList" class="school-view" :key="index">{{item.name}}</view>
						</picker-view-column>
					</picker-view>
				</view>
			</view>		
		</view>	
		<view class="row b-b"> 
			<text class="tit">门牌号</text>
			<input class="input" type="text" v-model="address" placeholder="楼号、门牌" placeholder-class="placeholder" />
		</view>
		
		<view class="row default-row">
			<text class="tit">设为默认</text>
			<switch :checked="defaule" color="#ffc23d" @change="switchChange" />
		</view>
		<button class="add-btn" @click="confirm">提交</button>
	</view>
</template>

<script>
	var app = require("../../common/common.js");
	export default {
		data() {
			return {
				name: '',
				mobile: '',
				showSchoolChoice: false,
				provinceList: [],	//省
				cityList: [],	//市
				areaList: [],	//区
				schoolList: [],	//高校
				indexVal: [0,0,0,0],	//picker-view选中数组下标初始值
				schoolId: 0,
				schoolName: '选择高校',
				area: '',
				longitude: '',
				latitude: '',
				address: '',
				default: false
			}
		},
		onLoad(option){
			let title = '新增收货地址';
			if(option.type==='edit'){
				title = '编辑收货地址'
				
				this.addressData = JSON.parse(option.data)
			}
			this.manageType = option.type;
			uni.setNavigationBarTitle({
				title
			})
			this.getProvince()//页面加载 1标识页面加载 2主动调用
		},
		methods: {
			switchChange(e){
				this.default = e.detail;
			},
			
			choiceSchool: function(){
				var that = this;
				that.showSchoolChoice = true;
				
			},
			cityChange: function(e){//picker-view组件的change事件
				var that = this;
				var indexS = e.detail.value;//选中下标数组
				if(indexS[0]){
					if(that.indexVal[0] != indexS[0]){
						that.indexVal[1] = indexS[1] = 0;
						that.indexVal[2] = indexS[2] = 0;
						that.indexVal[3] = indexS[3] = 0;
					}
					that.indexVal[0] = indexS[0];
				}else{
					that.indexVal[0] = 0;
				}
				if(indexS[1]){//有值再赋值
					that.indexVal[1] = indexS[1];
				}else{
					that.indexVal[1] = 0;
				}
				if(indexS[2]){
					that.indexVal[2] = indexS[2];
				}else{
					that.indexVal[2] = 0;
				}
				if(indexS[3]){
					that.indexVal[3] = indexS[3];
				}else{
					that.indexVal[3] = 0;
				}
				
				//1.通过下标0获取省对应下标的ID值调用市接口 2.表示手动选取
				that.getCity(that.provinceList[that.indexVal[0]].id,2);
				//获取"区" 未选择市时默认选中0
				that.getArea(that.cityList[that.indexVal[1]].id);
				//获取高校
				var province_id = that.provinceList[that.indexVal[0]].id;
				var city_id = that.cityList[that.indexVal[1]].id;
				var area_id = that.areaList[that.indexVal[2]].id;
				that.getSchool(province_id, city_id, area_id);
			},
			getProvince: function(e) { // 加载省份
				var that = this;
				uni.request({
					url: app.apiHost + "city/get_list",
					method: 'GET',
					data: {
						parent:0,
						grade: 1
					},
					success: function (res) {
						that.provinceList = res.data.data;
						that.getCity(that.provinceList[that.indexVal[0]].id,1)//调用市
						
					}
				})
			},
			getCity: function(provinceId,f) {//加载市
				var that = this;
				uni.request({
					url: app.apiHost + "city/get_list",
					method: 'GET',
					data: {
						parent:provinceId,
						grade: 2
					},					
					success:  function(res) {
						that.cityList = res.data.data;
						if(f==1){//页面调用时默认显示第一条数据
							that.getArea(that.cityList[that.indexVal[1]].id)
						}
					}
				})
			},
			getArea: function(cityId, f) {//加载区域
				var that = this;
				uni.request({
					url: app.apiHost + "city/get_list",
					method: 'GET',
					data: {
						parent:cityId,
						grade: 3
					},					
					success:  function(res) {
						that.areaList = res.data.data;
						if(f==1){//页面调用时默认显示第一条数据
							var province_id = that.provinceList[that.indexVal[0]].id;
							var city_id = that.cityList[that.indexVal[1]].id;
							var area_id = that.areaList[that.indexVal[2]].id;
							that.getSchool(province_id, city_id, area_id, 1)
						}
					}
				})
			},
			getSchool: function(provinceId, cityId, areaId) {
				var that = this;
				uni.request({
					url: app.apiHost + "school/get_list",
					method: 'GET',
					data: {
						province: provinceId,
						city: cityId,
						area: areaId
					},
					success: function (res) {
						that.schoolList = res.data.data;
					}
				})
			},
			pickerCancel: function() {
				var that = this;
				that.showSchoolChoice = false;
			},
			pickerConfirm: function() {
				var that = this;
				that.showSchoolChoice = false;
				//请求接口，保存选择的高校
				that.schoolId = that.schoolList[that.indexVal[3]].id;
				that.schoolName = that.schoolList[that.indexVal[3]].name;
			},
			
			//提交
			confirm(){
				var that = this;
				if(!that.name){
					this.$api.msg('请填写收货人姓名');
					return;
				}
				if(!/(^1[3|4|5|7|8][0-9]{9}$)/.test(that.mobile)){
					this.$api.msg('请输入正确的手机号码');
					return;
				}
				if(!that.schoolId){
					this.$api.msg('请选择高校');
					return;
				}
				if(!that.address){
					this.$api.msg('请填写具体地址');
					return;
				}
				
				// //this.$api.prePage()获取上一页实例，可直接调用上页所有数据和方法，在App.vue定义
				// this.$api.prePage().refreshList(data, this.manageType);
				// this.$api.msg(`地址${this.manageType=='edit' ? '修改': '添加'}成功`);
				// setTimeout(()=>{
				// 	uni.navigateBack()
				// }, 800)
			},
		}
	}
</script>

<style lang="scss">
	page{
		background: $page-color-base;
		padding-top: 16rpx;
	}

	.row{
		display: flex;
		align-items: center;
		position: relative;
		padding:0 30rpx;
		height: 110rpx;
		background: #fff;
		
		.tit{
			flex-shrink: 0;
			width: 120rpx;
			font-size: 30rpx;
			color: $font-color-dark;
		}
		.input{
			flex: 1;
			font-size: 30rpx;
			color: $font-color-dark;
		}
		.icon-shouhuodizhi{
			font-size: 36rpx;
			color: $font-color-light;
		}
	}
	/* 选择高校 */
	.choice-school-box {
		position: fixed;
		bottom: 0rpx;
		width: 100%;
		height: 100%;
		z-index: 999;
		.choice-school-mask{
			height: 60%;
			background-color: #000000;
			opacity: 0.4;
		}
		.choice-school-content {
			height: 40%;
			background-color: #FFFFFF;
			.choice-school-btn {
				.choice-school-header-btn-cancel-box {
					float: left;
					.choice-school-btn-cancel {
						font-size: 28rpx;
						color: #1aad19;
						line-height: 72rpx;
						padding-left: 20rpx;
						display: inline;
					}
				}
				.choice-school-header-btn-submit-box {
					float: right;
					.choice-school-btn-submit {
						font-size: 28rpx;
						color: #007AFF;
						line-height: 72rpx;
						padding-right: 20rpx;
						display: inline;
					}
				}
				height: 72rpx;
				border-bottom: 1rpx #f2f2f2 solid;
				overflow: hidden;
			}
			
			.choice-school-city {
				.city-box {
					width: 100%; 
					height: 300rpx;
					font-size: 28rpx;
					.city-column {
						flex:0 0 15%
					}
					.school-column {
						flex:0 0 55%
					}
					.city-view {
						line-height: 100rpx; 
						text-align: center;
					}
					.school-view {
						line-height: 100rpx;
						text-align: center;
					}
				}	
				
			}
		}
	}
	.default-row{
		margin-top: 16rpx;
		.tit{
			flex: 1;
		}
		switch{
			transform: translateX(16rpx) scale(.9);
		}
	}
	.add-btn{
		display: flex;
		align-items: center;
		justify-content: center;
		width: 690rpx;
		height: 80rpx;
		margin: 60rpx auto;
		font-size: $font-lg;
		color: #000;
		background-color: #ffc23d;
		border-radius: 10rpx;
		box-shadow: 1px 2px 5px rgba(255, 194, 61, 0.4);
	}
</style>
