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
      
      $data_array = [];
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
        array_push($data_array,$array);
      endforeach;
      $this->set('WeekAllData',$data_array);
  }
}
