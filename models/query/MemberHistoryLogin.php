<?php
/**
 * MemberHistoryLogin
 *
 * This is the ActiveQuery class for [[\ommu\member\models\MemberHistoryLogin]].
 * @see \ommu\member\models\MemberHistoryLogin
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 Upgrad.id
 * @created date 12 October 2020, 09:12 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

namespace ommu\member\models\query;

class MemberHistoryLogin extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\member\models\MemberHistoryLogin[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\member\models\MemberHistoryLogin|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
