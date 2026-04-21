# WebChat

Real-time chat with rooms, built on Laravel 13 + Vue 3. Messages show up instantly for everyone in the room — no refreshing needed.

## What it does

- Sign up, log in, log out
- Create chat rooms and jump between them
- Send messages that appear live for all connected users (WebSockets via Reverb)
- Loads the last 50 messages when you open a room

## Stack

**Backend** — PHP 8.4, Laravel 13, Sanctum (auth), Reverb (WebSocket server)  
**Frontend** — Vue 3, Tailwind CSS 4, Vite, Laravel Echo  
**DB** — SQLite

## Getting started

You'll need Docker and Docker Compose — that's it.

```bash
git clone <repo-url> webchat && cd webchat
docker-compose up -d
```

Hit http://localhost when the containers are up.

This starts four containers: `nginx` (port 80), the PHP app, the Reverb WebSocket server (port 6001), and a queue worker.

## Tests

```bash
docker compose exec app composer run test
```
