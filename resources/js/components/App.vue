<template>
  <div class="h-screen flex flex-col">
    <LoginForm v-if="!user" @authenticated="onAuthenticated" />
    <template v-else>
      <header class="bg-gray-900 border-b border-gray-800 px-6 py-3 flex items-center justify-between shrink-0">
        <span class="font-semibold text-indigo-400 tracking-wide">WebChat</span>
        <div class="flex items-center gap-4">
          <span class="text-sm text-gray-400">{{ user.name }}</span>
          <button
            @click="logout"
            class="text-sm text-gray-500 hover:text-gray-200 transition-colors"
          >Logout</button>
        </div>
      </header>
      <div class="flex flex-1 min-h-0">
        <RoomList :token="token" @select="selectedRoom = $event" :selected-id="selectedRoom?.id" />
        <ChatRoom v-if="selectedRoom" :room="selectedRoom" :token="token" />
        <div v-else class="flex-1 flex items-center justify-center text-gray-600">
          Select a room to start chatting
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import LoginForm from './LoginForm.vue';
import RoomList from './RoomList.vue';
import ChatRoom from './ChatRoom.vue';

const user  = ref(null);
const token = ref(null);
const selectedRoom = ref(null);

const savedToken = localStorage.getItem('auth_token');
const savedUser  = localStorage.getItem('auth_user');
if (savedToken && savedUser) {
  token.value = savedToken;
  user.value  = JSON.parse(savedUser);
}

function onAuthenticated(payload) {
  token.value = payload.token;
  user.value  = payload.user;
  localStorage.setItem('auth_token', payload.token);
  localStorage.setItem('auth_user', JSON.stringify(payload.user));
  axios.defaults.headers.common['Authorization'] = `Bearer ${payload.token}`;
}

async function logout() {
  await axios.post('auth/logout').catch(() => {});
  localStorage.removeItem('auth_token');
  localStorage.removeItem('auth_user');
  delete axios.defaults.headers.common['Authorization'];
  user.value = null;
  token.value = null;
  selectedRoom.value = null;
}
</script>
