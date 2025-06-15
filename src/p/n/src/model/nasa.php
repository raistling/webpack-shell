<?php
    global $API_KEY, $token;
    $token = $API_KEY;

    if(isset($_SESSION['token'])){
        $token = $_SESSION['token'];
    }

class Nasa
{
    private $date;
    private $headers;
    private $data;

    public function __construct($date)
    {
        $this->date = $date;
        $this->cURL();
    }

    public function getData()
    {
        return $this->data;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getRateLimit()
    {
        return $this->getValueHeader('X-Ratelimit-Limit');
    }

    public function getRateRemaining()
    {
        return $this->getValueHeader('X-Ratelimit-Remaining');
    }

    private function getApodUrl()
    {
        global $token;
        return 'https://api.nasa.gov/planetary/apod?date=' . $this->getDate() . '&api_key=' . $token;
    }

    private function cURL()
    {
        $ch = curl_init($this->getApodUrl());

        // Configurar opciones de cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);

        $response = curl_exec($ch); // Ejecutar la solicitud cURL

        if (curl_errno($ch)) {
            echo 'Error en la solicitud cURL: ' . curl_error($ch); // Verificar si hubo algún error
            exit;
        }
        curl_close($ch); // Cerrar cURL

        //$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Obtener el código de respuesta HTTP

        // Separar headers del contenido
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $header_size);
        $content = substr($response, $header_size);

        $headersArray = [];
        $headersLines = explode("\r\n", $headers);

        foreach ($headersLines as $headerLine) {
            if (strpos($headerLine, ':') !== false) {
                list($key, $value) = explode(': ', $headerLine, 2);
                $headersArray[$key] = $value;
            }
        }

        // Asignar headers y contenido de la respuesta a los atributos de la clase
        $this->headers = $headersArray;
        $this->data = json_decode($content);
    }

    private function getValueHeader($headerToFind)
    {
        return $this->headers[strtolower($headerToFind)];
    }
}
