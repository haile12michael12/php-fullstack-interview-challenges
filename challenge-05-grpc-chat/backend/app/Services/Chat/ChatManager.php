<?php

namespace App\Services\Chat;

class ChatManager
{
    private array $rooms = [];
    private array $userRooms = [];
    private array $messages = [];

    public function createRoom(string $name, array $participants = []): string
    {
        $roomId = uniqid('room_');
        $this->rooms[$roomId] = [
            'id' => $roomId,
            'name' => $name,
            'participants' => $participants,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Add participants to the room
        foreach ($participants as $userId) {
            if (!isset($this->userRooms[$userId])) {
                $this->userRooms[$userId] = [];
            }
            $this->userRooms[$userId][] = $roomId;
        }
        
        return $roomId;
    }

    public function joinRoom(string $roomId, int $userId): bool
    {
        if (!isset($this->rooms[$roomId])) {
            return false;
        }
        
        if (!in_array($userId, $this->rooms[$roomId]['participants'])) {
            $this->rooms[$roomId]['participants'][] = $userId;
        }
        
        if (!isset($this->userRooms[$userId])) {
            $this->userRooms[$userId] = [];
        }
        
        if (!in_array($roomId, $this->userRooms[$userId])) {
            $this->userRooms[$userId][] = $roomId;
        }
        
        return true;
    }

    public function leaveRoom(string $roomId, int $userId): bool
    {
        if (!isset($this->rooms[$roomId])) {
            return false;
        }
        
        // Remove user from room participants
        $key = array_search($userId, $this->rooms[$roomId]['participants']);
        if ($key !== false) {
            unset($this->rooms[$roomId]['participants'][$key]);
            $this->rooms[$roomId]['participants'] = array_values($this->rooms[$roomId]['participants']);
        }
        
        // Remove room from user's rooms
        if (isset($this->userRooms[$userId])) {
            $key = array_search($roomId, $this->userRooms[$userId]);
            if ($key !== false) {
                unset($this->userRooms[$userId][$key]);
                $this->userRooms[$userId] = array_values($this->userRooms[$userId]);
            }
        }
        
        return true;
    }

    public function sendMessage(string $roomId, int $userId, string $message): string
    {
        if (!isset($this->rooms[$roomId]) || !in_array($userId, $this->rooms[$roomId]['participants'])) {
            throw new \Exception('User is not in this room');
        }
        
        $messageId = uniqid('msg_');
        $this->messages[$messageId] = [
            'id' => $messageId,
            'room_id' => $roomId,
            'user_id' => $userId,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        return $messageId;
    }

    public function getRoomMessages(string $roomId): array
    {
        $roomMessages = [];
        foreach ($this->messages as $message) {
            if ($message['room_id'] === $roomId) {
                $roomMessages[] = $message;
            }
        }
        
        // Sort by timestamp
        usort($roomMessages, function($a, $b) {
            return strtotime($a['timestamp']) - strtotime($b['timestamp']);
        });
        
        return $roomMessages;
    }

    public function getUserRooms(int $userId): array
    {
        return $this->userRooms[$userId] ?? [];
    }

    public function getRoomParticipants(string $roomId): array
    {
        return $this->rooms[$roomId]['participants'] ?? [];
    }

    public function getRoom(string $roomId): ?array
    {
        return $this->rooms[$roomId] ?? null;
    }
}