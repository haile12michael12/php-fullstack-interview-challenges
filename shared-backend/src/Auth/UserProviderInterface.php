<?php

namespace SharedBackend\Auth;

interface UserProviderInterface
{
    /**
     * Get a user by their username
     * 
     * @param string $username
     * @return array|null
     */
    public function getUserByUsername(string $username): ?array;
    
    /**
     * Get a user by their ID
     * 
     * @param string|int $id
     * @return array|null
     */
    public function getUserById($id): ?array;
}