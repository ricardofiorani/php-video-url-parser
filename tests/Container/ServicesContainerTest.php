<?php declare(strict_types=1);

namespace RicardoFioraniTests\Container;

use PHPUnit\Framework\TestCase;
use RicardoFiorani\Adapter\Youtube\YoutubeServiceAdapter;
use RicardoFiorani\Container\ServicesContainer;
use RicardoFiorani\Renderer\DefaultRenderer;
use RicardoFiorani\Exception\DuplicatedServiceNameException;
use RicardoFiorani\Adapter\Youtube\Factory\YoutubeServiceAdapterFactory;
use RicardoFiorani\Renderer\Factory\DefaultRendererFactory;

class ServicesContainerTest extends TestCase
{
    private ServicesContainer $container;

    protected function setUp(): void
    {
        $this->container = $this->createServiceContainer($this->getMockConfig());
        parent::setUp();
    }


    public function testServiceContainerServiceRegistrationByArray(): void
    {
        static::assertTrue($this->container->hasService('Youtube'));
        static::assertInstanceOf(DefaultRenderer::class, $this->container->getRenderer());
    }

    public function testServiceContainerServiceRegistrationByInjection(): void
    {
        $this->container->registerService('TestService', ['#testPattern#'], function () {
            // @todo test the injected service maybe ?
        });

        static::assertContains('TestService', $this->container->getServiceNameList());
        $this->expectException(DuplicatedServiceNameException::class);
        $this->container->registerService('TestService', ['#testPattern#'], function () {
        });
    }

    public function testServicesList(): void
    {
        static::assertIsArray($this->container->getServices());
        static::assertContains(YoutubeServiceAdapter::SERVICE_NAME, $this->container->getServices());
    }

    public function testIfReturnsAlreadyInstantiatedFactory(): void
    {
        $factory = $this->container->getFactory(YoutubeServiceAdapter::SERVICE_NAME);
        static::assertInstanceOf(YoutubeServiceAdapterFactory::class, $factory);

        $alreadyInstantiatedFactory = $this->container->getFactory(YoutubeServiceAdapter::SERVICE_NAME);
        static::assertEquals($factory, $alreadyInstantiatedFactory);
    }

    /**
     * @return ServicesContainer
     */
    private function createServiceContainer(array $config = []): ServicesContainer
    {
        return new ServicesContainer($config);
    }

    /**
     * @return array
     */
    private function getMockConfig(): array
    {
        return [
            'services' => [
                YoutubeServiceAdapter::SERVICE_NAME => [
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
