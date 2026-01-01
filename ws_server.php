<?php
require __DIR__ . '/vendor/autoload.php';
require 'db.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
    protected $clients;
    protected $pdo;

    public function __construct($pdo)
    {
        $this->clients = new \SplObjectStorage;
        $this->pdo = $pdo;
        echo "WebSocket server started\n";
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection: {$conn->resourceId}\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        if (!$data) return;

        $sender = $data['sender_id'];
        $receiver = $data['receiver_id'];
        $message = $data['message'];

        // Save message to DB
        $stmt = $this->pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->execute([$sender, $receiver, $message]);

        // Broadcast message to all clients (simulate webhook push)
        foreach ($this->clients as $client) {
            $client->send(json_encode([
                'sender_id' => (string)$sender,
                'receiver_id' => (string)$receiver,
                'message' => $message
            ]));
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} closed\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Error: " . $e->getMessage() . "\n";
        $conn->close();
    }
}

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(new WsServer(new Chat($pdo))),
    8080
);

$server->run();
