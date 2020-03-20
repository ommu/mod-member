<?php
/**
 * Members
 *
 * Members represents the model behind the search form about `ommu\member\models\Members`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 30 October 2018, 22:51 WIB
 * @modified date 4 November 2018, 05:14 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

namespace ommu\member\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\member\models\Members as MembersModel;

class Members extends MembersModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['member_id', 'publish', 'approved', 'profile_id', 'member_private', 'approved_id', 'creation_id', 'modified_id'], 'integer'],
			[['username', 'displayname', 'photo_header', 'photo_profile', 'short_biography', 'approved_date', 'creation_date', 'modified_date', 'updated_date', 'approved_search', 'creationDisplayname', 'modifiedDisplayname'], 'safe'],
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
			$query = MembersModel::find()->alias('t');
		else
			$query = MembersModel::find()->alias('t')->select($column);
		$query->joinWith([
			'profile.title profile', 
			'approvedRltn approvedRltn', 
			'creation creation', 
			'modified modified'
		])
		->groupBy(['member_id']);

		// add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
		// disable pagination agar data pada api tampil semua
		if(isset($params['pagination']) && $params['pagination'] == 0)
			$dataParams['pagination'] = false;
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['profile_id'] = [
			'asc' => ['profile.message' => SORT_ASC],
			'desc' => ['profile.message' => SORT_DESC],
		];
		$attributes['approved_search'] = [
			'asc' => ['approvedRltn.displayname' => SORT_ASC],
			'desc' => ['approvedRltn.displayname' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['member_id' => SORT_DESC],
		]);

		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.member_id' => $this->member_id,
			't.approved' => $this->approved,
			't.profile_id' => isset($params['profile']) ? $params['profile'] : $this->profile_id,
			't.member_private' => $this->member_private,
			'cast(t.approved_date as date)' => $this->approved_date,
			't.approved_id' => isset($params['approved']) ? $params['approved'] : $this->approved_id,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
		]);

		if(isset($params['trash']))
			$query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
		else {
			if(!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == ''))
				$query->andFilterWhere(['IN', 't.publish', [0,1]]);
			else
				$query->andFilterWhere(['t.publish' => $this->publish]);
		}

		$query->andFilterWhere(['like', 't.username', $this->username])
			->andFilterWhere(['like', 't.displayname', $this->displayname])
			->andFilterWhere(['like', 't.photo_header', $this->photo_header])
			->andFilterWhere(['like', 't.photo_profile', $this->photo_profile])
			->andFilterWhere(['like', 't.short_biography', $this->short_biography])
			->andFilterWhere(['like', 'approvedRltn.displayname', $this->approved_search])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
