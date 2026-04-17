<template>
  <aside class="w-64 bg-gray-900 border-r border-gray-800 flex flex-col shrink-0">
    <div class="p-4 border-b border-gray-800">
      <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Rooms</h2>
      <form @submit.prevent="createRoom" class="flex gap-2">
        <input
          v-model="newRoomName"
          placeholder="New room…"
          class="flex-1 bg-gray-800 border border-gray-700 rounded-lg px-3 py-1.5 text-sm
                 text-gray-100 placeholder-gray-600 focus:outline-none focus:border-indigo-500"
        />
        <button
          type="submit"
          class="bg-indigo-600 hover:bg-indigo-500 px-3 py-1.5 rounded-lg text-sm font-medium transition-colors"
        >+</button>
      </form>
    </div>
    <ul class="flex-1 overflow-y-auto py-2">
      <li
        v-for="room in rooms"
        :key="room.id"
        @click="$emit('select', room)"
        :class="selectedId === room.id ? 'bg-indigo-600/20 text-indigo-300' : 'text-gray-300 hover:bg-gray-800'"
        class="px-4 py-2 cursor-pointer text-sm transition-colors flex items-center gap-2"
      >
        <span class="text-gray-500">#</span>{{ room.name }}
      </li>
    </ul>
  </aside>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({ token: String, selectedId: Number });
defineEmits(['select']);

const rooms       = ref([]);
const newRoomName = ref('');

async function load() {
  const { data } = await axios.get('rooms');
  rooms.value = data;
}

async function createRoom() {
  if (!newRoomName.value.trim()) return;
  const { data } = await axios.post('rooms', { name: newRoomName.value });
  rooms.value.push(data);
  newRoomName.value = '';
}

onMounted(load);
</script>
