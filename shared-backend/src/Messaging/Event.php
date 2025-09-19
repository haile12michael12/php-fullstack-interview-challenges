<?php

namespace SharedBackend\Messaging;

class Event
{
    private $name;
    private $payload;
    private $propagationStopped = false;
    
    /**
     * Create a new event
     * 
     * @param string $name
     * @param array $payload
     */
    public function __construct(string $name, array $payload = [])
    {
        $this->name = $name;
        $this->payload = $payload;
    }
    
    /**
     * Get event name
     * 
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * Get event payload
     * 
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
    
    /**
     * Set event payload
     * 
     * @param array $payload
     * @return self
     */
    public function setPayload(array $payload): self
    {
        $this->payload = $payload;
        return $this;
    }
    
    /**
     * Update a specific payload value
     * 
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function setPayloadValue(string $key, $value): self
    {
        $this->payload[$key] = $value;
        return $this;
    }
    
    /**
     * Stop event propagation
     * 
     * @return self
     */
    public function stopPropagation(): self
    {
        $this->propagationStopped = true;
        return $this;
    }
    
    /**
     * Check if propagation is stopped
     * 
     * @return bool
     */
    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }
}