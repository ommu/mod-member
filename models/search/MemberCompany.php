<?php
/**
 * MemberCompany
 *
 * MemberCompany represents the model behind the search form about `ommu\member\models\MemberCompany`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 31 October 2018, 15:48 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

namespace ommu\member\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\member\models\MemberCompany as MemberCompanyModel;

class MemberCompany extends MemberCompanyModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'member_id', 'company_id', 'company_type_id', 'company_cat_id', 'company_country_id', 'company_province_id', 'company_city_id', 'company_zipcode', 'creation_id', 'modified_id'], 'integer'],
			[['info_intro', 'info_article', 'company_address', 'company_district', 'company_village', 'creation_date', 'modified_date', 'updated_date', 'member_i', 'creationDisplayname', 'modifiedDisplayname'], 'safe'],
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
			$query = MemberCompanyModel::find()->alias('t');
		else
			$query = MemberCompanyModel::find()->alias('t')->select($column);
		$query->joinWith([
			'member member', 
			'company.directory company', 
			'companyType.title companyType', 
			'companyCategory.title companyCategory', 
			'creation creation', 
			'modified modified',
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
		$attributes['member_i'] = [
			'asc' => ['member.displayname' => SORT_ASC],
			'desc' => ['member.displayname' => SORT_DESC],
		];
		$attributes['company_type_id'] = [
			'asc' => ['companyType.message' => SORT_ASC],
			'desc' => ['companyType.message' => SORT_DESC],
		];
		$attributes['company_cat_id'] = [
			'asc' => ['companyCategory.message' => SORT_ASC],
			'desc' => ['companyCategory.message' => SORT_DESC],
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
			't.company_id' => isset($params['company']) ? $params['company'] : $this->company_id,
			't.company_type_id' => isset($params['companyType']) ? $params['companyType'] : $this->company_type_id,
			't.company_cat_id' => isset($params['companyCategory']) ? $params['companyCategory'] : $this->company_cat_id,
			't.company_country_id' => $this->company_country_id,
			't.company_province_id' => $this->company_province_id,
			't.company_city_id' => $this->company_city_id,
			't.company_zipcode' => $this->company_zipcode,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
		]);

		$query->andFilterWhere(['like', 't.info_intro', $this->info_intro])
			->andFilterWhere(['like', 't.info_article', $this->info_article])
			->andFilterWhere(['like', 't.company_address', $this->company_address])
			->andFilterWhere(['like', 't.company_district', $this->company_district])
			->andFilterWhere(['like', 't.company_village', $this->company_village])
			->andFilterWhere(['like', 'member.displayname', $this->member_i])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
