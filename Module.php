<?php
/**
 * member module definition class
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 16 May 2018, 14:13 WIB
 * @link http://ecc.ft.ugm.ac.id
 *
 */

namespace ommu\member;

class Module extends \app\components\Module
{
	public $layout = 'main';

	/**
	 * @inheritdoc
	 */
	public $controllerNamespace = 'ommu\member\controllers';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		// custom initialization code goes here
	}
}
