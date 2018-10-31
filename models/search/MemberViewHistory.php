<?php
/**
 * MemberViewHistory
 *
 * MemberViewHistory represents the model behind the search form about `ommu\member\models\MemberViewHistory`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
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
			[['view_date', 'view_ip', 'member_search', 'user_search'], 'safe'],
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
		$query = MemberViewHistoryModel::find()->alias('t');
		$query->joinWith([
			'view.member member', 
			'view.user user'
		]);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['member_search'] = [
			'asc' => ['member.displayname' => SORT_ASC],
			'desc' => ['member.displayname' => SORT_DESC],
		];
		$attributes['user_search'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
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
			't.view_id' => isset($params['view']) ? $params['view'] : $this->view_id,
			'cast(t.view_date as date)' => $this->view_date,
		]);

		$query->andFilterWhere(['like', 't.view_ip', $this->view_ip])
			->andFilterWhere(['like', 'member.displayname', $this->member_search])
			->andFilterWhere(['like', 'user.displayname', $this->user_search]);

		return $dataProvider;
	}
}
