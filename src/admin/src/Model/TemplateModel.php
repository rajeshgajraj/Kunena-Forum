<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Administrator
 * @subpackage      Models
 *
 * @copyright       Copyright (C) 2008 - 2021 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/

namespace Kunena\Forum\Administrator\Model;

defined('_JEXEC') or die();

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;
use Kunena\Forum\Libraries\Template\KunenaTemplate;
use Kunena\Forum\Libraries\Template\KunenaTemplateHelper;
use stdClass;

/**
 * template Model for Kunena
 *
 * @since  6.0
 */
class TemplateModel extends AdminModel
{

	/**
	 * @param   array  $data      data
	 * @param   bool   $loadData  loadData
	 *
	 * @return  boolean|mixed
	 *
	 * @throws  Exception
	 * @since   Kunena 6.0
	 *
	 * @see     \Joomla\CMS\MVC\Model\FormModel::getForm()
	 *
	 */
	public function getForm($data = [], $loadData = true): bool
	{
		// Load the configuration definition file.
		$template = $this->getState('template');
		$xml      = KunenaTemplate::getInstance($template)->getConfigXml();

		// Get the form.
		$form = $this->loadForm('com_kunena_template', $xml, ['control' => 'jform', 'load_data' => $loadData, 'file' => false], true, '//config');

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * @return  boolean|stdClass
	 *
	 * @throws  Exception
	 * @since   Kunena 6.0
	 *
	 */
	public function getTemplateDetails()
	{
		$app = Factory::getApplication();

		$template = $app->getUserState('kunena.edit.template');
		$details  = KunenaTemplateHelper::parseXmlFile($template);

		if (empty($template))
		{
			$template = $this->getState('template');
			$details  = KunenaTemplateHelper::parseXmlFile($template);
		}

		return $details;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   null  $ordering   ordering
	 * @param   null  $direction  direction
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 * @since   Kunena 6.0
	 *
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// List state information.
		$context = 'com_kunena.admin.templates';

		$app = Factory::getApplication();

		// Adjust the context to support modal layouts.
		$layout = $app->input->get('layout');

		if ($layout)
		{
			$context .= '.' . $layout;
		}

		// Edit state information
		$value = $this->getUserStateFromRequest($context . '.edit', 'name', '', 'cmd');
		$this->setState('template', $value);

		if (empty($app->getUserState('kunena.edit.templatename')))
		{
			$app->setUserState('kunena.edit.templatename', $value);
		}
	}

	/**
	 * @param   string  $key        key
	 * @param   string  $request    request
	 * @param   null    $default    default
	 * @param   string  $type       type
	 * @param   bool    $resetPage  resetPage
	 *
	 * @return  mixed|null
	 *
	 * @throws Exception
	 * @since   Kunena 6.0
	 *
	 */
	public function getUserStateFromRequest(string $key, string $request, $default = null, $type = 'none', $resetPage = true)
	{
		$app      = Factory::getApplication();
		$input    = $app->input;
		$oldState = $app->getUserState($key);
		$curState = ($oldState !== null) ? $oldState : $default;
		$newState = $input->get($request, null, $type);

		if (($curState != $newState) && ($resetPage))
		{
			$input->set('limitstart', 0);
		}

		// Save the new value only if it is set in this request.
		if ($newState !== null)
		{
			$app->setUserState($key, $newState);
		}
		else
		{
			$newState = $curState;
		}

		return $newState;
	}
}
