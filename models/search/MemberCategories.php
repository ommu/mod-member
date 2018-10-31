<?php
/**
 * MemberCategories
 *
 * MemberCategories represents the model behind the search form about `ommu\member\models\MemberCategories`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 1 November 2018, 01:09 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

namespace ommu\member\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\member\models\MemberCategories as MemberCategoriesModel;

class MemberCategories extends MemberCategoriesModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'publish', 'member_id', 'profile_cat_id', 'creation_id', 'modified_id'], 'integer'],
			[['creation_date', 'modified_date', 'updated_date', 'member_search', 'creation_search', 'modified_search'], 'safe'],
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
		$query = MemberCategoriesModel::find()->alias('t');
		$query->joinWith([
			'member member', 
			'category.title category', 
			'creation creation', 
			'modified modified'
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
		$attributes['profile_cat_id'] = [
			'asc' => ['category.message' => SORT_ASC],
			'desc' => ['category.message' => SORT_DESC],
		];
		$attributes['creation_search'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
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
			't.member_id' => isset($params['member']) ? $params['member'] : $this->member_id,
			't.profile_cat_id' => isset($params['category']) ? $params['category'] : $this->profile_cat_id,
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

		$query->andFilterWhere(['like', 'member.displayname', $this->member_search])
			->andFilterWhere(['like', 'creation.displayname', $this->creation_search])
			->andFilterWhere(['like', 'modified.displayname', $this->modified_search]);

		return $dataProvider;
	}
}
