<?php

namespace MessageConsumer;

class ExampleMessageConsumer
{
    /**
     * @param string $message
     *
     * @return object
     */
    public function ProcessText(string $message): object
    {
        $obj = \json_decode($message);
        print ' [x] Processed ' . \print_r($obj->contents->text, true) . "\n";

        return $obj;
    }

    /**
     * @param string $message
     *
     * @return object
     */
    public function ProcessSong(string $message): object
    {
        $obj = \json_decode($message);
        print ' [x] Processed ' . \print_r($obj->contents->song, true) . "\n";

        return $obj;
    }
}
