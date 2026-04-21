# WebChat

Real-time chat with rooms, built on Laravel 13 + Vue 3. Messages show up instantly for everyone in the room — no refreshing needed.

## What it does

- Sign up, log in, log out
- Create chat rooms and jump between them
- Send messages that appear live for all connected users (WebSockets via Reverb)
- Loads the last 50 messages when you open a room
- Runs in Docker if you want

## Stack

**Backend** — Laravel 13, Sanctum (auth), Reverb (WebSocket server)  
**Frontend** — Vue 3, Tailwind CSS 4, Vite, Laravel Echo  
**DB** — SQLite out of the box, MySQL/Postgres also work

## Getting started

You'll need PHP 8.3+, Composer, and Node 20+.

```bash
git clone <repo-url> webchat && cd webchat
composer install && npm install

cp .env.example .env
php artisan key:generate

# flip this in your .env to turn on real-time messaging
# BROADCAST_CONNECTION=reverb

touch database/database.sqlite
php artisan migrate

composer run dev
```

Then open http://localhost:8000.

`composer run dev` spins up Laravel, the queue worker, Pail (log viewer), and Vite all at once.

**Fresh clone? One command does everything:**

```bash
composer run setup
```

Installs deps, copies `.env`, generates a key, migrates, builds assets.

## Docker

```bash
docker-compose up -d
```

Starts nginx (port 80), the PHP app, the Reverb WebSocket server (port 6001), and a queue worker. Hit http://localhost when it's up.

## Environment variables worth knowing

| Variable | Default | What it does |
|----------|---------|-------------|
| `BROADCAST_CONNECTION` | `log` | Change to `reverb` for live messaging |
| `REVERB_APP_ID` | — | Reverb app ID |
| `REVERB_APP_KEY` | — | Reverb app key |
| `REVERB_APP_SECRET` | — | Reverb app secret |
| `REVERB_HOST` | `localhost` | Where Reverb is running |
| `REVERB_PORT` | `8080` | Reverb port |
| `DB_CONNECTION` | `sqlite` | Database driver |

## How it's structured

```
resources/js/
├── app.js               # entry point, wires up Axios + auth token
├── echo.js              # WebSocket setup
└── components/
    ├── App.vue           # root, handles auth state
    ├── LoginForm.vue     # login / register
    ├── RoomList.vue      # room sidebar + create form
    ├── ChatRoom.vue      # the main chat view, listens on WS channel
    ├── MessageList.vue   # auto-scrolling messages
    └── MessageInput.vue  # the input box

app/
├── Models/              # User, Room, Message
├── Http/Controllers/    # AuthController, RoomController, MessageController
├── Events/MessageSent.php  # fires on new message → Reverb picks it up
└── Chat/                # MessageValidator, RoomNameFormatter
```

**Auth:** login → Sanctum token → saved in `localStorage` → attached to every request.

**Messaging:** send message → saved to DB → `MessageSent` event fires → Reverb pushes it to the private `chat.{roomId}` channel → everyone in that room sees it instantly.

## API

| Method | Endpoint | Needs auth? | Description |
|--------|----------|-------------|-------------|
| POST | `/api/auth/register` | no | create account |
| POST | `/api/auth/login` | no | get a token |
| POST | `/api/auth/logout` | yes | revoke token |
| GET | `/api/rooms` | no | list rooms |
| POST | `/api/rooms` | yes | create a room |
| GET | `/api/rooms/{room}/messages` | yes | last 50 messages |
| POST | `/api/rooms/{room}/messages` | yes | send a message |

## Tests

```bash
composer run test
```
