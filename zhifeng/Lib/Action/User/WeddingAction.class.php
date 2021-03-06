<?php
class WeddingAction extends UserAction{
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction('wedding');
		
	}
	//喜帖配置
	public function index(){
		$Wedding=M('Wedding');
		$where['token']=session('token');
		$count=$Wedding->where($where)->count();
		$page=new Page($count,25);
		$list=$Wedding->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$page->show());
		$this->assign('wedding',$list);
		$this->display();
	}
	public function add(){
		if(IS_POST){
			$_POST['time']=strtotime($this->_post('time'));
			$this->all_insert('Wedding','/index');
		}else{
			$photo=M('Photo')->where(array('token'=>session('token')))->select();
			$this->assign('photo',$photo);
			$this->display();
		}
	}
	public function edit(){
		$Wedding=M('Wedding')->where(array('token'=>session('token'),'id'=>$this->_get('id','intval')))->find();
		if(IS_POST){
			//dump($_POST);exit;
			$_POST['time']=strtotime($this->_post('time'));
			$_POST['id']=$Wedding['id'];
			$this->all_save('wedding','/index');	
		}else{
			$photo=M('Photo')->where(array('token'=>session('token')))->select();
			$this->assign('photo',$photo);
			$this->assign('wedding',$Wedding);
			$this->display('add');
		}
	
	}
	public function info(){
		$data=D('Wedding_info');
		$where['pid']=$this->_get('id','intval');
		$where['type']=$this->_get('type','intval')?$this->_get('type','intval'):1;
		$count=$data->where($where)->count();
		$page=new Page($count,25);
		$info=$data->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$page->show());
		$this->assign('wedding',$info);
		$this->assign('type',$where['type']);
		$this->display();
	}
	public function infodel(){
		$where['id']=$this->_get('id','intval');
		$info=M('Wedding_info')->where($where)->find();
		$back=M('Wedding')->where(array('token'=>session('token'),'pid'=>$info['pid']))->find();
		if($back==false){$this->error('非法操作');exit;}
		if(D('Wedding_info')->where($where)->delete()){
			$this->success('操作成功',U(MODULE_NAME.'/info'));
		}else{
			$this->error('操作失败',U(MODULE_NAME.'/info'));
		}
	}
	public function del(){
		$where['id']=$this->_get('id','intval');
		$where['token']=session('token');
		if(D('Wedding')->where($where)->delete()){
			$this->success('操作成功',U(MODULE_NAME.'/index'));
		}else{
			$this->error('操作失败',U(MODULE_NAME.'/index'));
		}
	}
}



?>