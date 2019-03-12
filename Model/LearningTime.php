<?php
/* 
  author: GUO ENFU
*/
App::uses('AppModel','Model');

class LearningTime extends AppModel{
  public $validate = array(
    'time' => array(
      'rule' => 'naturalNumber',
      'message' => '0以上の値を入力してください。',
      'allowEmpty' => true
    )
  );
  public function findDay($user_id){
    $sql = "SELECT SUM(time) as day FROM ib_learning_times WHERE (user_id = $user_id AND DAY(created) = DAY(NOW()))";    
    $d_data = $this->query($sql);
    if($d_data[0][0]['day']){
      return $d_data[0][0];
    }else{
      return array();
    }
  }

  public function findWeek($user_id){
    $sql = "SELECT SUM(time) as week FROM ib_learning_times WHERE (user_id = $user_id AND WEEK(created) = WEEK(NOW()))";    
    $w_data = $this->query($sql);
    if($w_data[0][0]['week']){
      return $w_data[0][0];
    }else{
      return array();
    }
  }

  public function findWeekData($user_id){
    /*$sql =     
      "SELECT(CASE
      WHEN DATE_FORMAT(created, '%W') = 'Monday'    THEN '月曜日'
      WHEN DATE_FORMAT(created, '%W') = 'Tuesday'   THEN '火曜日'
      WHEN DATE_FORMAT(created, '%W') = 'Wednesday' THEN '水曜日'
      WHEN DATE_FORMAT(created, '%W') = 'Thursday'  THEN '木曜日'
      WHEN DATE_FORMAT(created, '%W') = 'Friday'    THEN '金曜日'
      WHEN DATE_FORMAT(created, '%W') = 'Saturday'  THEN '土曜日'
      WHEN DATE_FORMAT(created, '%W') = 'Sunday'    THEN '日曜日'
      END) AS week,
      SUM(time) AS sum
      FROM ib_learning_times
      WHERE (user_id = $user_id AND WEEK(created) = WEEK(NOW()))
      GROUP BY week";*/
    $sql =     
      "SELECT (dayofweek(created)) AS week,
      SUM(time) AS sum
      FROM ib_learning_times
      WHERE (user_id = $user_id AND WEEK(created) = WEEK(NOW()))
      GROUP BY week ORDER BY week ASC";
    $wd_data = $this->query($sql);
    return $wd_data;
  }
  
  public function findMonth($user_id){
    $sql = "SELECT SUM(time) as month FROM ib_learning_times WHERE (user_id = $user_id AND MONTH(created) = MONTH(NOW()))";    
    $m_data = $this->query($sql);
    if($m_data[0][0]['month']){
      return $m_data[0][0];
    }else{
      return array();
    }
  }
  
  public function findWeekAll($count){
    $sql = "SELECT user_id,SUM(time) as sum FROM ib_learning_times 
        WHERE user_id <= $count and (WEEK(created) = WEEK(NOW())) 
        GROUP BY user_id ORDER BY sum DESC";
    $weekAllData = $this->query($sql);
    return $weekAllData;
  }

  public function findMonthAll($count){
    $sql = "SELECT user_id,SUM(time) as sum FROM ib_learning_times 
        WHERE user_id <= $count and (MONTH(created) = MONTH(NOW())) 
        GROUP BY user_id ORDER BY sum DESC";
    $data = $this->query($sql);
    return $data;
  }

  public function findMonthUserAll($user_id){
    $sql = "SELECT DATE_FORMAT(created, '%Y/%m/%d') as date,
      SUM(time) as sum
      FROM ib_learning_times
      WHERE (user_id = $user_id AND MONTH(created) = MONTH(NOW()))
      GROUP BY date ORDER BY date ASC";    
    $data = $this->query($sql);
    return $data;
  }
}
