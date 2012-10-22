<?php
/**
 * Copyright Zikula Foundation 2009 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Zikula
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

use Symfony\Component\ClassLoader\UniversalClassLoader;

define('ZLOADER_PATH', __DIR__.'/..');
define('ZIKULA_CONFIG_PATH', realpath(__DIR__.'/../../web/config'));
define('ZIKULA_ROOT', realpath(__DIR__.'/../../web'));

/**
 * ZLoader.
 */
class ZLoader
{
    /**
     * Autoloaders.
     *
     * @var UniversalClassLoader
     */
    private static $autoloaders;

    /**
     * Autoloaders.
     *
     * @var \Zikula\Framework\ModuleClassLoader
     */
    private static $moduleLoader;

    /**
     * Base setup.
     *
     * @return void
     */
    public static function register()
    {
        self::$autoloaders = new UniversalClassLoader();
        self::$autoloaders->register();

        $mapClassLoader = new \Symfony\Component\ClassLoader\MapClassLoader(self::map());
        $mapClassLoader->register();

        self::$moduleLoader = new \Zikula\Framework\ModuleClassLoader();
        self::$moduleLoader->spl_autoload_register();
    }

    /**
     * Add new autoloader to the stack.
     *
     * @param string $namespace Namespace.
     * @param string $path      Path.
     * @param string $separator Separator, _ or \\.
     *
     * @return void
     */
    public static function addAutoloader($namespace, $path = '', $separator = '_')
    {
        if (in_array($namespace, self::$autoloaders->getNamespaces())) {
            return;
        }

        if ($separator == '_') {
            return self::$autoloaders->registerPrefix($namespace.'_', $path);
        }

        self::$autoloaders->register($namespace, $path, $separator);
    }

    public static function addModule($namespace, $path)
    {
        if (self::$moduleLoader->hasAutoloader($namespace)) {
            return;
        }

        self::$moduleLoader->register($namespace, $path);
    }

    /**
     * Provides map for simple autoloader.
     *
     * @return array Class locations.
     */
    public static function map()
    {
        return array(
            'StreamReader_Abstract' => ZLOADER_PATH . '/legacy/StreamReader/Abstract.php',
            'StreamReader_CachedFile' => ZLOADER_PATH . '/legacy/StreamReader/CachedFile.php',
            'StreamReader_String' => ZLOADER_PATH . '/legacy/StreamReader/String.php',
            'ZLanguage' => ZLOADER_PATH . '/legacy/i18n/ZLanguage.php',
            'ZI18n' => ZLOADER_PATH . '/legacy/i18n/ZI18n.php',
            'ZL10n' => ZLOADER_PATH . '/legacy/i18n/ZL10n.php',
            'ZLocale' => ZLOADER_PATH . '/legacy/i18n/ZLocale.php',
            'ZGettext' => ZLOADER_PATH . '/legacy/i18n/ZGettext.php',
            'ZMO' => ZLOADER_PATH . '/legacy/i18n/ZMO.php',
            'ZLanguageBrowser' => ZLOADER_PATH . '/legacy/i18n/ZLanguageBrowser.php',
            'BlockUtil' => ZLOADER_PATH . '/legacy/util/BlockUtil.php',
            'CacheUtil' => ZLOADER_PATH . '/legacy/util/CacheUtil.php',
            'CategoryRegistryUtil' => ZLOADER_PATH . '/legacy/util/CategoryRegistryUtil.php',
            'CategoryUtil' => ZLOADER_PATH . '/legacy/util/CategoryUtil.php',
            'CookieUtil' => ZLOADER_PATH . '/legacy/util/CookieUtil.php',
            'DataUtil' => ZLOADER_PATH . '/legacy/util/DataUtil.php',
            'DateUtil' => ZLOADER_PATH . '/legacy/util/DateUtil.php',
            'DoctrineHelper' => ZLOADER_PATH . '/legacy/util/DoctrineHelper.php',
            'EventUtil' => ZLOADER_PATH . '/legacy/util/EventUtil.php',
            'FileUtil' => ZLOADER_PATH . '/legacy/util/FileUtil.php',
            'FilterUtil' => ZLOADER_PATH . '/legacy/util/FilterUtil.php',
            'FormUtil' => ZLOADER_PATH . '/legacy/util/FormUtil.php',
            'HookUtil' => ZLOADER_PATH . '/legacy/util/HookUtil.php',
            'HtmlUtil' => ZLOADER_PATH . '/legacy/util/HtmlUtil.php',
            'JCSSUtil' => ZLOADER_PATH . '/legacy/util/JCSSUtil.php',
            'LogUtil' => ZLOADER_PATH . '/legacy/util/LogUtil.php',
            'ModUtil' => ZLOADER_PATH . '/legacy/util/ModUtil.php',
            'ObjectUtil' => ZLOADER_PATH . '/legacy/util/ObjectUtil.php',
            'PluginUtil' => ZLOADER_PATH . '/legacy/util/PluginUtil.php',
            'RandomUtil' => ZLOADER_PATH . '/legacy/util/RandomUtil.php',
            'SecurityUtil' => ZLOADER_PATH . '/legacy/util/SecurityUtil.php',
            'ServiceUtil' => ZLOADER_PATH . '/legacy/util/ServiceUtil.php',
            'SessionUtil' => ZLOADER_PATH . '/legacy/util/SessionUtil.php',
            'StringUtil' => ZLOADER_PATH . '/legacy/util/StringUtil.php',
            'System' => ZLOADER_PATH . '/legacy/util/System.php',
            'ThemeUtil' => ZLOADER_PATH . '/legacy/util/ThemeUtil.php',
            'UserUtil' => ZLOADER_PATH . '/legacy/util/UserUtil.php',
        );
    }

}
/**
 * Exit.
 *
 * @param string  $msg  Message.
 * @param boolean $html True for html.
 *
 * @deprecated since 1.3.0
 *
 * @return false
 */
function z_exit($msg, $html = true)
{
    if ($html) {
        $msg = DataUtil::formatForDisplayHTML($msg);
    }
    LogUtil::registerError($msg);
    trigger_error($msg, E_USER_ERROR);
    return false;
    //throw new Zikula_Exception_Fatal($msg);
}
