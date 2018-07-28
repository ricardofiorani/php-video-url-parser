<?php

namespace RicardoFiorani\Test\Container;


use PHPUnit\Framework\TestCase;
use RicardoFiorani\Adapter\Youtube\Factory\YoutubeServiceAdapterFactory;
use RicardoFiorani\Container\Exception\DuplicatedServiceNameException;
use RicardoFiorani\Container\ServicesContainer;
use RicardoFiorani\Renderer\DefaultRenderer;
use RicardoFiorani\Renderer\Factory\DefaultRendererFactory;
use stdClass;

class ServicesContainerTest extends TestCase
{
    public function testServiceContainerServiceRegistrationByArray()
    {
        $config = $this->getMockConfig();
        $serviceContainer = $this->createServiceContainer($config);
        $this->assertTrue($serviceContainer->hasService('Youtube'));
        $this->assertInstanceOf(DefaultRenderer::class, $serviceContainer->getRenderer());
    }

    public function testServiceContainerServiceRegistrationByInjection()
    {
        $serviceContainer = $this->createServiceContainer();
        $serviceContainer->registerService('TestService', ['#testPattern#'], function () {
            // @todo test the injected service maybe ?
        });

        $this->assertContains('TestService', $serviceContainer->getServiceNameList());
        $this->expectException(DuplicatedServiceNameException::class);
        $serviceContainer->registerService('TestService', ['#testPattern#'], function () {
        });
    }

    public function testServicesList()
    {
        $config = $this->getMockConfig();
        $serviceContainer = $this->createServiceContainer($config);
        $this->assertInternalType('array', $serviceContainer->getServices());
        $this->assertContains('Youtube', $serviceContainer->getServices());
    }

    public function testIfReturnsAlreadyInstantiatedFactory()
    {
        $config = $this->getMockConfig();
        $serviceContainer = $this->createServiceContainer($config);
        $factory = $serviceContainer->getFactory('Youtube');
        $this->assertInstanceOf(YoutubeServiceAdapterFactory::class, $factory);

        $alreadyInstantiatedFactory = $serviceContainer->getFactory('Youtube');
        $this->assertEquals($factory, $alreadyInstantiatedFactory);
    }

    /**
     * @return ServicesContainer
     */
    private function createServiceContainer(array $constructArray = array())
    {
        $serviceContainer = new ServicesContainer($constructArray);

        return $serviceContainer;
    }

    /**
     * @return array
     */
    private function getMockConfig()
    {
        return array(
            'services' => array(
                'Youtube' => array(
                    'patterns' => array(
                        '#(?:<\>]+href=\")?(?:http://)?((?:[a-zA-Z]{1,4}\.)?youtube.com/(?:watch)?\?v=(.{11}?))[^"]*(?:\"[^\<\>]*>)?([^\<\>]*)(?:)?#',
                        '%(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
                    ),
                    'factory' => YoutubeServiceAdapterFactory::class,
                ),
            ),
            'renderer' => array(
                'name' => 'DefaultRenderer',
                'factory' => DefaultRendererFactory::class,
            )
        );
    }
}
