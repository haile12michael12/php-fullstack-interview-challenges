<?php

namespace App\Traits;

trait SerializableTrait
{
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    public function toArray(): array
    {
        $array = [];
        foreach ($this as $key => $value) {
            // Skip traits and other non-serializable properties
            if (is_object($value) && !($value instanceof \Serializable)) {
                continue;
            }
            $array[$key] = $value;
        }
        return $array;
    }

    public function toXml(): string
    {
        $array = $this->toArray();
        return $this->arrayToXml($array);
    }

    protected function arrayToXml(array $array, string $rootElement = 'root'): string
    {
        $xml = new \SimpleXMLElement("<{$rootElement}/>");
        $this->arrayToXmlRecursive($array, $xml);
        return $xml->asXML();
    }

    protected function arrayToXmlRecursive(array $array, \SimpleXMLElement $xml): void
    {
        foreach ($array as $key => $value) {
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

    public function serialize(): string
    {
        return serialize($this->toArray());
    }

    public function unserialize(string $data): void
    {
        $array = unserialize($data);
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }
    }
}