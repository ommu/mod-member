<?php
/**
 * member module definition class
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
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

	/**
	 * {@inheritdoc}
	 */
	public function getLayoutPath()
	{
		if(Yii::$app->view->theme)
			return Yii::$app->view->theme->basePath . DIRECTORY_SEPARATOR . 'layouts';
		else
			return parent::getLayoutPath();
	}
}
