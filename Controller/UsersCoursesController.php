<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohaboard.irohasoft.jp
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

App::uses('AppController', 'Controller');

/**
 * UsersCourses Controller
 *
 * @property UsersCourse $UsersCourse
 * @property PaginatorComponent $Paginator
 */
class UsersCoursesController extends AppController
{
  public $uses = array('User','LearningTime','Model','Setting','UsersCourse');
	public $components = array(
	);
  public $validate = array(
    'time' => array(
      'rule' => 'naturalNumber',
      'message' => '０位上の値を入力してください。'
    )
  );

	public function index()
	{
		// 全体のお知らせの取得
		App::import('Model', 'Setting');
		$this->Setting = new Setting();
		
		$data = $this->Setting->find('all', array(
			'conditions' => array(
				'Setting.setting_key' => 'information'
			)
		));
		
		$info = $data[0]['Setting']['setting_value'];
		
		// お知らせ一覧を取得
		$this->loadModel('Info');
		$infos = $this->Info->getInfos($this->Session->read('Auth.User.id'), 2);
		
		$no_info = "";
		
		// 全体のお知らせもお知らせも存在しない場合
		if(($info=="") && count($infos)==0)
			$no_info = "お知らせはありません";
    /*20190303 勉強時間の提出*/
    $id = $this->Auth->User('id');
    $this->set('post_id',$id);
    if($this->request->is('post')){
      $this->LearningTime->create();
      if(isset($this->request->data['submit'])){
        //提出する時の処理
        $this->LearningTime->set($this->request->data);

        if($this->LearningTime->validates()){

          $this->LearningTime->create();
          if($this->LearningTime->save($this->request->data)){
            $this->Flash->success(__('提出しました、ありがとうございます'));
            return $this->redirect(array('action' => 'index'));
          }
            $this->Flash->error(__('提出は失敗しました、もう一回やってください。'));
        }
      }elseif(isset($this->request->data['learningtime'])){
        return $this->redirect(array(
          'action' => 'learningtime',
          $id
        ));
      }
    }
    /********************/
		
		// 受講コース情報の取得
		$courses = $this->UsersCourse->getCourseRecord( $this->Session->read('Auth.User.id') );
		
		$no_record = "";
		
		if(count($courses)==0)
			$no_record = "受講可能なコースはありません";
		
		$this->set(compact('courses', 'no_record', 'info', 'infos', 'no_info'));
	}
  /* 20190303 public function learningtime()を作成 */
  public function learningtime(){
    $id = $this->Auth->User('id');
    $this->set('post_id',$id);
    
    $d_data = $this->LearningTime->findDay($id);
    $this->set('d_data',$d_data); 
    
    $w_data = $this->LearningTime->findWeek($id);
    $this->set('w_data',$w_data);
    
    $wd_data = $this->LearningTime->findWeekData($id);
    foreach($wd_data as &$wdata):
      switch($wdata[0]['week']){
        case 1:
          $wdata[0]['week'] = "日曜日";
          break;
        case 2:
          $wdata[0]['week'] = "月曜日";
          break;
        case 3:
          $wdata[0]['week'] = "火曜日";
          break;
        case 4:
          $wdata[0]['week'] = "水曜日";
          break;
        case 5:
          $wdata[0]['week'] = "木曜日";
          break;
        case 6:
          $wdata[0]['week'] = "金曜日";
          break;
        case 7:
          $wdata[0]['week'] = "土曜日";
          break;
      };
    endforeach;
    unset($wdata);
    $this->set('wd_data',$wd_data);
    
    $m_data = $this->LearningTime->findMonth($id);
    $this->set('m_data',$m_data);
    
    $ma_data = $this->LearningTime->findMonthUserAll($id);
    $this->set('ma_data',$ma_data);
  }

}
