<template>
  <div class="min-h-screen bg-fuchsia-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <h2 class="mt-6 text-center text-3xl font-extrabold text-fuchsia-900">
        Create a new account
      </h2>
      <p class="mt-2 text-center text-sm text-fuchsia-600">
        Or
        <router-link to="/login" class="font-medium text-fuchsia-600 hover:text-fuchsia-500 underline">
          sign in to existing account
        </router-link>
      </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-fuchsia-100">
        <form class="space-y-6" @submit.prevent="handleRegister">
          <div>
            <label for="name" class="block text-sm font-medium text-fuchsia-700"> Full Name </label>
            <div class="mt-1">
              <input id="name" v-model="form.name" type="text" autocomplete="name" required class="appearance-none block w-full px-3 py-2 border border-fuchsia-300 rounded-md shadow-sm placeholder-fuchsia-400 focus:outline-none focus:ring-fuchsia-500 focus:border-fuchsia-500 sm:text-sm">
            </div>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-fuchsia-700"> Email address </label>
            <div class="mt-1">
              <input id="email" v-model="form.email" type="email" autocomplete="email" required class="appearance-none block w-full px-3 py-2 border border-fuchsia-300 rounded-md shadow-sm placeholder-fuchsia-400 focus:outline-none focus:ring-fuchsia-500 focus:border-fuchsia-500 sm:text-sm">
            </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-fuchsia-700"> Password </label>
            <div class="mt-1">
              <input id="password" v-model="form.password" type="password" required class="appearance-none block w-full px-3 py-2 border border-fuchsia-300 rounded-md shadow-sm placeholder-fuchsia-400 focus:outline-none focus:ring-fuchsia-500 focus:border-fuchsia-500 sm:text-sm">
            </div>
          </div>

          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-fuchsia-700"> Confirm Password </label>
            <div class="mt-1">
              <input id="password_confirmation" v-model="form.password_confirmation" type="password" required class="appearance-none block w-full px-3 py-2 border border-fuchsia-300 rounded-md shadow-sm placeholder-fuchsia-400 focus:outline-none focus:ring-fuchsia-500 focus:border-fuchsia-500 sm:text-sm">
            </div>
          </div>

          <div v-if="error" class="text-red-500 text-sm text-center font-medium">
              {{ error }}
          </div>

          <div>
            <button type="submit" :disabled="loading" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-gray-800 bg-fuchsia-600 hover:bg-fuchsia-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-fuchsia-500 transition disabled:opacity-50">
              {{ loading ? 'Registering...' : 'Register' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { auth } from '../services/auth';

const router = useRouter();
const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
});
const error = ref(null);
const loading = ref(false);

const handleRegister = async () => {
    loading.value = true;
    error.value = null;
    
    try {
        await axios.get('/sanctum/csrf-cookie');
        const response = await axios.post('/api/register', form.value);
        auth.setUser(response.data.user);
        window.location.href = '/matches'; // Force refresh
    } catch (err) {
        if (err.response && err.response.data && err.response.data.errors) {
            error.value = Object.values(err.response.data.errors).flat().join(' ');
        } else {
            error.value = 'Failed to register. Please try again.';
        }
    } finally {
        loading.value = false;
    }
};
</script>
