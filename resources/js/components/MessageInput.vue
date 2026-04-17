<template>
  <div class="px-6 py-4 border-t border-gray-800 shrink-0">
    <form @submit.prevent="send" class="flex gap-3">
      <input
        v-model="text"
        placeholder="Type a message…"
        :disabled="sending"
        class="flex-1 bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-sm
               text-gray-100 placeholder-gray-500 focus:outline-none focus:border-indigo-500
               disabled:opacity-50 transition-colors"
        @keydown.enter.exact.prevent="send"
      />
      <button
        type="submit"
        :disabled="!text.trim() || sending"
        class="bg-indigo-600 hover:bg-indigo-500 disabled:opacity-40 px-4 py-2.5 rounded-xl
               text-sm font-medium transition-colors"
      >Send</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const emit   = defineEmits(['send']);
const text   = ref('');
const sending = ref(false);

async function send() {
  const content = text.value.trim();
  if (!content) return;
  sending.value = true;
  text.value    = '';
  try {
    await emit('send', content);
  } finally {
    sending.value = false;
  }
}
</script>
