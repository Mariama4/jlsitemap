<?php
/**
 * @package    JLSitemap Component
 * @version    @version@
 * @author     Joomline - joomline.ru
 * @copyright  Copyright (c) 2010 - 2022 Joomline. All rights reserved.
 * @license    GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link       https://joomline.ru/
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

HTMLHelper::stylesheet('com_jlsitemap/admin.min.css', array('version' => 'auto', 'relative' => true));
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $file = JFactory::getApplication()->input->files->get('my_file');
    $filename = JFile::makeSafe($file['name']);

    // Путь, куда будет сохранен файл
    $destination = JPATH_SITE . '/jlsitemap/' . $filename;


    // Сохраняем файл на сервер
    JFile::upload($file['tmp_name'], $destination);

    // Выводим сообщение об успешной загрузке
    echo '<p>Файл ' . $filename . ' успешно загружен на сервер.</p>';
}
?>
<div id="controlPanel">
	<div class="main">
		<?php
		echo '<p>Название файла строго SITEMAP_URLS.txt</p>';
		$path = JPATH_SITE . '/jlsitemap/';
		$files = JFolder::files($path);
		echo '<p>Файлов ' . count($files) . '</p>';
		?>
		<form method="post" enctype="multipart/form-data">
			<input type="file" name="my_file">
			<button type="submit">Загрузить файл</button>
		</form>
		<?php echo LayoutHelper::render('components.jlsitemap.admin.messages', $this->messages); ?>
		<div class="actions">
			<?php
			$layout = 'components.jlsitemap.admin.action';

			// Generation
			echo LayoutHelper::render($layout, array(
				'class'     => 'generation',
				'url'       => $this->generate,
				'title'     => 'COM_JLSITEMAP_GENERATION',
				'icon'      => 'generation',
				'newWindow' => false
			));

			// Sitemap
			if ($this->sitemap)
			{
				if (!$this->sitemap->unidentified)
				{
					echo LayoutHelper::render($layout, array(
						'class'     => 'sitemap',
						'url'       => $this->sitemap->url,
						'title'     => 'COM_JLSITEMAP_SITEMAP',
						'icon'      => 'sitemap',
						'newWindow' => true,
						'badge'     => HTMLHelper::_('date', $this->sitemap->date, Text::_('DATE_FORMAT_LC6'))
					));
				}
				else
				{
					echo LayoutHelper::render($layout, array(
						'class'     => 'unidentified-sitemap error',
						'url'       => $this->sitemap->url,
						'title'     => 'COM_JLSITEMAP_ERROR_SITEMAP_UNIDENTIFIED',
						'icon'      => 'sitemap',
						'newWindow' => true,
						'badge'     => HTMLHelper::_('date', $this->sitemap->date, Text::_('DATE_FORMAT_LC6'))
					));
				}

				echo LayoutHelper::render($layout, array(
					'class' => 'delete',
					'url'   => $this->delete,
					'title' => 'COM_JLSITEMAP_SITEMAP_DELETE',
					'icon'  => 'delete'
				));
			}
			else
			{
				echo LayoutHelper::render($layout, array(
					'class' => 'no-sitemap not-active error',
					'title' => 'COM_JLSITEMAP_ERROR_SITEMAP_NOT_FOUND',
					'icon'  => 'sitemap',
				));
			}

			// Plugins
			echo LayoutHelper::render($layout, array(
				'class'     => 'plugins',
				'url'       => $this->plugins,
				'title'     => 'COM_JLSITEMAP_PLUGINS',
				'icon'      => 'plugins',
				'newWindow' => true
			));

			// Cron
			if ($this->cron)
			{
				echo LayoutHelper::render($layout, array(
					'class'     => 'cron',
					'url'       => $this->cron->url,
					'title'     => 'COM_JLSITEMAP_CRON',
					'icon'      => 'cron',
					'newWindow' => true,
					'badge'     => ($this->cron->last_run) ?
						HTMLHelper::_('date', $this->cron->last_run, Text::_('DATE_FORMAT_LC6')) : false
				));
			}

			// Debug
			echo LayoutHelper::render($layout, array(
				'class'     => 'debug',
				'url'       => $this->debug,
				'title'     => 'COM_JLSITEMAP_DEBUG',
				'icon'      => 'debug',
				'newWindow' => true
			));

			// Config
			if ($this->config)
			{
				echo LayoutHelper::render($layout, array(
					'class' => 'config',
					'url'   => $this->config,
					'title' => 'COM_JLSITEMAP_CONFIG',
					'icon'  => 'config'
				));
			}
			?>
		</div>
	</div>
	<div class="sidebar">
		<div class="wrapper">
			<?php echo Text::_('COM_JLSITEMAP_ADMIN_TEXT'); ?>
		</div>
	</div>
</div>