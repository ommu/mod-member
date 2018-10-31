<?php
/**
 * MemberCategories
 *
 * This is the ActiveQuery class for [[\ommu\member\models\MemberCategories]].
 * @see \ommu\member\models\MemberCategories
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 1 November 2018, 01:04 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

namespace ommu\member\models\query;

class MemberCategories extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * @inheritdoc
	 */
	public function published() 
	{
		return $this->andWhere(['publish' => 1]);
	}

	/**
	 * @inheritdoc
	 */
	public function unpublish() 
	{
		return $this->andWhere(['publish' => 0]);
	}

	/**
	 * @inheritdoc
	 * @return \ommu\member\models\MemberCategories[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return \ommu\member\models\MemberCategories|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
