<?php
namespace ModelBundle\Model;


use paragraph1\phpFCM\Message;

class MessageWithContent extends Message
{
     /**
     * Represents the app's "Send-to-Sync" message.
     *
     * @var bool
     */
    private $contentAvailableFlag;

    /**
     * @see https://firebase.google.com/docs/cloud-messaging/concept-options#collapsible_and_non-collapsible_messages
     *
     * @return \paragraph1\phpFCM\Message
     */
    public function setContentAvailable() {
        $this->contentAvailableFlag = TRUE;
        return $this;
    }

     public function jsonSerialize()
    {
        $jsonData = parent::jsonSerialize();
        if ($this->contentAvailableFlag === TRUE) {
            $jsonData['content_available'] = TRUE;
        }
        return $jsonData;
    }


}
