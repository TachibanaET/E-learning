<?php
/* 
  @author: GUO ENFU
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
  //User

  public function findDay($user_id, $theme_id){
    $sql = "SELECT SUM(time) as day FROM ib_learning_times WHERE (user_id = $user_id 
        AND DAY(created) = DAY(NOW()) 
        AND MONTH(created) = MONTH(NOW())
        AND theme_id = $theme_id)";    
    $d_data = $this->query($sql);
    if($d_data[0][0]['day']){
      return $d_data[0][0];
    }else{
      return array();
    }
  }

  //User
  public function findWeek($user_id, $theme_id){
    $sql = "SELECT SUM(time) as week FROM ib_learning_times WHERE (user_id = $user_id AND WEEK(created) = WEEK(NOW()) AND theme_id = $theme_id )";    
    $w_data = $this->query($sql);
    if($w_data[0][0]['week']){
      return $w_data[0][0];
    }else{
      return array();
    }
  }
  //User
  public function findWeekData($user_id, $theme_id){
    $sql =     
      "SELECT (dayofweek(created)) AS week,
      SUM(time) AS sum
      FROM ib_learning_times
      WHERE (user_id = $user_id AND WEEK(created) = WEEK(NOW()) AND theme_id = $theme_id)
      GROUP BY week ORDER BY week ASC";
    $wd_data = $this->query($sql);
    return $wd_data;
  }
  //User
  public function findMonth($user_id, $theme_id){
    $sql = "SELECT SUM(time) as month FROM ib_learning_times WHERE (user_id = $user_id AND MONTH(created) = MONTH(NOW()) AND theme_id = $theme_id )";    
    $m_data = $this->query($sql);
    if($m_data[0][0]['month']){
      return $m_data[0][0];
    }else{
      return array();
    }
  }
  //Admin 
  public function findWeekAll($count){
    $sql = "SELECT user_id,SUM(time) as sum FROM ib_learning_times 
        WHERE user_id <= $count and (WEEK(created) = WEEK(NOW())) 
        GROUP BY user_id ORDER BY sum DESC";
    $weekAllData = $this->query($sql);
    return $weekAllData;
  }
  //Admin
  public function findMonthAll($count){
    $sql = "SELECT user_id,SUM(time) as sum FROM ib_learning_times 
        WHERE user_id <= $count and (MONTH(created) = MONTH(NOW())) 
        GROUP BY user_id ORDER BY sum DESC";
    $data = $this->query($sql);
    return $data;
  }
  //User
  public function findMonthUserAll($user_id, $theme_id){
    $sql = "SELECT DATE_FORMAT(created, '%Y/%m/%d') as date,
      SUM(time) as sum
      FROM ib_learning_times
      WHERE (user_id = $user_id AND MONTH(created) = MONTH(NOW()) AND theme_id = $theme_id )
      GROUP BY date ORDER BY date ASC";    
    $data = $this->query($sql);
    return $data;
  }
  //User  
  public function rank($user_id, $theme_id){
    $sql = "SELECT user_id, SUM(time) as sum FROM ib_learning_times WHERE (MONTH(created) = MONTH(NOW()) AND theme_id = $theme_id ) GROUP BY user_id ORDER BY sum DESC";
    $rows = $this->query($sql);
    foreach($rows as $row){
      if((int)$row['ib_learning_times']['user_id'] == $user_id){
        $user_sum = (int)$row[0]['sum'];
      }
    }
    $rank = 0;
    foreach($rows as $row){
      $rank++;
      if((int)$row[0]['sum'] == $user_sum){
        break;
      }
    }
    return $rank;
  }
  //User
  public function findUserAll($user_id, $theme_id){
    $sql = "SELECT DATE_FORMAT(created, '%Y/%m/%d') as date,
      SUM(time) as sum
      FROM ib_learning_times
      WHERE (user_id = $user_id ) AND (theme_id = $theme_id )
      GROUP BY date ORDER BY date ASC"; 
    $data = $this->query($sql);
    return $data;
  }
  //Admin && User 
  public function getTheme($theme_id){
    $sql = "SELECT theme FROM ib_themes WHERE id = $theme_id"; 
    $data = $this->query($sql);
    return $data[0]['ib_themes'];
  }
  //User
  public function getUserSumAll($user_id,$theme_id){
    $sql = "SELECT SUM(time) as sum FROM ib_learning_times WHERE(user_id = $user_id AND theme_id = $theme_id)";
    $data = $this->query($sql);
    if($data[0][0]['sum']){
      return $data[0][0];
    }else{
      return array();
    }
  }
  //Admin
  public function weekSumById($id){
    $sql = "SELECT SUM(time) as sum FROM ib_learning_times WHERE(theme_id = $id and WEEK(created) = WEEK(NOW()))";
    $data = $this->query($sql);
    return $data[0][0]['sum'];
  }
  //Admin
  public function monthSumById($id){
    $sql = "SELECT SUM(time) as sum FROM ib_learning_times WHERE(theme_id = $id and MONTH(created) = MONTH(NOW()))";
    $data = $this->query($sql);
    return $data[0][0]['sum'];
  }
  //Admin
  public function allSumById($id){
    $sql = "SELECT SUM(time) as sum FROM ib_learning_times WHERE(theme_id = $id)";
    $data = $this->query($sql);
    //$this->log($data);
    return $data[0][0]['sum'];
  }
  //Admin
  public function getIdAndUserId($theme){
    $theme = "%".$theme."%";
    $sql = "SELECT id,user_id FROM ib_themes WHERE theme LIKE '$theme' GROUP BY user_id";
    $data = $this->query($sql);
    return $data;
  }
  
}

