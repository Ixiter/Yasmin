<?php
/**
 * Yasmin
 * Copyright 2017 Charlotte Dunois, All Rights Reserved
 *
 * Website: https://charuru.moe
 * License: https://github.com/CharlotteDunois/Yasmin/blob/master/LICENSE
*/

namespace CharlotteDunois\Yasmin\WebSocket\Events;

/**
 * WS Event
 * @see https://discordapp.com/developers/docs/topics/gateway#message-reaction-remove
 * @internal
 */
class MessageReactionRemove {
    protected $client;
    
    function __construct(\CharlotteDunois\Yasmin\Client $client) {
        $this->client = $client;
    }
    
    function handle(array $data) {
        $channel = $this->client->channels->get($data['channel_id']);
        if($channel) {
            $message = $channel->messages->get($data['message_id']);
            if($message) {
                $id = (!empty($data['emoji']['id']) ? $data['emoji']['id'] : $data['emoji']['name']);
                
                $reaction = $message->reactions->get($id);
                if($reaction) {
                    $reaction->_decrementCount();
                    
                    $user = $this->client->users->get($data['user_id']);
                    if($user) {
                        $reaction->users->delete($user->id);
                    }
                    
                    if($reaction->count === 0) {
                        $message->reactions->delete(($reaction->emoji->id ?? $reaction->emoji->name));
                    }
                    
                    $this->client->emit('messageReactionRemove', $reaction);
                }
            }
        }
    }
}