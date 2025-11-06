<?php

namespace App\Service;

class PresetManager
{
    private array $presets = [];

    public function __construct()
    {
        $this->loadPresets();
    }

    public function getPreset(string $name): ?array
    {
        return $this->presets[$name] ?? null;
    }

    public function getAllPresets(): array
    {
        return $this->presets;
    }

    public function savePreset(string $name, array $operations): void
    {
        $this->presets[$name] = $operations;
        $this->savePresets();
    }

    public function deletePreset(string $name): void
    {
        unset($this->presets[$name]);
        $this->savePresets();
    }

    private function loadPresets(): void
    {
        $presetFile = __DIR__ . '/../Config/presets.php';
        if (file_exists($presetFile)) {
            $this->presets = include $presetFile;
        }
    }

    private function savePresets(): void
    {
        $presetFile = __DIR__ . '/../Config/presets.php';
        $exported = var_export($this->presets, true);
        file_put_contents($presetFile, "<?php\n\nreturn $exported;\n");
    }
}