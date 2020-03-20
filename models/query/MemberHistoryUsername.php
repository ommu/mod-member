<?php
/**
 * MemberHistoryUsername
 *
 * This is the ActiveQuery class for [[\ommu\member\models\MemberHistoryUsername]].
 * @see \ommu\member\models\MemberHistoryUsername
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 30 October 2018, 23:04 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

namespace ommu\member\models\query;

class MemberHistoryUsername extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\member\models\MemberHistoryUsername[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\member\models\MemberHistoryUsername|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
