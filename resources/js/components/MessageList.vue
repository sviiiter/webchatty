<template>
  <div ref="container" class="flex-1 overflow-y-auto px-6 py-4 flex flex-col gap-3">
    <div
      v-for="msg in messages"
      :key="msg.id"
      class="flex flex-col gap-0.5"
    >
      <span class="text-xs font-semibold text-indigo-400">{{ msg.user?.name ?? 'Unknown' }}</span>
      <p class="text-sm text-gray-200 leading-relaxed">{{ msg.content }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, nextTick } from 'vue';

const props     = defineProps({ messages: Array });
const container = ref(null);

watch(() => props.messages?.length, async () => {
  await nextTick();
  if (container.value) container.value.scrollTop = container.value.scrollHeight;
});
</script>
