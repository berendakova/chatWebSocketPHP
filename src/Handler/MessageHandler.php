<?php


namespace App\Handler;


use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Cookie;

class MessageHandler implements MessageComponentInterface
{
    protected $connections;
    private $logger;
    public function __construct()
    {

        $this->connections = new SplObjectStorage;

    }

    /**
     * When a new connection is opened it will be passed to this method
     * @param ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    function onOpen(ConnectionInterface $conn)
    {

        $this->connections->attach($conn);
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    function onClose(ConnectionInterface $conn)
    {
        $this->connections->detach($conn);
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param ConnectionInterface $conn
     * @param \Exception $e
     * @throws \Exception
     */
    function onError(ConnectionInterface $conn, \Exception $e)
    {
        $this->connections->detach($conn);
        $conn->close();
    }

    /**
     * Triggered when a client sends data through the socket
     * @param \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
     * @param string $msg The message received
     * @throws \Exception
     */
    function onMessage(ConnectionInterface $from, $msg)

    {
        $fd = fopen("index.txt", 'w') or die("не удалось создать файл");
        $arrayMessage = json_decode($msg,true);
        $name = $arrayMessage['name'];
        $message = $arrayMessage['message'];

        if(strpos($message,'subscribe')!==false){
         /*   $nameSubscribe = explode(' ',$message);
            $this->getResponse()->setCookie('myCookie', serialize($data));
            $data = unserialize($this->getRequest()->getCookie('myCookie'));
            fwrite($fd,$cookie);*/
        }
        fclose($fd);
        foreach ($this->connections as $connection) {
            if ($connection === $from) {
                continue;
            }
            $connection->send($msg);
        }
    }
}


