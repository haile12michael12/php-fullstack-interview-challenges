<?php

namespace App\Queue;

class JobDispatcher
{
    protected $queue = [];

    public function dispatch(callable $job, array $params = [])
    {
        // In a real implementation, this would push the job to a queue system
        // For this demo, we'll just store it in memory
        $this->queue[] = [
            'job' => $job,
            'params' => $params,
            'created_at' => time()
        ];
        
        return true;
    }

    public function dispatchAsync(callable $job, array $params = [])
    {
        // In a real implementation, this would push the job to a queue system for async processing
        // For this demo, we'll just call the job immediately
        return call_user_func_array($job, $params);
    }

    public function processQueue()
    {
        // In a real implementation, this would process jobs from the queue
        // For this demo, we'll just return the queue size
        return count($this->queue);
    }

    public function getQueueSize()
    {
        return count($this->queue);
    }
}