<?php

namespace Onyx\Hangouts\Helpers;

use Exception;
use Onyx\Hangouts\Network\Http;

class Messages extends Http
{
    /**
     * @param \Onyx\User $user
     * @param $msg
     *
     * @return bool
     */
    public function sendMessage($user, $msg)
    {
        $chat_id = $this->findUserOrChatId($user);

        return $this->_send($chat_id, $msg);
    }

    /**
     * @param $msg
     *
     * @return bool
     */
    public function sendGroupMessage($msg)
    {
        $chat_id = config('services.panda.group_id');

        if (strlen($chat_id) > 1) {
            return $this->_send($chat_id, $msg);
        } else {
            return false;
        }
    }

    /**
     * @param \Onyx\User $user
     *
     * @return int
     */
    public function findUserOrChatId($user)
    {
        if (strlen($user->chat_id) > 1) {
            return $user->chat_id;
        } else {
            $this->_send($user->google_id, 'Please issue the command <strong>/bot setup</strong>. This saves me processing power and is a requirement before I can talk to you.');

            return $user->google_id;
        }
    }

    //------------------------------------------------------
    // Private Functions
    //------------------------------------------------------

    /**
     * @param $id
     * @param $msg
     *
     * @throws Exception
     *
     * @return bool
     */
    private function _send($id, $msg)
    {
        $url = env('BOT_HOST').':'.env('BOT_PORT');

        try {
            $this->postJson($url, $id, $msg);
        } catch (Exception $e) {
            // Currently ignoring 500 errors because hangupsbot dies with this
            // > results = results.encode("ascii", "xmlcharrefreplace")
            // > AttributeError: 'bool' object has no attribute 'encode'
            if ($e->getCode() !== 500) {
                throw $e;
            }
        }
    }
}
