<?php

class Model_User extends PhalApi_Model_NotORM {


	/* 判断昵称是否重复 */
	public function checkName($uid,$name){
	
		$isexist=DI()->notorm->users
					->select('id')
					->where('id!=? and user_nickname=?',$uid,$name)
					->fetchOne();

		if($isexist){
			return 0;
		}else{
			return 1;
		}
	}
	
	/* 修改信息 */
	public function userUpdate($uid,$fields){
		/* 清除缓存 */
		delCache("userinfo_".$uid);
        
        if(!$fields){
            return false;
        }

		return DI()->notorm->users
					->where('id=?',$uid)
					->update($fields);
	}

	/* 修改密码 */
	public function updatePass($uid,$oldpass,$pass){
		$userinfo=DI()->notorm->users
					->select("user_pass")
					->where('id=?',$uid)
					->fetchOne();
		$oldpass=setPass($oldpass);							
		if($userinfo['user_pass']!=$oldpass){
			return 1003;
		}							
		$newpass=setPass($pass);
		return DI()->notorm->users
					->where('id=?',$uid)
					->update( array( "user_pass"=>$newpass ) );
	}
	/* 历史 */
	public function history($uid,$p){
		$p = (is_numeric( $p ) && $p >1)? $p:1; 
		$pnum=10;
		$pstart = ($p-1)*$pnum;
		$rs=DI()->notorm->history
			->where('uid = ?',$uid)
			->order("addtime desc")
			->limit($pstart,$pnum)
			->fetchAll();

		return $rs;					
	}
	public function cleanhistory($uid){

		$rs=DI()->notorm->history
			->where('uid = ?',$uid)
			->delete();

		return $rs;					
	}	
	public function delhistory($id){
		$rs=DI()->notorm->history
			->where('newsid = ?',$id)
			->delete();
		return $rs;					
	}		
	
	/* 历史 */
	public function getnews($id){
		$rs=DI()->notorm->news_page
			->where('id = ?',$id)
			->fetchOne();
		return $rs;					
	}
	/* myupdate */
	public function myupdate($uid,$p){
		$p = (is_numeric( $p ) && $p >1)? $p:1; 
		$pnum=10;
		$pstart = ($p-1)*$pnum;
		$rs=DI()->notorm->think
			->where('uid = ?',$uid)
			->order("addtime desc")
			->limit($pstart,$pnum)
			->fetchAll();
		return $rs;						
	}
	/* 收藏 */
	public function collect_news($uid,$p){

		$p = (is_numeric( $p ) && $p >1)? $p:1; 
		$pnum=10;
		$pstart = ($p-1)*$pnum;
		$rs=DI()->notorm->zan_collect
			->where('uid = ? and type = 0 and collect=1',$uid)
			->order("collecttime desc")
			->limit($pstart,$pnum)
			->fetchAll();

		return $rs;					
	}
	public function collect_think($uid,$p){

		$p = (is_numeric( $p ) && $p >1)? $p:1; 
		$pnum=10;
		$pstart = ($p-1)*$pnum;
		$rs=DI()->notorm->zan_collect
			->where('uid = ? and type = 1 and collect=1',$uid)
			->order("collecttime desc")
			->limit($pstart,$pnum)
			->fetchAll();

		return $rs;					
	}	
	public function getthink($id){
		$rs=DI()->notorm->think
			->where('id = ?',$id)
			->fetchOne();
		return $rs;					
	}	
	public function delcollect($id,$type){
		$rs=DI()->notorm->zan_collect
			->where('newsid = ? and type =?',$id,$type)
			->delete();
		return $rs;					
	}
	/* 消息 */
	public function msg($uid,$p){

		$p = (is_numeric( $p ) && $p >1)? $p:1; 
		$pnum=10;
		$pstart = ($p-1)*$pnum;
		$rs=DI()->notorm->msg
			->where('uid = ? ',$uid)
			->order("addtime desc")
			->limit($pstart,$pnum)
			->fetchAll();

		return $rs;					
	}

//	积分历史
    public function getScoreHistory($uid)
    {
        $rs=DI()->notorm->scores_record
            ->where('uid = ? ',$uid)
            ->order("addtime desc")
            ->fetchAll();

        return $rs;
    }

//    兑换历史
    public function getDuihuanHistory($uid)
    {
        $rs=DI()->notorm->goods_record
            ->where('uid = ? ',$uid)
            ->order("addtime desc")
            ->fetchAll();

        $goods_data = DI()->notorm->goods->fetchAll();
        foreach ($rs as $k => $v) {
            foreach ($goods_data as $k2 => $v2) {
                if( $v['goods_id'] != $v2['id']){
                    continue;
                }
               $rs[$k]['goods_name'] = $v2['title'];
                $rs[$k]['score_price'] = $v2['score'];
            }
        }

        return $rs;
    }


    //支付党费
    public function payParty($data)
    {
        $history = DI()->notorm->partypay_record->where($data)->fetchOne();
        if ($history) {
            return false;
        }
        $data['addtime'] = time();
        $res = DI()->notorm->partypay_record->insert($data);

        return $res;
    }

    //获取用户权限
    public function getUserAuth()
    {
        $authData = DI()->notorm->front_auth->select('rule_name')->where(['status' => 1])->fetchAll();
        $arr = [];
        foreach ($authData as $k => $v) {
            $arr[] = $v['rule_name'];
        }
        return $arr;
    }


    /* 历史 */
    public function getscanning($id){
        $rs=DI()->notorm->scanning
            ->where('id = ?',$id)
            ->fetchOne();
        return $rs;
    }


}
