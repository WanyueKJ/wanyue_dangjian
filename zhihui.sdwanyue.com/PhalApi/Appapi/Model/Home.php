<?php
session_start();
class Model_Home extends PhalApi_Model_NotORM {

    /* 轮播 */
    public function getSlide($id){

        $rs=DI()->notorm->slide_item
            ->select("image,url")
            ->where("status='1' and slide_id=? ",$id)
            ->order("list_order asc")
            ->fetchAll();
        foreach($rs as $k=>$v){
            $rs[$k]['image']=get_upload_path($v['image']);
        }

        return $rs;
    }


    /* 通知公告 */
    public function notice($p){
        $p = (is_numeric($p) && $p >1)? $p:1;
        $pnum=10;
        $pstart = ($p-1)*$pnum;
        $rs=DI()->notorm->notice
            ->where('id>?',1)
            ->order("list_order asc,id desc")
            ->limit($pstart,$pnum)
            ->fetchAll();

        return $rs;
    }

    public function isjoin($id,$uid){
        $check = DI()->notorm->user_ask->where('listid = ? and uid=?',$id,$uid)->fetchOne();
        if($check){
            return true;
        }else{
            return false;
        }
    }
    public function optionlist($listid){
        return DI()->notorm->ask->where('listid = ?',$listid)->fetchAll();
    }


    /* 党员列表 */
    public function getparter($branchid,$p){

        $p = (is_numeric( $p ) && $p >1)? $p:1;
        $pnum=10;
        $pstart = ($p-1)*$pnum;
        if($branchid==0){
            $rs=DI()->notorm->parter
                ->order("list_order asc,id asc")
                ->limit($pstart,$pnum)
                ->fetchAll();
        }else{
            $rs=DI()->notorm->parter
                ->where("partybranch=?",$branchid)
                ->order("list_order asc,id asc")
                ->limit($pstart,$pnum)
                ->fetchAll();
        }

        return $rs;
    }

    //党员详情
    public function getpartershow($id){

        $rs=DI()->notorm->parter
            ->where('id = ?',$id)
            ->fetchOne();

        return $rs;
    }

    /* 党建参阅 */
    public function getwindow($p,$pnum=10){
        $p = (is_numeric( $p ) && $p >1)? $p:1;
        //$pnum=10;
        $pstart = ($p-1)*$pnum;
        $rs=DI()->notorm->window
            ->order("list_order asc,id desc")
            ->limit($pstart,$pnum)
            ->fetchAll();

        return $rs;
    }

    /* 学习 */
    public function getlession($p,$pnum=10){
        $p = (is_numeric( $p ) && $p >1)? $p:1;
        //$pnum=10;
        $pstart = ($p-1)*$pnum;

        //隐藏党章相关数据
        $rs=DI()->notorm->lession
            ->order("list_order asc,id desc")
            ->limit($pstart,$pnum)
            ->fetchAll();

        return $rs;
    }

    //获取党史列表
    public function getDanghistoryList()
    {
        $list=DI()->notorm->danghistory
            ->limit(0,4)
            ->fetchAll();

        return $list;
    }


    /* 要闻速览 */
    public function getScanning($p){

        $p = (is_numeric( $p ) && $p >1)? $p:1;
        $pnum=6;
        $pstart = ($p-1)*$pnum;
        $rs=DI()->notorm->scanning
            ->order("list_order asc,id desc")
            ->limit($pstart,$pnum)
            ->fetchAll();
        return $rs;
    }

    //获取党史详情
    public function getKnowledgeById($id)
    {
        $list=DI()->notorm->knowledge
            ->where('hid =?', $id)
            ->fetchAll();

        return $list;
    }

    public function danghistoryList()
    {
        $list=DI()->notorm->danghistory
            ->fetchAll();

        return $list;
    }



}
