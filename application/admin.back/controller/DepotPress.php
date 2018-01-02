<?php
	namespace app\admin\controller;
	use think\Controller;
	use think\Db;
	
	class DepotPress extends Common{
		
		
		
		//物料进库页面
		public function pressManager(){
			
			$re=[
				'status'=>1,
				'page'=>$this->fetch(),
			];
			return $re;
		}		
		
		//物料进库页面
		public function press(){
			
			$re=[
				'status'=>1,
				'page'=>$this->fetch(),
			];
			return $re;
		}
		
		//仓库单子数据提交
		public function pressSubAddhandle(){
			$data=request()->post();
			$data['materiel_add_time']=time();
			$materiel_pid=$data['materiel_pid'];
			
			//进库
//			if(!$data['materiel_out_pid']){
//				$data['materiel_out_pid']=$data['materiel_pid'];
//			}
			//同步到指定materiel_out_pid，便于进出库计算
			
			//获取库存，判断是否超出库存
			$materiel_number=db('depot_materiels')->where('materiel_id',$materiel_pid)->find()['materiel_number'];
			$differ_materiel_number=$materiel_number-$data['materiel_number'];
			
//			if($differ_materiel_number<0){
//				return ['state'=>0,'msg'=>'当前出库数量，超出范围'];
//				die;
//			}
			
			//保持物料变更状态
			$re_id=db('depot_press_materiels_goods')->insertGetId($data);
			if($re_id){

				$re=[
					'status'=>1,
					'page'=>'保存状态成功',
					'bId'=>$re_id,
				];
			}else{
				$re=[
					'status'=>0,
					'msg'=>'保存状态错误',
				];
			}
			return $re;
		}
		
		//仓库出库数据提交
	
		
		//销售退库
		public function sales(){
			$re=[
				'status'=>1,
				'page'=>$this->fetch(),
			];
			return $re;
		}
	

		
		
		
		//样品退库
		public function sample(){
			$re=[
				'status'=>1,
				'page'=>$this->fetch(),
			];
			return $re;
		}
		
		
		//生产入库
		public function production(){
			$re=[
				'status'=>1,
				'page'=>$this->fetch(),
			];
			return $re;
		}
	
		//成品入库
		public function endProduct(){
			$re=[
				'status'=>1,
				'page'=>$this->fetch(),
			];
			return $re;
		}		
		
	
		//半成品入库
		public function semiManufactures(){
			$re=[
				'status'=>1,
				'page'=>$this->fetch(),
			];
			return $re;
		}			
		
		//不良品入库
		public function rejects(){
			$re=[
				'status'=>1,
				'page'=>$this->fetch(),
			];
			return $re;
		}		
		
		//其他入库
		public function other(){
			$re=[
				'status'=>1,
				'page'=>$this->fetch(),
			];
			return $re;
		}		
				
		
		public function deliveryOfCargoFromStorageManager(){
			
			$re=[
				'status'=>1,
				'page'=>$this->fetch(),
			];
			return $re;
		}
		
		//出库
		//销售出库
		public function salesOut(){
			$re=[
				'status'=>1,
				'page'=>$this->fetch(),
			];
			return $re;
		}			
		
		//样品退库
		public function sampleOut(){
			$re=[
				'status'=>1,
				'page'=>$this->fetch(),
			];
			return $re;
		}			
		
		//成品出库
		public function endProductOut(){
			$re=[
				'status'=>1,
				'page'=>$this->fetch(),
			];
			return $re;
		}
				
		//半成品出库
		public function semiManufacturesOut(){
			$re=[
				'status'=>1,
				'page'=>$this->fetch(),
			];
			return $re;
		}			
		
		//生产领料出库
		public function theProductionOfMaterials(){
			$re=[
				'status'=>1,
				'page'=>$this->fetch(),
			];
			return $re;
		}			
		//其他出库
		public function otherOut(){
			$re=[
				'status'=>1,
				'page'=>$this->fetch(),
			];
			return $re;
		}		


		//物料入库提交数据
		public function pressAddhandle(){
			$data=request()->post();
			$data['materiel_add_time']=strtotime(str_replace('T',' ',$data['materiel_add_time']));
			$data['materiel_goods_ids']=ltrim($data['requisition_sub_ids'],',');
			//物料进库标志
			$data['materiel_is_in']=1;
			$data['requisitionl_flow']=$this->getFlowData();
			$data['requisition_from_id']=session('admin_id');
			
			//获取flow表id的信息，填充数据
			$duty_id=session('duty_id');
			
			$duty_model=model('Duty');
			
			//获取部门信息
			$duty_info=$duty_model->getFlowInfo($duty_id);
			//获取事务流对应flow id
			$data['requisitionl_flow_id']=$duty_info['duty_flow_id'];;
			
			//获取处理事务流信息的ID
			$data['requisitionl_routine_id']=$duty_info['duty_routine_id'];
			
			//保存本部门的superid
			$data['requisition_super_id']=session('admin_duty_superid');
			unset($data['requisition_sub_ids']);
			//dump($data);die;
					
			//根绝oa_depot_press_type获取id,然后插入数据			
			$type_id=db('depot_press_type')->where([
				'type_number'=>$data['materiel_type'],
				'type_is_in'=>1
			])->find()['type_id'];			
			//获取插入后的ID
			$data['materiel_type']=$type_id;		
			
			$re_id=db('depot_press_materiels')->insertGetId($data);
			
			if($re_id){
				$this->outDepot($data['materiel_goods_ids'],$re_id,1);
				$re=$this->detail($re_id);

				if($re['state']==1){
					return $re=[
						'state'=>2,
						'page'=>$re['page']	
					];		
				}
					return $re=[
						'state'=>-1,
						'page'=>'找不页面'
					];					
				
			}
			
		}
		

		
		//获取详情信息
		public function detail($id=1){
				//实例化一个插件
				$model=model('DepotPress');
				//传递参数
				$data=$model->DepotPress($id);
				$this->assign('data',$data);
				$re=[
					'state'=>1,
					'page'=>$this->fetch('depot_press/'.$data['DepotpressType']['type_name'])
				];	
				return $re;		
		}
     

		
				
		//获取当前的部门的事务流  然后写入数据字段
		public function getFlowData(){
			//获取部门事务流id
			$duty_id=session('duty_id');
			$duty_flow_id=db('duty')->find($duty_id)['duty_flow_id'];
			
			//获取事务数据
			$flow_weights=db('flow')->find($duty_flow_id)['flow_weights'];
			return $flow_weights;
			
		}
		
		
		//物料出库计算方法
		public function OutAddhandle(){
			$data=request()->post();
			$data['materiel_add_time']=strtotime(str_replace('T',' ',$data['materiel_add_time']));
			$data['materiel_goods_ids']=ltrim($data['requisition_sub_ids'],',');
			//物料进库标志
			$data['materiel_is_in']=0;
			unset($data['requisition_sub_ids']);
			
			$data['requisitionl_flow']=$this->getFlowData();
			$data['requisition_from_id']=session('admin_id');
			//获取flow表id的信息，填充数据
			$duty_id=session('duty_id');
			
			$duty_model=model('Duty');
			
			//获取部门信息
			$duty_info=$duty_model->getFlowInfo($duty_id);
			//获取事务流对应flow id
			$data['requisitionl_flow_id']=$duty_info['duty_flow_id'];;
			
			//获取处理事务流信息的ID
			$data['requisitionl_routine_id']=$duty_info['duty_routine_id'];
			
			//保存本部门的superid
			$data['requisition_super_id']=session('admin_duty_superid');
					
				//根绝oa_depot_press_type获取id,然后插入数据			
				$type_id=db('depot_press_type')->where([
					'type_number'=>$data['materiel_type'],
					'type_is_in'=>0
				])->find()['type_id'];	
	
			//获取插入后的ID
			$data['materiel_type']=$type_id;
			$re_id=db('depot_press_materiels')->insertGetId($data);
			
			if($re_id){
				$this->outDepot($data['materiel_goods_ids'],$re_id);
				
				$re=$this->detail($re_id);

				if($re['state']==1){
					return $re=[
						'state'=>2,
						'page'=>$re['page']	
					];		
				}
					return $re=[
						'state'=>-1,
						'page'=>'找不页面'
					];					
				
				
			}
			
		}
		//出库参与计算方法
		public function outDepot($arr,$re_id,$flag=0){
			$arr=explode(',',$arr);
			foreach($arr as $k=>$v){
				
				$addRe=db('depot_press_materiels_goods')->where('materiel_id',$v)->update([
				//	'materiel_out_pid'=>
					'materiel_is_in'=>$flag,
					'materiel_main_table_pid'=>$re_id
				]);
				
			}
		}
		
		
		
		
		
		//物料出库
		public function deliveryOfCargoFromStorageAddhandle(){
			
			$data=request()->post();
			$data['materiel_add_time']=time();
			$materiel_pid=$data['materiel_pid'];
			//保持物料变更状态
			$re_id=db('depot_press_materiels')->insertGetId($data);
			if($re_id){
				//更新物料数据
				$meteriel_number=db('depot_materiels')->find($materiel_pid)['meteriel_number'];
				
				$new_meteriel_number=$meteriel_number-$data['meteriel_number'];
				
				$update_re=db('depot_materiels')->where('materiel_id',$materiel_pid)->update([
					'meteriel_number'=>$new_meteriel_number
				]);	
				$re=[
					'status'=>1,
					'page'=>'保存状态成功',
				];
			}else{
				$re=[
					'status'=>0,
					'msg'=>'保存状态错误',
				];
			}
			return $re;
		}

		
		//异步校验字段
		public function ajaxCheck(){
			
			if(request()->isAjax()){
				$admin_post=request()->post();
				$key=array_keys($admin_post)[0];
				$validate=validate('DepotPress');
				if(!$validate->scene($key)->check($admin_post))
				{
					return array('state'=>'0','msg'=>$validate->getError());
				}else{
					return array('state'=>'1','msg'=>'名称可以使用');
				}
			}			
		}
		
		public function routineCB($table_id){
			//声明更新后的数据
			$new_meteriel_number=-1;
//				//读取子数据库中的物料信息

			//记录关联批次id，便于查询本批次剩余数据
			$materiels_goods_number=0;
			//获取主表数据
			$DepotPressMateriels_model=model('DepotPressMateriels');
			$DepotPressMateriels_data=$DepotPressMateriels_model->get($table_id);
			$DepotPressMateriels_data->DepotPressMaterielsGoods;
			
			
			$DepotPressMateriels_arr=$DepotPressMateriels_data->toArray();
			//遍历字表数据
			foreach($DepotPressMateriels_arr['DepotPressMaterielsGoods'] as $k=>$v){
				//记录当前字表id，便于同步本次结存寻找id,并更新
				$materiel_id=$v['materiel_id'];
				//判断是否入库，查找pid。如果是出库，关联上级，根绝上级id-》pid查找
				if($v['materiel_is_in']){
					$pid=$v['materiel_pid'];
				}else{
					$DepotPressMaterielsGoods=model('DepotPressMaterielsGoods');
					$obj=$DepotPressMaterielsGoods->get($v['materiel_id']);
					$obj->DepotMaterielsGoods;
					
					$arr=$obj->toArray();
					
					$pid=$arr['DepotMaterielsGoods']['materiel_pid'];
				}
				//获取仓库数据数量
				$meteriel_number=db('depot_materiels')->find($pid)['materiel_number'];
				//校验上次本物料数据变更是否正确
				
				$this->checkMaterielNum($v['materiel_out_pid'],$meteriel_number);
				//判断进出库，进行计算
				if($v['materiel_is_in']){
					$new_meteriel_number=$meteriel_number+$v['materiel_number'];
					//存储到仓库实体数据表中
					unset($v['materiel_id']);
					//判断是否存在相同批次，如果存在则合并
					$where=[
						'materiel_batch_number'=>$v['materiel_batch_number'],
						'materiel_pid'=>$v['materiel_pid']
					];
					
					$depot_materiels_goods_check_re=db('depot_materiels_goods')->where($where)->find();
					//如果存在相同批次的，则合并数量
					if($depot_materiels_goods_check_re){
						$new_depot_materiels_goods_check_number=$depot_materiels_goods_check_re['materiel_number']+$v['materiel_number'];
						$materiel_check_updata_re=db('depot_materiels_goods')->where('materiel_id',$depot_materiels_goods_check_re['materiel_id'])
						->update([
							'materiel_number'=>$new_depot_materiels_goods_check_number
						]);
						$materiels_goods_number=$new_depot_materiels_goods_check_number;
						if(!$materiel_check_updata_re){
							dump('物料合并失败');die;
						}
						
						
					}else{
						unset($v['materiels_goods_number']);
						$re=db('depot_materiels_goods')->insertGetId($v);
						if(!$re){
							dump('物料更新到批次表失败');die;
						}
						$materiels_goods_number=$v['materiel_number'];

					}
					
							
					//dump($re);	
				}else{
					$new_meteriel_number=$meteriel_number-$v['materiel_number'];
					//更新仓库实体批次表，便于同步计算物料总数据，和批次数据，总量的减少，也会体现在对应批次上
					//查找批次表数据，用于和进出计算
					$materiel_number=db('depot_materiels_goods')->find($v['materiel_pid'])['materiel_number'];
					$new_meteriel_goods_number=$materiel_number-$v['materiel_number'];
					

					$update_meteriel_re=db('depot_materiels_goods')->where('materiel_id',$v['materiel_pid'])
						->update([
							'materiel_number'=>$new_meteriel_goods_number
						]);
						if(!$update_meteriel_re){
							dump('物料批次数据更新失败');die;
						}
						$materiels_goods_number=$new_meteriel_goods_number;
				}
				if($new_meteriel_number<0){
					dump('物料数据异常！请检查数据出入数量');die;
				}
				
				//用户回滚备份物料数量数据
				$roll_materiel_number=db('depot_materiels')->find($pid)['materiel_number'];
					
				$depot_materiels=db('depot_materiels')->where('materiel_id',$pid)->update([
					'materiel_number'=>$new_meteriel_number
				]);
				//更新本次结存到表中				
				$depot_press_materiels_goods_re=db('depot_press_materiels_goods')
					->where('materiel_id',$materiel_id)
					->update([
						'materiel_this_number'=>$new_meteriel_number,
						'materiel_is_add'=>1,
						'materiels_goods_number'=>$materiels_goods_number
					]);
					
				//更新仓库物料数据
				
									
				//如果添加失败，则回滚
				if(!($depot_press_materiels_goods_re&&$depot_materiels)){
					//更新本次结存到表中		sad		askjd
					$roll_depot_press_materiels_goods_re=db('depot_press_materiels_goods')
						->where('materiel_id',$materiel_id)
						->update([
							'materiel_this_number'=>0,
							'materiel_is_add'=>0
						]);
					if(!$roll_depot_press_materiels_goods_re){
						dump('物料进出库数据回滚失败');die;
					}					
					
					$roll_depot_materiels=db('depot_materiels')->where('materiel_id',$pid)->update([
						'materiel_number'=>$roll_materiel_number
					]);
					
					if(!$roll_depot_materiels){
						dump('物料数据回滚失败');die;
					}
					
				}
			}
			
			return true;
		}
		
		//校验仓库数据dsa///
		public function checkMaterielNum($materiel_out_pid,$materiel_number){
			$materiel_this_id=db('depot_press_materiels_goods')->where([
				'materiel_is_add'=>1,
				'materiel_out_pid'=>$materiel_out_pid,
			])->max('materiel_id');
			if($materiel_this_id){
				$materiel_this_number=db('depot_press_materiels_goods')->find($materiel_this_id)['materiel_this_number'];
				if($materiel_this_number!=$materiel_number){
					dump('物料上次结存数据校验异常！请检查数据出入数量');die;
				}				
			}
		}
		
		//校验本物品是否存在相同批次，然后提示合并
		public function checkbatch(){
			$materiel_batch_number=input('materiel_batch_number');
			$materiel_id=input('materiel_id');
			$where=[
				'materiel_out_pid'=>$materiel_id,
				'materiel_batch_number'=>$materiel_batch_number
			];
			
			$re=db('depot_materiels_goods')->where($where)->find();
			if($re){
				return '当前批号："'.$re['materiel_batch_number'].'" 已存在，默认数量将自动合并！';
			}
			
		}
		
		
	}
