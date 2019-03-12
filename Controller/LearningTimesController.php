<?php
/*
  author: GUO ENFU
*/
App::uses('AppController', 'Controller');
class LearningTimesController extends AppController{
 
  public $uses = array('User','LearningTime','Model','Setting');
  public function admin_index(){
      $count = $this->User->countId();
      $WeekAllData = $this->LearningTime->findWeekAll($count);
      $MonthAllData = $this->LearningTime->findMonthAll($count);
      /*週間データ*/
      $week_array = [];
      foreach($WeekAllData as $data):
        $user_id = $data['ib_learning_times']['user_id'];
        $time = $data[0]['sum'];
        $user_info = $this->User->find('all',
          array(
            'conditions' => array(
              'User.id' => "$user_id"
            )
          )
        );
        $user_name = $user_info[0]['User']['name'];
        $array = array(
                  'username' => $user_name,
                  'sum' => $time
        );
        array_push($week_array,$array);
      endforeach;
      $this->set('WeekAllData',$week_array);

      /*月間データ*/ 
      $month_array = [];
      foreach($MonthAllData as $data):
        $user_id = $data['ib_learning_times']['user_id'];
        $time = $data[0]['sum'];
        $user_info = $this->User->find('all',
          array(
            'conditions' => array(
              'User.id' => "$user_id"
            )
          )
        );
        $user_name = $user_info[0]['User']['name'];
        $array = array(
                  'username' => $user_name,
                  'sum' => $time
        );
        array_push($month_array,$array);
      endforeach;
      $this->set('MonthAllData',$month_array); 
      
  }
}
