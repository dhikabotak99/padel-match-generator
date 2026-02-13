<template>
  <nav class="bg-sage-600 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <router-link to="/" class="flex-shrink-0 flex items-center">
            <span class="text-gray-800 font-extrabold text-xl tracking-tight">Padel-In</span>
          </router-link>
          <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
            <router-link to="/" class="text-sage-100 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium transition">
              Home
            </router-link>
            <router-link v-if="user" to="/matches" class="text-sage-100 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium transition">
              My Matches
            </router-link>
            <router-link v-if="user" to="/create" class="text-sage-100 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium transition">
              Create Match
            </router-link>
          </div>
        </div>
        <div class="flex items-center">
          <div v-if="user" class="flex items-center space-x-4">
             <span class="text-sage-200 text-sm hidden md:block">Welcome, {{ user.name }}</span>
             <button @click="logout" class="bg-sage-700 hover:bg-sage-800 text-gray-800 px-4 py-2 rounded-lg text-sm font-bold outline transition transform hover:scale-105 hover:shadow-2xl ">
               Logout
             </button>
          </div>
          <div v-else class="flex items-center space-x-3">
            <router-link to="/login" class="text-gray-800 hover:text-sage-100 font-medium text-sm transition">
              Login
            </router-link>
            <router-link to="/register" class="bg-white text-sage-600 hover:bg-sage-50 px-4 py-2 rounded-lg text-sm font-bold shadow transition">
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
        localStorage.removeItem('user');
        window.location.href = '/login'; // Force refresh to clear state
    } catch (error) {
        console.error('Logout failed:', error);
    }
};
</script>
