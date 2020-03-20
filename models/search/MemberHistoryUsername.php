<?php
/**
 * MemberHistoryUsername
 *
 * MemberHistoryUsername represents the model behind the search form about `ommu\member\models\MemberHistoryUsername`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 31 October 2018, 04:33 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

namespace ommu\member\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\member\models\MemberHistoryUsername as MemberHistoryUsernameModel;

class MemberHistoryUsername extends MemberHistoryUsernameModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'member_id', 'updated_id'], 'integer'],
			[['username', 'updated_date', 'member_search', 'updated_search', 'profile_search'], 'safe'],
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
			$query = MemberHistoryUsernameModel::find()->alias('t');
		else
			$query = MemberHistoryUsernameModel::find()->alias('t')->select($column);
		$query->joinWith([
			'member member', 
			'updated updated', 
			'member.profile.title profile'
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
		$attributes['member_search'] = [
			'asc' => ['member.displayname' => SORT_ASC],
			'desc' => ['member.displayname' => SORT_DESC],
		];
		$attributes['updated_search'] = [
			'asc' => ['updated.displayname' => SORT_ASC],
			'desc' => ['updated.displayname' => SORT_DESC],
		];
		$attributes['profile_search'] = [
			'asc' => ['profile.message' => SORT_ASC],
			'desc' => ['profile.message' => SORT_DESC],
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
			't.member_id' => isset($params['member']) ? $params['member'] : $this->member_id,
			'cast(t.updated_date as date)' => $this->updated_date,
			't.updated_id' => isset($params['updated']) ? $params['updated'] : $this->updated_id,
			'member.profile_id' => isset($params['profile']) ? $params['profile'] : $this->profile_search,
		]);

		$query->andFilterWhere(['like', 't.username', $this->username])
			->andFilterWhere(['like', 'member.displayname', $this->member_search])
			->andFilterWhere(['like', 'updated.displayname', $this->updated_search]);

		return $dataProvider;
	}
}
