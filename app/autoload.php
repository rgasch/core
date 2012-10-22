<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = require __DIR__.'/../vendor/autoload.php';

// intl
if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';
}

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

require_once __DIR__.'/../src/legacy/i18n/ZGettextFunctions.php';

$loader->add('CustomBundle', __DIR__.'/custom');
$loader->add('Zikula_', __DIR__.'/../src/legacy');
$loader->add('Zikula', __DIR__.'/../src');

require_once __DIR__.'/../src/Zikula/Bundle/CoreBundle/Resources/stubs/ZikulaAutoload.php';
ZikulaAutoload::initialize($loader);

if (file_exists(__DIR__.'bootstrap.php.cache')) {
    require_once __DIR__.'bootstrap.php.cache';
}

return $loader;
