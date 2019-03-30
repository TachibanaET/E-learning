<?php
/**
 * @author        GUO ENFU 
 */

App::uses('AppModel', 'Model');

class Theme extends AppModel
{
  public function findThemeAll(){
    $sql = "SELECT * FROM ib_themes GROUP BY theme ORDER BY id ASC";
    $data = $this->query($sql);
    return $data;
  }
	
	// 検索用
	public $actsAs = array(
		'Search.Searchable'
	);

	public $filterArgs = array(
	);
}
