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

class GroupsController extends AppController
{

	public $components = array(
		'Paginator',
		'Security' => array(
			'csrfUseOnce' => false,
		),
	);

	public function admin_index()
	{
		$this->Group->recursive = 0;
		$this->Group->virtualFields['course_title'] = 'GroupCourse.course_title'; // 外部結合テーブルのフィールドによるソート用
		
		$this->Paginator->settings = array(
			'fields' => array('*', 'GroupCourse.course_title'),
			'limit' => 20,
			'order' => 'created desc',
			'joins' => array(
				array('type' => 'LEFT OUTER', 'alias' => 'GroupCourse',
						'table' => '(SELECT gc.group_id, group_concat(c.title order by c.id SEPARATOR \', \') as course_title FROM ib_groups_courses gc INNER JOIN ib_courses c ON c.id = gc.course_id  GROUP BY gc.group_id)',
						'conditions' => 'Group.id = GroupCourse.group_id')
			)
		);
		
		$this->set('groups', $this->Paginator->paginate());
	}

	public function admin_add()
	{
		$this->admin_edit();
		$this->render('admin_edit');
	}

	public function admin_edit($id = null)
	{
		if ($this->action == 'edit' && ! $this->Group->exists($id))
		{
			throw new NotFoundException(__('Invalid group'));
		}
		if ($this->request->is(array(
				'post',
				'put'
		)))
		{
			if ($this->Group->save($this->request->data))
			{
				$this->Flash->success(__('グループ情報を保存しました'));
				return $this->redirect(array(
						'action' => 'index'
				));
			}
			else
			{
				$this->Flash->error(__('The group could not be saved. Please, try again.'));
			}
		}
		else
		{
			$options = array(
					'conditions' => array(
							'Group.' . $this->Group->primaryKey => $id
					)
			);
			$this->request->data = $this->Group->find('first', $options);
		}
		
		$courses = $this->Group->Course->find('list');
		$this->set(compact('courses'));
	}

	public function admin_delete($id = null)
	{
		$this->Group->id = $id;
		if (! $this->Group->exists())
		{
			throw new NotFoundException(__('Invalid group'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Group->delete())
		{
			$this->Flash->success(__('グループ情報を削除しました'));
		}
		else
		{
			$this->Flash->error(__('The group could not be deleted. Please, try again.'));
		}
		return $this->redirect(array(
				'action' => 'index'
		));
	}
}
