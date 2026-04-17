<template>
  <div class="h-screen flex items-center justify-center bg-gray-950">
    <div class="w-full max-w-sm bg-gray-900 rounded-2xl p-8 shadow-xl border border-gray-800">
      <h1 class="text-2xl font-bold text-center mb-6 text-indigo-400">WebChat</h1>

      <div class="flex gap-2 mb-6">
        <button
          @click="mode = 'login'"
          :class="mode === 'login' ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:text-white'"
          class="flex-1 py-1.5 rounded-lg text-sm font-medium transition-colors"
        >Login</button>
        <button
          @click="mode = 'register'"
          :class="mode === 'register' ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:text-white'"
          class="flex-1 py-1.5 rounded-lg text-sm font-medium transition-colors"
        >Register</button>
      </div>

      <form @submit.prevent="submit" class="flex flex-col gap-4">
        <input
          v-if="mode === 'register'"
          v-model="form.name"
          placeholder="Name"
          class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-sm text-gray-100 placeholder-gray-500 focus:outline-none focus:border-indigo-500 transition-colors w-full"
          required
        />
        <input
          v-model="form.email"
          type="email"
          placeholder="Email"
          class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-sm text-gray-100 placeholder-gray-500 focus:outline-none focus:border-indigo-500 transition-colors w-full"
          required
        />
        <input
          v-model="form.password"
          type="password"
          placeholder="Password"
          class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-sm text-gray-100 placeholder-gray-500 focus:outline-none focus:border-indigo-500 transition-colors w-full"
          required
        />
        <p v-if="error" class="text-red-400 text-sm">{{ error }}</p>
        <button
          type="submit"
          :disabled="loading"
          class="bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 py-2 rounded-lg font-semibold transition-colors"
        >{{ loading ? '...' : (mode === 'login' ? 'Login' : 'Create account') }}</button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import axios from 'axios';

const emit = defineEmits(['authenticated']);
const mode    = ref('login');
const loading = ref(false);
const error   = ref('');
const form    = reactive({ name: '', email: '', password: '' });

async function submit() {
  error.value   = '';
  loading.value = true;
  try {
    const endpoint = mode.value === 'login' ? 'auth/login' : 'auth/register';
    const { data } = await axios.post(endpoint, form);
    emit('authenticated', data);
  } catch (e) {
    error.value = e.response?.data?.message ?? 'Something went wrong';
  } finally {
    loading.value = false;
  }
}
</script>
