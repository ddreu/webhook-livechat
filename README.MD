# Simple PHP Live Chat App

This repository contains **two versions** of a simple one-on-one live chat app using PHP and MySQL:

1. **Webhook / WebSocket version** – real-time messages using Ratchet WebSocket server (no polling).
2. **AJAX polling version** – periodically fetches new messages from the server.

Both versions share the **same MySQL database** schema.

---

## Database Setup

```sql
CREATE DATABASE webchat CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE webchat;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username) VALUES ('Alice'), ('Bob');
```
