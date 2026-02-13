<template>
  <nav class="bg-sky-600 shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <router-link to="/" class="flex-shrink-0 flex items-center">
            <span class="text-white font-extrabold text-xl tracking-tight">PadelIn</span>
          </router-link>
          <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
            <router-link to="/" class="text-sky-100 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition">
              Home
            </router-link>
            <router-link v-if="user" to="/matches" class="text-sky-100 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition">
              My Matches
            </router-link>
            <router-link v-if="user" to="/create" class="text-sky-100 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition">
              Create Match
            </router-link>
          </div>
        </div>
        <div class="flex items-center">
          <div v-if="user" class="flex items-center space-x-4">
             <span class="text-sky-200 text-sm hidden md:block">Welcome, {{ user.name }}</span>
             <button @click="logout" class="bg-sky-700 hover:bg-sky-800 text-white px-4 py-2 rounded-lg text-sm font-bold transition">
               Logout
             </button>
          </div>
          <div v-else class="flex items-center space-x-3">
            <router-link to="/login" class="text-white hover:text-sky-100 font-medium text-sm transition">
              Login
            </router-link>
            <router-link to="/register" class="bg-white text-sky-600 hover:bg-sky-50 px-4 py-2 rounded-lg text-sm font-bold shadow transition">
              Register
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const user = computed(() => {
    const userStr = localStorage.getItem('user');
    return userStr ? JSON.parse(userStr) : null;
});

const router = useRouter();

const logout = async () => {
    try {
        await axios.post('/api/logout');
    } catch (error) {
        console.error('Logout failed:', error);
    } finally {
        localStorage.removeItem('user');
        window.location.href = '/login'; 
    }
};
</script>
