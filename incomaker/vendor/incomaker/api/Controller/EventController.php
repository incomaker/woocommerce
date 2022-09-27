<?php

namespace Incomaker\Api\Controller;

use Incomaker\Api\Data\Event;

/**
 * Description of EventController
 */
class EventController extends Controller {

    /**
     * 
     * @param type $permId
     * @return Event
     */
    public function createEventObject($permId) {
        return new Event($permId);
    }

    /**
     * Adds event
     * 
     * (createEventObject)
     * 
     * @param Event $event
     * @return response
     */
    public function addEvent(Event $event) {
        return $this->curlPostQuery("event/", "", $event->getData());
    }

}
