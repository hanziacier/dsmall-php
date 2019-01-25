<?php

namespace app\home\controller;
use think\Lang;
class Pointgrade extends BasePointShop
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        Lang::load(APP_PATH.'home/lang/'.config('default_lang').'/pointgrade.lang.php');
        if (session('is_login') != '1'){
            $this->error(lang('no_login'),url('Login/login'));
        }
    }

    public function index(){
        //查询会员及其附属信息
        $result = parent::pointshopMInfo(true);
        $member_info = $result['member_info'];
        unset($result);

        $member_model = model('member');
        //获得会员升级进度
        $membergrade_arr = $member_model->getMemberGradeArr(true, $member_info['member_exppoints'],$member_info['level']);
        $this->assign('membergrade_arr', $membergrade_arr);

        //处理经验值计算说明文字
        $exppoints_rule = config("exppoints_rule")?unserialize(config("exppoints_rule")):array();
        $ruleexplain_arr = array();
        $exppoints_rule['exp_orderrate'] = floatval($exppoints_rule['exp_orderrate']);
        if ($exppoints_rule['exp_orderrate'] > 0){
            $ruleexplain_arr['exp_order'] = lang('empirical_value_calculated_valid_shopping')."{$exppoints_rule['exp_orderrate']}". lang('ds_yuan')."=1". lang('experience');
            $exp_ordermax = intval($exppoints_rule['exp_ordermax']);
            if ($exp_ordermax > 0){
                $ruleexplain_arr['exp_order'] .= lang('single_order_most_available')."{$exppoints_rule['exp_ordermax']}". lang('experience');
            }
        }
        $exppoints_rule['exp_login'] = intval($exppoints_rule['exp_login']);
        if ($exppoints_rule['exp_login'] > 0){
            $ruleexplain_arr['exp_login'] = lang('members_first_logged_each_day')."{$exppoints_rule['exp_login']}". lang('experience');
        }
        $exppoints_rule['exp_comments'] = intval($exppoints_rule['exp_comments']);
        if ($exppoints_rule['exp_comments'] > 0){
            $ruleexplain_arr['exp_comments'] = lang('review_order_will_obtained')."{$exppoints_rule['exp_comments']}". lang('experience');
        }
        $this->assign('ruleexplain_arr', $ruleexplain_arr);

        //分类导航
        $nav_link = array(
            0=>array('title'=>lang('homepage'),'link'=>HOME_SITE_URL),
            1=>array('title'=>lang('ds_pointprod'),'link'=>url('Pointshop/index')),
            2=>array('title'=> lang('my_growth'))
        );
        $this->assign('nav_link_list', $nav_link);
        return $this->fetch($this->template_dir.'pointgrade');
    }
    /**
     * 经验明细列表
     */
    public function exppointlog(){
        //查询会员及其附属信息
        $result = parent::pointshopMInfo();

        //查询积分日志列表
        $exppoints_model = model('exppoints');
        $where = array();
        $where['explog_memberid'] = session('member_id');
        $list_log = $exppoints_model->getExppointslogList($where, '*', 20, 'explog_id desc');
        //信息输出
        $this->assign('stage_arr',$exppoints_model->getExppointsStage());
        $this->assign('show_page', $exppoints_model->page_info->render());
        $this->assign('list_log',$list_log);
        //分类导航
        $nav_link = array(
            0=>array('title'=>lang('homepage'),'link'=>HOME_SITE_URL),
            1=>array('title'=>lang('ds_pointprod'),'link'=>url('Pointshop/index')),
            2=>array('title'=> lang('experience_details'))
        );
        $this->assign('nav_link_list', $nav_link);
        return $this->fetch($this->template_dir.'point_exppointslog');
    }
}