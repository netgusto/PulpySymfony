<?php

namespace Pulpy\CoreBundle\Provider\Platform;

use Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Reference,
    Symfony\Component\DependencyInjection\Definition,
    Symfony\Component\ExpressionLanguage\Expression;


class ClassicPlatformProvider {
    public function __construct() {
    }

    public function injectServicesInContainer(ContainerBuilder $container) {
        
        /*
        config.loader.dbbacked:
                class: Pulpy\CoreBundle\Services\Config\Loader\DbBackedConfigLoaderService
                arguments:
                    - @doctrine.orm.entity_manager
                    - { data.dir: '' }

            config.site:
                class: Pulpy\CoreBundle\Services\Config\SiteConfigService
                arguments:
                    - @=service('config.loader.dbbacked').load('config.site')

            fs.persistent:
                class: Pulpy\CoreBundle\Services\PersistentStorage\S3PersistentStorageService
                arguments:
                    - @=service('environment').getEnv('S3_BUCKET')
                    - @=service('environment').getEnv('S3_KEYID')
                    - @=service('environment').getEnv('S3_SECRET')
                    - @=service('environment').getScheme() ~ '://' ~ service('environment').getEnv('S3_BUCKET') ~ '.s3.amazonaws.com'

            post.cachehandler:
                class: Pulpy\CoreBundle\Services\CacheHandler\EventedPostCacheHandlerService
                arguments:
                    - @fs.persistent
                    - @system.status
                    - @postfile.repository
                    - @post.repository
                    - @postfile.topostconverter
                    - @doctrine.orm.entity_manager
                    - @=service('config.site').getPostsDir()
                    - @culture
        */


        /*
        config.loader.filebacked:
            class: Pulpy\CoreBundle\Services\Config\Loader\FileBackedConfigLoaderService
            arguments:
                - { data.dir: 'data' }

        config.site:
            class: Pulpy\CoreBundle\Services\Config\SiteConfigService
            arguments:
                - @=service('config.loader.filebacked').load(parameter('rootdir') ~ '/app/config/app.yml')

        fs.persistent:
            class: Pulpy\CoreBundle\Services\PersistentStorage\LocalFSPersistentStorageService
            arguments:
                - @=service('environment').getRootDir()
                - @=service('environment').getSiteUrl()

        post.cachehandler:
            class: Pulpy\CoreBundle\Services\CacheHandler\LastModifiedPostCacheHandlerService
            arguments:
                - @fs.persistent
                - @system.status
                - @postfile.repository
                - @post.repository
                - @postfile.topostconverter
                - @doctrine.orm.entity_manager
                - @=service('config.site').getPostsDir()
                - @culture
        */

        $configloader = new Definition('Pulpy\CoreBundle\Services\Config\Loader\FileBackedConfigLoaderService');
        #$configloader->addArgument(new Reference('doctrine.orm.entity_manager'));
        $configloader->addArgument(array('data.dir' => 'data'));

        $container->setDefinition(
            'config.loader',
            $configloader
        );

        #######################################################################


        $configsite = new Definition('Pulpy\CoreBundle\Services\Config\SiteConfigService');
        #$configsite->addArgument(new Expression("@=service('config.loader').load('config.site')"));
        $configsite->addArgument(new Expression("service('config.loader').load(parameter('rootdir') ~ '/app/config/app.yml')"));

        $container->setDefinition(
            'config.site',
            $configsite
        );

        #######################################################################

        $fspersistent = new Definition('Pulpy\CoreBundle\Services\PersistentStorage\LocalFSPersistentStorageService');
        $fspersistent->addArgument(new Expression("service('environment').getRootDir()"));
        $fspersistent->addArgument(new Expression("service('environment').getSiteUrl()"));

        $container->setDefinition(
            'fs.persistent',
            $fspersistent
        );

        #######################################################################

        $postcachehandler = new Definition('Pulpy\CoreBundle\Services\CacheHandler\LastModifiedPostCacheHandlerService');
        $postcachehandler->addArgument(new Reference('fs.persistent'));
        $postcachehandler->addArgument(new Reference('system.status'));
        $postcachehandler->addArgument(new Reference('postfile.repository'));
        $postcachehandler->addArgument(new Reference('post.repository'));
        $postcachehandler->addArgument(new Reference('postfile.topostconverter'));
        $postcachehandler->addArgument(new Reference('doctrine.orm.entity_manager'));
        $postcachehandler->addArgument(new Expression("service('config.site').getPostsDir()"));
        $postcachehandler->addArgument(new Reference("culture"));

        $container->setDefinition(
            'post.cachehandler',
            $postcachehandler
        );
    }
}