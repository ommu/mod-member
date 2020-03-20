<?php
/**
 * MemberViewHistory
 *
 * MemberViewHistory represents the model behind the search form about `ommu\member\models\MemberViewHistory`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 31 October 2018, 12:54 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

namespace ommu\member\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\member\models\MemberViewHistory as MemberViewHistoryModel;

class MemberViewHistory extends MemberViewHistoryModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'view_id'], 'integer'],
			[['view_date', 'view_ip', 'profile_search', 'member_search', 'userDisplayname'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Tambahkan fungsi beforeValidate ini pada model search untuk menumpuk validasi pd model induk. 
	 * dan "jangan" tambahkan parent::beforeValidate, cukup "return true" saja.
	 * maka validasi yg akan dipakai hanya pd model ini, semua script yg ditaruh di beforeValidate pada model induk
	 * tidak akan dijalankan.
	 */
	public function beforeValidate() {
		return true;
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params, $column=null)
	{
		if(!($column && is_array($column)))
			$query = MemberViewHistoryModel::find()->alias('t');
		else
			$query = MemberViewHistoryModel::find()->alias('t')->select($column);
		$query->joinWith([
			'view.member.profile.title profile',
			'view.member member', 
			'view.user user'
		])
		->groupBy(['id']);

		// add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
		// disable pagination agar data pada api tampil semua
		if(isset($params['pagination']) && $params['pagination'] == 0)
			$dataParams['pagination'] = false;
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['profile_search'] = [
			'asc' => ['profile.message' => SORT_ASC],
			'desc' => ['profile.message' => SORT_DESC],
		];
		$attributes['member_search'] = [
			'asc' => ['member.displayname' => SORT_ASC],
			'desc' => ['member.displayname' => SORT_DESC],
		];
		$attributes['userDisplayname'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

		if(Yii::$app->request->get('id'))
			unset($params['id']);
		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.id' => $this->id,
			't.view_id' => isset($params['view']) ? $params['view'] : $this->view_id,
			'cast(t.view_date as date)' => $this->view_date,
			'member.profile_id' => isset($params['profile']) ? $params['profile'] : $this->profile_search,
		]);

		$query->andFilterWhere(['like', 't.view_ip', $this->view_ip])
			->andFilterWhere(['like', 'member.displayname', $this->member_search])
			->andFilterWhere(['like', 'user.displayname', $this->userDisplayname]);

		return $dataProvider;
	}
}
