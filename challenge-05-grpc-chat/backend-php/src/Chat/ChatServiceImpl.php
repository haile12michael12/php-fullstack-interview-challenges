<?php

namespace App\Chat;

use Chat\ChatMessage;
use Chat\ChatHistory;
use Chat\ChatServiceInterface;
use Chat\Empty;
use Chat\HistoryRequest;
use Chat\JoinRequest;
use Chat\SendResponse;
use Chat\UserInfo;
use Chat\UserList;
use Spiral\GRPC;

class ChatServiceImpl implements ChatServiceInterface
{
    private $userManager;
    private $messageStore;
    private $streams = [];
    private $nextMessageId = 1;
    private $messageQueue = [];

    public function __construct()
    {
        $this->userManager = new UserManager();
        $this->messageStore = new MessageStore();
        
        // Initialize message ID from the last stored message
        $recentMessages = $this->messageStore->getRecentMessages(1);
        if (!empty($recentMessages)) {
            $lastMessage = $recentMessages[0];
            $this->nextMessageId = (int)$lastMessage['id'] + 1;
        }
    }

    /**
     * Join the chat and receive a stream of messages
     */
    public function JoinChat(GRPC\ContextInterface $ctx, JoinRequest $request): \Generator
    {
        $userId = $request->getUserId();
        $username = $request->getUsername();
        
        // Add user to active users
        $this->userManager->addUser($userId, $username);
        
        // Create join message
        $joinMessage = new ChatMessage();
        $joinMessage->setId((string)$this->nextMessageId++);
        $joinMessage->setUserId($userId);
        $joinMessage->setUsername($username);
        $joinMessage->setContent("$username has joined the chat");
        $joinMessage->setTimestamp(time());
        $joinMessage->setType(ChatMessage\MessageType::JOIN);
        
        // Add to history
        $this->messageStore->addMessage($joinMessage);
        
        // Broadcast join message to all users
        $this->broadcastMessage($joinMessage);
        
        // Create a unique stream ID for this connection
        $streamId = uniqid();
        $this->streams[$streamId] = [
            'user_id' => $userId,
            'username' => $username,
            'last_message_id' => $joinMessage->getId()
        ];
        
        try {
            // Send last 10 messages from history to the new user
            $recentMessages = $this->messageStore->getRecentMessages(10);
            foreach ($recentMessages as $messageData) {
                yield $this->messageStore->arrayToMessage($messageData);
            }
            
            // Keep connection open until client disconnects
            while (true) {
                // Check if there are new messages for this user
                yield from $this->waitForNewMessages($streamId);
                
                // Update user's last active time
                $this->userManager->updateActivity($userId);
                
                // Sleep to prevent CPU overuse
                usleep(100000); // 100ms
            }
        } finally {
            // Clean up when user disconnects
            unset($this->streams[$streamId]);
            
            // If this was the user's last connection, mark them as offline
            $stillActive = false;
            foreach ($this->streams as $stream) {
                if ($stream['user_id'] === $userId) {
                    $stillActive = true;
                    break;
                }
            }
            
            if (!$stillActive) {
                // Create leave message
                $leaveMessage = new ChatMessage();
                $leaveMessage->setId((string)$this->nextMessageId++);
                $leaveMessage->setUserId($userId);
                $leaveMessage->setUsername($username);
                $leaveMessage->setContent("$username has left the chat");
                $leaveMessage->setTimestamp(time());
                $leaveMessage->setType(ChatMessage\MessageType::LEAVE);
                
                // Add to history
                $this->messageStore->addMessage($leaveMessage);
                
                // Remove from active users
                $this->userManager->removeUser($userId);
                
                // Broadcast leave message
                $this->broadcastMessage($leaveMessage);
            }
        }
    }

    /**
     * Send a message to the chat
     */
    public function SendMessage(GRPC\ContextInterface $ctx, ChatMessage $message): SendResponse
    {
        $userId = $message->getUserId();
        
        // Check if user exists
        $user = $this->userManager->getUser($userId);
        if (!$user) {
            $response = new SendResponse();
            $response->setSuccess(false);
            $response->setError("User not found");
            return $response;
        }
        
        // Update user's last active time
        $this->userManager->updateActivity($userId);
        
        // Set message ID and timestamp
        $message->setId((string)$this->nextMessageId++);
        $message->setTimestamp(time());
        
        // Add to history
        $this->messageStore->addMessage($message);
        
        // Broadcast to all users
        $this->broadcastMessage($message);
        
        // Return success response
        $response = new SendResponse();
        $response->setSuccess(true);
        $response->setMessageId($message->getId());
        
        return $response;
    }

    /**
     * Get list of online users
     */
    public function GetOnlineUsers(GRPC\ContextInterface $ctx, Empty $request): UserList
    {
        $userList = new UserList();
        
        foreach ($this->userManager->getActiveUsers() as $userData) {
            $userInfo = $this->userManager->arrayToUserInfo($userData);
            $userList->addUsers($userInfo);
        }
        
        return $userList;
    }

    /**
     * Get chat history
     */
    public function GetChatHistory(GRPC\ContextInterface $ctx, HistoryRequest $request): ChatHistory
    {
        $limit = $request->getLimit() ?: 20;
        $beforeTimestamp = $request->getBeforeTimestamp() ?: PHP_INT_MAX;
        
        // Get messages from store
        $messageData = $this->messageStore->getMessagesBefore($beforeTimestamp, $limit + 1);
        
        // Create response
        $history = new ChatHistory();
        $count = 0;
        
        foreach ($messageData as $data) {
            if ($count < $limit) {
                $message = $this->messageStore->arrayToMessage($data);
                $history->addMessages($message);
                $count++;
            }
        }
        
        // Set has_more flag
        $history->setHasMore(count($messageData) > $limit);
        
        return $history;
    }

    /**
     * Broadcast a message to all connected users
     */
    private function broadcastMessage(ChatMessage $message)
    {
        // Add message to queue for all streams to pick up
        $this->messageQueue[] = $message;
        
        // Limit queue size
        if (count($this->messageQueue) > 100) {
            array_shift($this->messageQueue);
        }
    }

    /**
     * Wait for new messages
     */
    private function waitForNewMessages(string $streamId): \Generator
    {
        if (!isset($this->streams[$streamId])) {
            return;
        }
        
        $lastMessageId = $this->streams[$streamId]['last_message_id'];
        
        // Check for new messages in the queue
        foreach ($this->messageQueue as $message) {
            if ($message->getId() > $lastMessageId) {
                // Update last message ID for this stream
                $this->streams[$streamId]['last_message_id'] = $message->getId();
                yield $message;
            }
        }
    }
}