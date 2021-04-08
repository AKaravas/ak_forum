<?php
declare(strict_types=1);

use Karavas\AkForum\Ajax\AjaxRequests;
use Karavas\AkForum\Helper\Helper;
use Karavas\AkForum\UserFunc\UserFunc;
use TYPO3\CMS\Core\DependencyInjection;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container, ContainerBuilder $containerBuilder) {
    $containerBuilder->registerForAutoconfiguration(UserFunc::class)->addTag('akforum.userfunc');
    $containerBuilder->registerForAutoconfiguration(Helper::class)->addTag('akforum.helper');
    $containerBuilder->registerForAutoconfiguration(AjaxRequests::class)->addTag('akforum.ajaxRequests');

    $containerBuilder->addCompilerPass(new DependencyInjection\SingletonPass('akforum.userfunc'));
    $containerBuilder->addCompilerPass(new DependencyInjection\SingletonPass('akforum.helper'));
    $containerBuilder->addCompilerPass(new DependencyInjection\SingletonPass('akforum.ajaxRequests'));
};