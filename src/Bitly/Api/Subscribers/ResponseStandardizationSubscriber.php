<?php

namespace Bitly\Api\Subscribers;

use Guzzle\Common\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 *  Organize the response to better fit REST standard.
 */
class ResponseStandardizationSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return array('request.complete' => array('StandardizeResponse', 255));
    }

    /**
     * @param Event $event
     *  - Bitly always returns 200 as status code. This set the correct status code for responses.
     *  - In case of a valid response from Bitly API we just return the 'data' section of the original response
     */
    public function StandardizeResponse(Event $event)
    {
        if ($event['response']->json()['status_code'] === 200)
            return $event['response']->setBody(json_encode($event['response']->json()['data']));
        
        $event['response']->setStatus($event['response']->json()['status_code'], $event['response']->json()['status_txt']);
        
    }
}