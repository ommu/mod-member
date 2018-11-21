<?php
/**
 * MemberFriends
 *
 * MemberFriends represents the model behind the search form about `ommu\member\models\MemberFriends`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 31 October 2018, 13:53 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

namespace ommu\member\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\member\models\MemberFriends as MemberFriendsModel;

class MemberFriends extends MemberFriendsModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'type_id', 'user_id', 'request_id', 'modified_id'], 'integer'],
			[['request_date', 'modified_date', 'user_search', 'request_search', 'modified_search'], 'safe'],
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
	public function search($params)
	{
		$query = MemberFriendsModel::find()->alias('t');
		$query->joinWith([
			'type.title type', 
			'user user', 
			'request request', 
			'modified modified'
		]);

		// add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
		// disable pagination agar data pada api tampil semua
		if(isset($params['pagination']) && $params['pagination'] == 0)
			$dataParams['pagination'] = false;
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['type_id'] = [
			'asc' => ['type.message' => SORT_ASC],
			'desc' => ['type.message' => SORT_DESC],
		];
		$attributes['user_search'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['request_search'] = [
			'asc' => ['request.displayname' => SORT_ASC],
			'desc' => ['request.displayname' => SORT_DESC],
		];
		$attributes['modified_search'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.id' => $this->id,
			't.type_id' => isset($params['type']) ? $params['type'] : $this->type_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			't.request_id' => isset($params['request']) ? $params['request'] : $this->request_id,
			'cast(t.request_date as date)' => $this->request_date,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
		]);

		$query->andFilterWhere(['like', 'user.displayname', $this->user_search])
			->andFilterWhere(['like', 'request.displayname', $this->request_search])
			->andFilterWhere(['like', 'modified.displayname', $this->modified_search]);

		return $dataProvider;
	}
}
