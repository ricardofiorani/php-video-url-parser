<?php

namespace RicardoFiorani\Test\Container;

use PHPUnit_Framework_TestCase;
use RicardoFiorani\Container\ServicesContainer;
use stdClass;

class ServicesContainerTest extends PHPUnit_Framework_TestCase
{
    public function testServiceContainerServiceRegistrationByArray()
    {
        $config = $this->getMockConfig();
        $serviceContainer = $this->createServiceContainer($config);
        $this->assertTrue($serviceContainer->hasService('Youtube'));
        $this->assertInstanceOf('\\RicardoFiorani\\Renderer\\DefaultRenderer', $serviceContainer->getRenderer());
    }

    public function testServiceContainerServiceRegistrationByInjection()
    {
        $serviceContainer = $this->createServiceContainer();
        $serviceContainer->registerService('TestService', array('#testPattern#'), function () {
            // @todo test the injected service maybe ?
        });

        $this->assertContains('TestService', $serviceContainer->getServiceNameList());

        $this->setExpectedException('\\RicardoFiorani\\Container\\Exception\\DuplicatedServiceNameException');

        $serviceContainer->registerService('TestService', array('#testPattern#'), function () {
            // @todo test the injected service maybe ?
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
        $this->assertInstanceOf('\\RicardoFiorani\\Adapter\\Youtube\\Factory\\YoutubeServiceAdapterFactory', $factory);

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
                    'factory' => '\\RicardoFiorani\\Adapter\\Youtube\\Factory\\YoutubeServiceAdapterFactory',
                ),
            ),
            'renderer' => array(
                'name' => 'DefaultRenderer',
                'factory' => '\\RicardoFiorani\\Renderer\\Factory\\DefaultRendererFactory',
            )
        );
    }
}
