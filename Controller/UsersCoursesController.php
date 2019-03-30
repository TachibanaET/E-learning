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
  public $uses = array('User','LearningTime','Model','Setting','UsersCourse','Theme');
	public $components = array(
    'Paginator',
    'Search.Prg',
    'Session'
	);

  public $paginate = array();
  
  public $presetVars = array(
    array(
      'name' => 'name',
      'type' => 'value',
      'field' => 'User.name'
    ),
    array(
      'name' => 'username',
      'type' => 'like',
      'field' => 'User.username'
    ),
    array(
      'name' => 'theme',
      'type' => 'like',
      'field' => 'Theme.theme'
    ),
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

    /*20190312*/
    $conditions = $this->Theme->parseCriteria($this->Prg->parsedParams());
    
    $conditions['user_id'] = $id;

    $options = array(
      'conditions' => $conditions,
      'fileds' => array('Theme.theme'),
      'order' => 'Theme.created asc',
      'group' => 'Theme.theme'
    );

    $select_list = [];
     
    $rows = $this->Theme->find('all',$options);

    foreach($rows as $row){
      $theme_id = $row['Theme']['id'];
      $array = array(
        "$theme_id" => $row['Theme']['theme']
        );
        array_push($select_list,$array);
    }
    
    $this->set('select_list',$select_list);

    $today_date = (isset($this->request->query['today_date'])) ?
      $this->request->query['today_date']:
        array('year' => date('Y'), 'month' => date('m'), 'day' => date('d'));

    $this->set('today_date',$today_date);
    /*************/
    if($this->request->is('post')){
      
      if(isset($this->request->data['submit'])){
        //提出する時の処理
        $tmp = $this->request->data;
        

        $today_date = $tmp['LearningTime']['today_date'];

        $created = $today_date['year']."-".$today_date['month']."-".$today_date['day'];
       
        if($tmp['LearningTime']['theme']){ 
          
          $select_index = (int)$tmp['LearningTime']['theme'];
          //$theme_id = $rows[$index]['Theme']['id'];
          $tmp['LearningTime']['theme_id'] = $select_index;
        
          $tmp['LearningTime']['created'] = $created;

          $this->LearningTime->set($tmp);
        }elseif($tmp['LearningTime']['theme_new']){

          $array = array(
            'user_id' => $id,
            'theme' => $tmp['LearningTime']['theme_new']
          );
          $this->Theme->set($array);

          if($this->Theme->save($array)){
            $options = array(
              'conditions' => array(
                'Theme.theme' => $array['theme']
              ),
              'group' => 'Theme.theme'
            );
            $rows = $this->Theme->find('all',$options);
            //$this->log($rows);
            $tmp['LearningTime']['theme_id'] = $rows[0]['Theme']['id'];
            //unset($tmp['LearningTime']['theme_new']);
            //$this->log($tmp);
            $tmp['LearningTime']['created'] = $created;
            $this->LearningTime->set($tmp);
          }
          $this->Flash->error(__('提出は失敗しました、もう一回やってください。'));
        }
        
        //$this->LearningTime->set($this->request->data);
        $this->log($tmp);
        if($this->LearningTime->validates()){

          $this->LearningTime->create();
          if($this->LearningTime->save($tmp)){
            $this->Flash->success(__('提出しました、ありがとうございます'));
            return $this->redirect(array('action' => 'index'));
          }
            $this->Flash->error(__('提出は失敗しました、もう一回やってください。'));
        }/*else{
          throw new BadRequestException();

        }*/
      }elseif(isset($this->request->data['learningtime'])){
        $this->Session->write('key',$this->request->data['LearningTime']['theme']);
        //$this->log('key');
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
    /****20190313***/
    $id = $this->Auth->User('id');
    if($this->Session->check('key')){
      $theme_id = (int)$this->Session->read('key');
      $this->set('theme_id',$theme_id);
      //debug($theme_id);
    }else{
      throw new BadRequestException();
    }
   
    $this->set('post_id',$id);
    
    $d_data = $this->LearningTime->findDay($id,$theme_id);
    $this->set('d_data',$d_data); 
    
    $w_data = $this->LearningTime->findWeek($id, $theme_id);
    $this->set('w_data',$w_data);
    
    $wd_data = $this->LearningTime->findWeekData($id, $theme_id);
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
    
    $m_data = $this->LearningTime->findMonth($id, $theme_id);
    $this->set('m_data',$m_data);
    
    $ma_data = $this->LearningTime->findMonthUserAll($id, $theme_id);
    $this->set('ma_data',$ma_data);
    $rank = $this->LearningTime->rank($id, $theme_id);
    $this->set('rank',$rank);

    $untilNow = $this->LearningTime->findUserAll($id, $theme_id);
    $this->set('untilNow',$untilNow);
   
    $theme = $this->LearningTime->getTheme($theme_id);
    $this->set('theme',$theme); 
    
    $SA = $this->LearningTime->getUserSumAll($id,$theme_id);
    $this->set('sumAll',$SA);
    //debug($untilNow);
  }

}
