<?php

namespace Interfaces;

interface DBConnectionInterface
{
    public function connect(): void;
    public function disconnect(): void;
    public function isConnected(): bool;
    public function query(string $sql, array $params = []);
    public function prepare(string $sql);
    public function execute(string $sql, array $params = []);
    public function getLastInsertId();
}