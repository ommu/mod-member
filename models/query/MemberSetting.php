<?php
/**
 * MemberSetting
 *
 * This is the ActiveQuery class for [[\ommu\member\models\MemberSetting]].
 * @see \ommu\member\models\MemberSetting
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 5 November 2018, 06:12 WIB
 * @modified date 5 November 2018, 06:12 WIB
 * @modified by Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @link https://github.com/ommu/mod-member
 *
 */

namespace ommu\member\models\query;

class MemberSetting extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\member\models\MemberSetting[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\member\models\MemberSetting|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
