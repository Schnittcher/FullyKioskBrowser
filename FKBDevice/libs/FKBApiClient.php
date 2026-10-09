<?php

declare(strict_types=1);

/**
 * Client für die REST-Schnittstelle von Fully Kiosk Browser.
 * Das Passwort steht nie in Debug-Ausgaben oder Fehlermeldungen.
 */
class FKBApiClient
{
    private string $lastError = '';

    public function __construct(
        private string $Host,
        private int $Port,
        private string $Password,
        private bool $UseHTTPS,
        private Closure $Debug,
        private int $Timeout = 5
    ) {
    }

    public function getLastError(): string
    {
        return $this->lastError;
    }

    /**
     * Führt einen Befehl aus.
     *
     * @return array|string|false Bei $Json das dekodierte Ergebnis, sonst der rohe Inhalt (zum Beispiel PNG), false bei einem Fehler
     */
    public function request(string $Command, array $Parameters = [], bool $Json = true): array|string|false
    {
        $this->lastError = '';

        $query = ['cmd' => $Command];
        foreach ($Parameters as $key => $value) {
            $query[$key] = is_bool($value) ? ($value ? 'true' : 'false') : (string) $value;
        }
        $base = ($this->UseHTTPS ? 'https://' : 'http://') . $this->Host . ':' . $this->Port . '/?';
        $debugUrl = $base . http_build_query($query, '', '&', PHP_QUERY_RFC3986);

        if ($this->Password !== '') {
            $query['password'] = $this->Password;
        }
        if ($Json) {
            $query['type'] = 'json';
        }
        $url = $base . http_build_query($query, '', '&', PHP_QUERY_RFC3986);

        ($this->Debug)('Request URL', $debugUrl);

        [$body, $code, $error] = $this->send($url);

        if ($body === false) {
            $this->lastError = $error;
            ($this->Debug)('Request Error', $error);
            return false;
        }
        if ($code >= 400) {
            $this->lastError = 'HTTP ' . $code;
            ($this->Debug)('Request Error', $this->lastError);
            return false;
        }
        if (!$Json) {
            return (string) $body;
        }

        ($this->Debug)('Request Result', (string) $body);
        $data = json_decode((string) $body, true);
        if (!is_array($data)) {
            $this->lastError = 'Invalid response';
            return false;
        }
        // Fully antwortet bei falschem oder fehlendem Passwort mit HTTP 200 und {"status":"Error",...}
        if (($data['status'] ?? '') === 'Error') {
            $this->lastError = (string) ($data['statustext'] ?? 'Error');
            return false;
        }
        return $data;
    }

    /**
     * Führt einen Befehl aus und prüft den Status der Antwort.
     */
    public function command(string $Command, array $Parameters = []): bool
    {
        $result = $this->request($Command, $Parameters);
        if (!is_array($result)) {
            return false;
        }
        if (($result['status'] ?? '') !== 'OK') {
            $this->lastError = (string) ($result['statustext'] ?? 'Status not OK');
            return false;
        }
        return true;
    }

    /**
     * Sendet die Anfrage per HTTP.
     *
     * @return array [Inhalt oder false, HTTP-Statuscode, Fehlertext]
     */
    protected function send(string $Url): array
    {
        $curl = curl_init($Url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => $this->Timeout,
            CURLOPT_TIMEOUT        => $this->Timeout * 2,
            // Das Tablet nutzt im lokalen Netz meist ein selbst signiertes Zertifikat
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0
        ]);
        $body = curl_exec($curl);
        $code = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $error = curl_error($curl);
        curl_close($curl);
        return [$body, $code, $error];
    }
}
