<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Template.Aurelia
 * @subpackage      Pages.Search
 *
 * @copyright       Copyright (C) 2008 - 2020 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
**/

namespace Kunena\Forum\Site;

defined('_JEXEC') or die();

use Joomla\CMS\Language\Text;
use function defined;

$content = $this->execute('Search/Form');

$this->addBreadcrumb(
	Text::_('COM_KUNENA_MENU_SEARCH'),
	'index.php?option=com_kunena&view=search'
);

echo $content;
echo $this->subRequest('Search/Results');
