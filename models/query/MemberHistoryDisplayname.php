<?php
/**
 * MemberHistoryDisplayname
 *
 * This is the ActiveQuery class for [[\ommu\member\models\MemberHistoryDisplayname]].
 * @see \ommu\member\models\MemberHistoryDisplayname
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 30 October 2018, 22:56 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

namespace ommu\member\models\query;

class MemberHistoryDisplayname extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\member\models\MemberHistoryDisplayname[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\member\models\MemberHistoryDisplayname|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
