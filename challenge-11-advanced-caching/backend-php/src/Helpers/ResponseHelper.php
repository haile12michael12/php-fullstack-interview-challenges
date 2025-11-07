<?php

namespace App\Helpers;

class ResponseHelper
{
    public function json(array $data, int $statusCode = 200): string
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        return json_encode($data);
    }
    
    public function jsonp(array $data, string $callback, int $statusCode = 200): string
    {
        http_response_code($statusCode);
        header('Content-Type: application/javascript');
        return $callback . '(' . json_encode($data) . ');';
    }
    
    public function xml(array $data, int $statusCode = 200): string
    {
        http_response_code($statusCode);
        header('Content-Type: application/xml');
        return $this->arrayToXml($data);
    }
    
    private function arrayToXml(array $data, string $rootElement = 'response'): string
    {
        $xml = new \SimpleXMLElement("<{$rootElement}/>");
        $this->arrayToXmlRecursive($data, $xml);
        return $xml->asXML();
    }
    
    private function arrayToXmlRecursive(array $data, \SimpleXMLElement $xml): void
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key)) {
                    $subnode = $xml->addChild("$key");
                    $this->arrayToXmlRecursive($value, $subnode);
                } else {
                    $this->arrayToXmlRecursive($value, $xml);
                }
            } else {
                $xml->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }
}