<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../FKBDevice/libs/FKBApiClient.php';

/**
 * Client mit vorgegebener Antwort statt echter HTTP-Anfrage.
 */
class FKBApiClientFake extends FKBApiClient
{
    public string $lastUrl = '';
    public array $response = ['{"status":"OK","statustext":"OK"}', 200, ''];

    protected function send(string $Url): array
    {
        $this->lastUrl = $Url;
        return $this->response;
    }
}

class FKBApiClientTest extends TestCase
{
    private array $debug = [];

    public function testRequestBuildsUrlWithCommandPasswordAndJsonType(): void
    {
        $client = $this->client();

        $client->request('getDeviceInfo');

        $this->assertSame('http://192.0.2.10:2323/?cmd=getDeviceInfo&password=geheim&type=json', $client->lastUrl);
    }

    public function testHttpsIsUsedWhenEnabled(): void
    {
        $client = $this->client('geheim', true);

        $client->request('getDeviceInfo');

        $this->assertStringStartsWith('https://192.0.2.10:2323/', $client->lastUrl);
    }

    public function testPasswordNeverAppearsInDebugOutput(): void
    {
        $client = $this->client('geheim');

        $client->request('textToSpeech', ['text' => 'Hallo']);

        $this->assertNotEmpty($this->debug);
        foreach ($this->debug as [$message, $data]) {
            $this->assertStringNotContainsString('geheim', $message . $data);
            $this->assertStringNotContainsString('password', $message . $data);
        }
    }

    public function testParametersAreUrlEncoded(): void
    {
        $client = $this->client();

        $client->request('textToSpeech', ['text' => 'Hallo Welt & mehr']);

        $this->assertStringContainsString('text=Hallo%20Welt%20%26%20mehr', $client->lastUrl);
    }

    public function testPasswordWithSpecialCharactersIsEncoded(): void
    {
        $client = $this->client('p&ss=wort #1');

        $client->request('getDeviceInfo');

        $this->assertStringContainsString('password=p%26ss%3Dwort%20%231', $client->lastUrl);
    }

    public function testBooleanParametersAreSentAsTrueAndFalse(): void
    {
        $client = $this->client();

        $client->request('playSound', ['url' => 'http://x/a.mp3', 'loop' => false, 'stream' => 3]);

        $this->assertStringContainsString('loop=false', $client->lastUrl);
        $this->assertStringContainsString('stream=3', $client->lastUrl);
    }

    public function testRawRequestHasNoJsonTypeAndReturnsBody(): void
    {
        $client = $this->client();
        $client->response = ['PNGDATA', 200, ''];

        $result = $client->request('getScreenshot', [], false);

        $this->assertSame('PNGDATA', $result);
        $this->assertStringNotContainsString('type=json', $client->lastUrl);
    }

    public function testCommandReturnsTrueOnStatusOk(): void
    {
        $client = $this->client();

        $this->assertTrue($client->command('screenOn'));
    }

    public function testCommandReturnsFalseAndKeepsStatusText(): void
    {
        $client = $this->client();
        $client->response = ['{"status":"Error","statustext":"Unknown command"}', 200, ''];

        $this->assertFalse($client->command('foo'));
        $this->assertSame('Unknown command', $client->getLastError());
    }

    public function testWrongPasswordAnswerIsAnError(): void
    {
        // Fully antwortet mit HTTP 200 und status "Error", nicht mit HTTP 401
        $client = $this->client();
        $client->response = ['{"status":"Error","statustext":"Please login"}', 200, ''];

        $this->assertFalse($client->request('getDeviceInfo'));
        $this->assertSame('Please login', $client->getLastError());
    }

    public function testHttpErrorCodeIsAnError(): void
    {
        $client = $this->client();
        $client->response = ['denied', 401, ''];

        $this->assertFalse($client->request('getDeviceInfo'));
        $this->assertSame('HTTP 401', $client->getLastError());
    }

    public function testConnectionFailureIsAnError(): void
    {
        $client = $this->client();
        $client->response = [false, 0, 'Connection timed out'];

        $this->assertFalse($client->request('getDeviceInfo'));
        $this->assertSame('Connection timed out', $client->getLastError());
    }

    public function testInvalidJsonIsAnError(): void
    {
        $client = $this->client();
        $client->response = ['<html>', 200, ''];

        $this->assertFalse($client->request('getDeviceInfo'));
        $this->assertSame('Invalid response', $client->getLastError());
    }

    public function testDeviceInfoWithoutStatusFieldIsReturned(): void
    {
        $client = $this->client();
        $client->response = ['{"deviceName":"Tablet","batteryLevel":70}', 200, ''];

        $this->assertSame(['deviceName' => 'Tablet', 'batteryLevel' => 70], $client->request('getDeviceInfo'));
    }

    private function client(string $Password = 'geheim', bool $Https = false): FKBApiClientFake
    {
        $this->debug = [];
        return new FKBApiClientFake('192.0.2.10', 2323, $Password, $Https, function (string $Message, string $Data): void
        {
            $this->debug[] = [$Message, $Data];
        });
    }
}
