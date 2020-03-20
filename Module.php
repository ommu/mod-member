<?php
/**
 * member module definition class
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 16 May 2018, 14:13 WIB
 * @link https://github.com/ommu/mod-member
 *
 */

namespace ommu\member;

use Yii;

class Module extends \app\components\Module
{
	/**
	 * {@inheritdoc}
	 */
	public $controllerNamespace = 'ommu\member\controllers';

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getViewPath() 
	{
		if(preg_match('/app/', get_class(Yii::$app->controller)))
			return Yii::getAlias('@app/modules/member/views');
		else
			return Yii::getAlias('@ommu/member/views');
	}
}
