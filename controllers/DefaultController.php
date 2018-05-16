<?php
/**
 * DefaultController
 * @var $this yii\web\View
 *
 * Default controller for the `member` module
 * Reference start
 * TOC :
 *	Index
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @created date 16 May 2018, 14:13 WIB
 * @link http://ecc.ft.ugm.ac.id
 *
 */

namespace app\modules\member\controllers;

use app\components\Controller;

class DefaultController extends Controller
{
	/**
	 * Renders the index view for the module
	 * @return string
	 */
	public function actionIndex()
	{
		$this->view->title = 'members';
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('index');
	}
}