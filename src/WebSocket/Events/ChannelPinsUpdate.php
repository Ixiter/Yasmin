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
 * @see https://discordapp.com/developers/docs/topics/gateway#channel-pins-update
 * @internal
 */
class ChannelPinsUpdate {
    protected $client;
    
    function __construct(\CharlotteDunois\Yasmin\Client $client) {
        $this->client = $client;
    }
    
    function handle(array $data) {
        $channel = $this->client->channels->get($data['channel_id']);
        if($channel) {
            $time = (!empty($data['last_pin_timestamp']) ? \CharlotteDunois\Yasmin\Utils\DataHelpers::makeDateTime((int) $data['last_pin_timestamp']) : null);
            $this->client->emit('channelPinsUpdate', $channel, $time);
        }
    }
}
