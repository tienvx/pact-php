<?php

namespace Plugins;

use Consumer\Service\HttpClientService;
use PhpPact\Consumer\Exception\MockServerNotStartedException;
use PhpPact\Consumer\Exception\PluginNotSupportedBySpecificationException;
use PhpPact\Consumer\InteractionBuilder;
use PhpPact\Consumer\Model\ConsumerRequest;
use PhpPact\Consumer\Model\ProviderResponse;
use PhpPact\Standalone\Installer\Exception\FileDownloadFailureException;
use PhpPact\Standalone\Installer\Exception\NoDownloaderFoundException;
use PhpPact\Standalone\MockService\MockServerEnvConfig;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Exception\GuzzleException;

class CsvTest extends TestCase
{
    /**
     * @throws NoDownloaderFoundException
     * @throws FileDownloadFailureException
     * @throws MockServerNotStartedException
     * @throws PluginNotSupportedBySpecificationException
     * @throws GuzzleException
     */
    public function testGetCsvFile()
    {
        $request = new ConsumerRequest();
        $request
            ->setMethod('GET')
            ->setPath('/report.csv')
            ->addHeader('Content-Type', 'text/csv');

        $response = new ProviderResponse();
        $response
            ->setStatus(200)
            ->setBody(\json_encode([
                'csvHeaders' => false,
                'column:1' => "matching(type,'Name')",
                'column:2' => 'matching(number,100)',
                'column:3' => "matching(datetime, 'yyyy-MM-dd','2000-01-01')"
            ]))
            ->setContentType('text/csv');

        $config      = new MockServerEnvConfig();
        $config->setConsumer('csvConsumer');
        $config->setProvider('csvProvider');
        $config->setPactSpecificationVersion('4.0.0');
        $builder     = new InteractionBuilder($config);
        $builder
            ->usingPlugin('csv')
            ->newInteraction()
            ->given('report.csv file exist')
            ->uponReceiving('request for a report')
            ->with($request)
            ->willRespondWith($response)
            ->createMockServer();

        $service = new HttpClientService($builder->getBaseUri());
        $columns = $service->getCsvFile();

        $this->assertTrue($builder->verify());
        $this->assertCount(3, $columns);
    }
}
