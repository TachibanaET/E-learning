<?php
/*
  @author: GUO ENFU
*/
App::uses('AppController', 'Controller');
class LearningTimesController extends AppController{
 
  public $uses = array('User','LearningTime','Model','Setting','Theme');

  public function admin_index(){

      $count = $this->User->countId();
      
      $themeAll = $this->Theme->findThemeAll();
//      debug($themeAll);

      $theme_selected = "";
      $this->set('theme_selected',$theme_selected);

      $select_list = [];
      
      foreach($themeAll as $row){
        $id = $row['ib_themes']['id'];
        $theme = $row['ib_themes']['theme'];
        $array = array(
          "$id" => $theme
        );
        array_push($select_list,$array);
      }

      $this->set('select_list',$select_list);
   
      if($this->request->is('post')){
        //$this->log($this->request->data);
 
        if(isset($this->request->data['search'])){

          $theme_id = $this->request->data['Theme']['theme'];

          $theme_selected = $this->LearningTime->getTheme($theme_id);

          $theme_selected = $theme_selected['theme'];


          $this->set('theme_selected',$theme_selected);

          $all = $this->LearningTime->getIdAndUserId($theme_selected);
          
          //$this->log($all);

          $week_array = [];
          $month_array = [];
          $untilNow_array = [];

          foreach($all as $row){
            $theme_id = $row['ib_themes']['id'];  
            $user_id = $row['ib_themes']['user_id'];
            
            $user_info = $this->User->find('all',
              array(
                'conditions' => array(
                'User.id' => "$user_id"
              ) 
              )
            );
            //$this->log($user_info);
            if(!empty($user_info)){
              $user_name = $user_info[0]['User']['name'];
            
            /*Week data */
              $array = array(
                    'username' => $user_name,
                    'sum' => $this->LearningTime->weekSumById($theme_id)
              );
              if($array['sum'] != 0){
                array_push($week_array,$array);
              }

            /*Month data*/
              $array = array(
                    'username' => $user_name,
                    'sum' => $this->LearningTime->monthSumById($theme_id)
              );
              if($array['sum'] != 0){
                array_push($month_array,$array);
              }

            /*UntilNow data*/
            //$this->log($this->LearningTime->allSumById($theme_id));
              $array = array(
                    'username' => $user_name,
                    'sum' => $this->LearningTime->allSumById($theme_id)
              );
              if($array['sum'] != 0){
                array_push($untilNow_array,$array);
              }
            }
          }
          //Sort
          foreach($week_array as $key => $value){
            $sort[$key] = $value['sum'];
          }
          if(!empty($week_array)){
            array_multisort($sort,SORT_DESC,$week_array);
          }
          $this->set('WeekAllData',$week_array);


          //Sort
          foreach($month_array as $key => $value){
            $sort[$key] = $value['sum'];
          }
          array_multisort($sort,SORT_DESC,$month_array);
          $this->set('MonthAllData',$month_array);


          //Sort
          foreach($untilNow_array as $key => $value){
            $sort[$key] = $value['sum'];
          }
          array_multisort($sort,SORT_DESC,$untilNow_array);
          $this->set('UntilNowData',$untilNow_array);
        }
      }

      //$WeekAllData = $this->LearningTime->findWeekAll($count);
      //$MonthAllData = $this->LearningTime->findMonthAll($count);

      /*週間データ*/
      /*$week_array = [];
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
      $this->set('WeekAllData',$week_array);*/

      /*月間データ*/ 
      /*$month_array = [];
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
      $this->set('MonthAllData',$month_array);*/
      
  }
}
