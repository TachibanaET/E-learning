<?php
APP::uses('AppModel','Model');
class Soap extends AppModel{
  public function findSoap($user_id){
    $option = array(
      //検索条件
      'conditions'=>array( 
        'user_id' => $user_id
        ),
      //何順なのか
      'order'=> array(
        'Soap.created DESC'
      )
    );
    return $this->find('all',$option);
  }
}
