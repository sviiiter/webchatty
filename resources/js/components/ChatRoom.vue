<template>
  <div class="flex-1 flex flex-col min-h-0">
    <div class="px-6 py-3 border-b border-gray-800 shrink-0">
      <h2 class="font-semibold text-gray-200"># {{ room.name }}</h2>
    </div>
    <MessageList :messages="messages" />
    <MessageInput @send="sendMessage" />
  </div>
</template>

<script setup>
import { ref, watch, onUnmounted } from 'vue';
import axios from 'axios';
import { makeEcho } from '../echo.js';
import MessageList from './MessageList.vue';
import MessageInput from './MessageInput.vue';

const props   = defineProps({ room: Object, token: String });
const messages = ref([]);
let echo = null;

async function load() {
  const { data } = await axios.get(`rooms/${props.room.id}/messages`);
  messages.value = [...data.data].reverse();
}

async function sendMessage(content) {
  await axios.post(`rooms/${props.room.id}/messages`, { content });
  messages.value.push({
    id: Date.now(),
    content,
    user: { name: 'You' },
    created_at: new Date().toISOString(),
  });
}

function subscribe() {
  echo = makeEcho(props.token);
  echo.private(`chat.${props.room.id}`)
    .listen('.message.sent', ({ message }) => {
      messages.value.push(message);
    });
}

function unsubscribe() {
  echo?.disconnect();
  echo = null;
}

watch(() => props.room.id, async () => {
  unsubscribe();
  await load();
  subscribe();
}, { immediate: true });

onUnmounted(unsubscribe);
</script>
