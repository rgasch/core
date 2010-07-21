<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2004, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_System_Modules
 * @subpackage Theme
 */

// Based on
// Originally a script for MamboOS http://www.mamboserver.com
// ThemeChanger    - Version: 1.1
// Author : Arthur Konze - webmaster@mamboportal.com
// ThemeChanger for Zikula 0.760 - Version 0.8
// Adapted to Zikula by N!cklas - nicklas@johansson.tk
// Last changes by Lindbergh , http://lindbergh.ohost.de

class Theme_Block_Themeswitcher extends Zikula_Block
{
    /**
     * initialise block
     *
     */
    public function init()
    {
        SecurityUtil::registerPermissionSchema('Themeswitcherblock::', 'Block title::');
    }

    /**
     * get information on block
     *
     * @return       array       The block information
     */
    public function info()
    {
        $switchThemeEnable = System::getVar('theme_change');

        if (!$switchThemeEnable) {
            $requirement_message = $this->__f('Notice: This theme switcher block will not be displayed until you allow users to change themes. You can enable/disable this from the <a href="%s">settings</a> of the Theme module.', DataUtil::formatForDisplayHTML(ModUtil::url('Theme', 'admin', 'modifyconfig')));
        } else {
            $requirement_message = '';
        }

        return array('module'       => 'Theme',
                'text_type'         => $this->__('Theme switcher'),
                'text_type_long'    => $this->__('Theme switcher'),
                'allow_multiple'    => true,
                'form_content'      => false,
                'form_refresh'      => false,
                'show_preview'      => true,
                'admin_tableless'   => true,
                'requirement'       => $requirement_message);
    }

    public function display($blockinfo)
    {
        // check if the module is available
        if (!ModUtil::available('Theme')) {
            return;
        }

        // check if theme switching is allowed
        if (!System::getVar('theme_change')) {
            return;
        }

        // security check
        if (!SecurityUtil::checkPermission( "Themeswitcherblock::", "$blockinfo[title]::", ACCESS_READ)) {
            return;
        }

        // Get variables from content block
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        // Defaults
        if (empty($vars['format'])) {
            $vars['format'] = 1;
        }

        // get some use information about our environment
        $currenttheme = UserUtil::getTheme();

        // get all themes in our environment
        $themes = ThemeUtil::getAllThemes();

        // get some use information about our environment
        $currenttheme = ThemeUtil::getInfo(ThemeUtil::getIDFromName(UserUtil::getTheme()));

        // get all themes in our environment
        $themes = ThemeUtil::getAllThemes(ThemeUtil::FILTER_USER);

        $previewthemes = array();
        $currentthemepic = null;
        foreach ($themes as $themeinfo) {
            $themename = $themeinfo['name'];
            if (file_exists($themepic = 'themes/'.DataUtil::formatForOS($themeinfo['directory']).'/images/preview_small.png')) {
                $themeinfo['previewImage'] = $themepic;
            }
            else {
                $themeinfo['previewImage'] = 'system/Theme/images/preview_small.png';
            }
            $previewthemes[$themename] = $themeinfo;
            if ($themename == $currenttheme['name']) {
                $currentthemepic = $themeinfo['previewImage'];
            }
        }

        $this->view->assign($vars)
                       ->assign('currentthemepic', $currentthemepic)
                       ->assign('currenttheme', $currenttheme)
                       ->assign('themes', $previewthemes);

        $blockinfo['content'] = $this->view->fetch('theme_block_themeswitcher.tpl');

        return BlockUtil::themeBlock($blockinfo);
    }

    public function modify($blockinfo)
    {
        // Get current content
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        // Defaults
        // format: 1 = drop down with preview, 2 = simple list
        if (empty($vars['format'])) {
            $vars['format'] = 1;
        }

        $this->view->setCaching(false);

        // assign the approriate values
        $this->view->assign($vars);

        // Return the output that has been generated by this function
        return $this->view->fetch('theme_block_themeswitcher_modify.tpl');
    }

    function update($blockinfo)
    {
        // Get current content
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        // alter the corresponding variable
        $vars['format'] = FormUtil::getPassedValue('format', 1, 'POST');

        // write back the new contents
        $blockinfo['content'] = BlockUtil::varsToContent($vars);

        // clear the block cache
        $this->view->clear_cache('theme_block_themeswitcher.tpl');

        return $blockinfo;
    }
}