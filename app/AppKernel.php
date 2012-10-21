<?php

use Zikula\Bundle\CoreBundle\HttpKernel\ZikulaKernel as Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Zikula\Bundle\CoreBundle\CoreBundle(),
            new Zikula\Bundle\ModuleBundle\ZikulaModuleBundle(),
//            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            //new JMS\AopBundle\JMSAopBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            //new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
//            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
//            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
//            new JMS\I18nRoutingBundle\JMSI18nRoutingBundle(),
//            new JMS\TranslationBundle\JMSTranslationBundle(),

            new Zikula\Module\AdminModule\ZikulaAdminModule(),
            new Zikula\Module\BlocksModule\ZikulaBlocksModule(),
            new Zikula\Module\CategoriesModule\ZikulaCategoriesModule(),
            new Zikula\Module\ErrorsModule\ZikulaErrorsModule(),
            new Zikula\Module\ExtensionsModule\ZikulaExtensionsModule(),
            new Zikula\Module\GroupsModule\ZikulaGroupsModule(),
            new Zikula\Module\MailerModule\ZikulaMailerModule(),
            new Zikula\Module\PageLockModule\ZikulaPageLockModule(),
            new Zikula\Module\PermissionsModule\ZikulaPermissionsModule(),
            new Zikula\Module\SearchModule\ZikulaSearchModule(),
            new Zikula\Module\SecurityCenterModule\ZikulaSecurityCenterModule(),
            new Zikula\Module\SettingsModule\ZikulaSettingsModule(),
            new Zikula\Module\ThemeModule\ZikulaThemeModule(),
            new Zikula\Module\UsersModule\ZikulaUsersModule(),
            new Zikula\Theme\Andreas08Theme\Andreas08Theme(),
            new CustomBundle\CustomBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
        $loader->load(__DIR__.'/config/database.yml');
    }
}
