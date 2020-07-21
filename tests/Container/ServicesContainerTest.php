<?php declare(strict_types=1);

namespace RicardoFiorani\Tests\VideoUrlParser\Container;

use PHPUnit\Framework\TestCase;
use RicardoFiorani\VideoUrlParser\Adapter\Youtube\Factory\YoutubeServiceAdapterFactory;
use RicardoFiorani\VideoUrlParser\Container\ServicesContainer;
use RicardoFiorani\VideoUrlParser\Exception\DuplicatedServiceNameException;
use RicardoFiorani\VideoUrlParser\Renderer\DefaultRenderer;
use RicardoFiorani\VideoUrlParser\Renderer\Factory\DefaultRendererFactory;

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
        $serviceContainer->registerService('TestService', array('#testPattern#'), function () {
            // @todo tests the injected service maybe ?
        });

        $this->assertContains('TestService', $serviceContainer->getServiceNameList());
        $this->expectException(DuplicatedServiceNameException::class);
        $serviceContainer->registerService('TestService', array('#testPattern#'), function () {
        });
    }

    public function testServicesList()
    {
        $config = $this->getMockConfig();
        $serviceContainer = $this->createServiceContainer($config);
        $this->assertIsArray($serviceContainer->getServices());
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
    private function createServiceContainer(array $constructArray = [])
    {
        return new ServicesContainer($constructArray);
    }

    /**
     * @return array
     */
    private function getMockConfig()
    {
        return [
            'services' => [
                'Youtube' => [
                    'patterns' => [
                        '#(?:<\>]+href=\")?(?:http://)?((?:[a-zA-Z]{1,4}\.)?youtube.com/(?:watch)?\?v=(.{11}?))[^"]*(?:\"[^\<\>]*>)?([^\<\>]*)(?:)?#',
                        '%(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
                    ],
                    'factory' => YoutubeServiceAdapterFactory::class,
                ],
            ],
            'renderer' => [
                'name' => 'DefaultRenderer',
                'factory' => DefaultRendererFactory::class,
            ]
        ];
    }
}
