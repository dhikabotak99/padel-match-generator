<template>
  <div class="min-h-screen bg-sage-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
      <div class="flex justify-between items-center mb-8">
        <div>
          <h2 class="text-4xl font-extrabold text-sage-800">Your Matches</h2>
          <p class="mt-2 text-lg text-sage-600">Manage and view all your tournaments</p>
        </div>
        <router-link to="/create" class="bg-sage-600 hover:bg-sage-700 text-gray-800 font-bold py-3 px-6 rounded-xl shadow-lg transition transform hover:-translate-y-1">
          + New Match
        </router-link>
      </div>

      <div v-if="loading" class="text-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-sage-600 mx-auto mb-4"></div>
        <p class="text-sage-500">Loading matches...</p>
      </div>

      <div v-else-if="matches.length === 0" class="text-center py-20 bg-white rounded-3xl shadow-sm border border-sage-100">
        <p class="text-xl text-sage-400 mb-6">No matches found.</p>
        <router-link to="/create" class="text-sage-600 font-bold hover:text-sage-800 underline">Start your first match!</router-link>
      </div>

      <div v-else class="grid performant-grid gap-6">
        <div v-for="match in matches" :key="match.id" class="bg-white rounded-2xl p-6 shadow-sm border border-sage-100 hover:shadow-md transition duration-200">
          <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div class="mb-4 md:mb-0">
               <div class="flex items-center space-x-3 mb-2">
                 <h3 class="text-xl font-bold text-sage-900">{{ match.name || 'Untitled Match' }}</h3>
                 <span class="bg-sage-100 text-sage-800 px-2 py-0.5 rounded text-xs font-bold uppercase">{{ match.type }}</span>
               </div>
               <div class="text-sm text-sage-500 space-y-1">
                 <p>{{ new Date(match.created_at).toLocaleDateString() }} • {{ match.players_count }} Players</p>
                 <p>Status: <span :class="getStatusClass(match.status)" class="capitalize font-medium">{{ match.status }}</span></p>
               </div>
            </div>
            
            <div class="flex space-x-3 w-full md:w-auto">
              <router-link :to="{ name: 'MatchLobby', params: { id: match.id }}" class="flex-1 md:flex-none text-center bg-white border-2 border-sage-100 hover:border-sage-300 text-sage-700 font-bold py-2 px-6 rounded-lg transition">
                Lobby
              </router-link>
              <router-link :to="{ name: 'MatchView', params: { id: match.id }}" class="flex-1 md:flex-none text-center bg-sage-600 hover:bg-sage-700 text-gray-800 font-bold py-2 px-6 rounded-lg shadow-md transition">
                View
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const matches = ref([]);
const loading = ref(true);

const fetchMatches = async () => {
  try {
    const response = await axios.get('/api/matches');
    matches.value = response.data;
  } catch (error) {
    console.error('Error fetching matches:', error);
  } finally {
    loading.value = false;
  }
};

const getStatusClass = (status) => {
  switch(status) {
    case 'active': return 'text-green-600';
    case 'completed': return 'text-blue-600';
    default: return 'text-yellow-600';
  }
};

onMounted(() => {
  fetchMatches();
});
</script>
