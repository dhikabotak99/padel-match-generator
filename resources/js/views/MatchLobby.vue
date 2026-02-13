<template>
  <div class="min-h-screen bg-sky-50 py-12 px-4">
    <div v-if="match" class="max-w-4xl mx-auto bg-white shadow-xl rounded-3xl overflow-hidden border border-sky-100 relative">
      <!-- Decorative Header -->
      <div class="h-24 md:h-32 bg-sky-600 relative overflow-hidden">
        <div class="absolute inset-0 bg-sky-700 opacity-20 transform -skew-y-3"></div>
      </div>
      
      <div class="px-6 md:px-8 pb-8 relative -mt-10 md:-mt-12">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 space-y-4 md:space-y-0">
            <div>
                <div class="bg-white p-2 rounded-xl shadow-md inline-block mb-3">
                    <span class="bg-sky-100 text-sky-800 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wide">{{ match.type }}</span>
                </div>
                <h2 class="text-2xl md:text-4xl font-extrabold text-sky-900 leading-tight">{{ match.name || 'Untitled Match' }}</h2>
                <div class="flex items-center space-x-2 mt-2 text-sky-600 text-sm font-medium">
                    <span>{{ match.scoring_type === '21' ? 'Points (21)' : 'Tennis Rules' }}</span>
                    <span>•</span>
                    <span :class="{'text-yellow-600': match.status === 'pending', 'text-sky-600': match.status === 'active'}" class="capitalize">{{ match.status }}</span>
                </div>
            </div>
            
            <div class="hidden sm:block">
                 <button v-if="match.status === 'pending'" @click="startMatch" class="bg-sky-600 hover:bg-sky-700 text-gray-800 font-bold py-3 px-8 rounded-xl shadow-lg transform transition hover:-translate-y-1 hover:shadow-xl">
                    Start Match
                </button>
                <button v-else @click="goToMatch" class="bg-sky-600 hover:bg-sky-700 text-gray-800 font-bold py-3 px-8 rounded-xl shadow-lg transition">
                    Go to Match
                </button>
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-sky-800">Players</h3>
                <span class="bg-sky-100 text-sky-600 px-3 py-1 rounded-full text-sm font-bold">{{ match.players.length }} registered</span>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <div v-for="player in match.players" :key="player.id" class="group bg-sky-50 hover:bg-white border border-sky-100 hover:border-sky-300 p-4 rounded-2xl transition duration-200 flex items-center space-x-3">
                <div class="w-10 h-10 bg-sky-200 text-sky-700 rounded-full flex items-center justify-center text-sm font-bold group-hover:bg-sky-600 group-hover:text-gray-800 transition">
                {{ player.name.charAt(0).toUpperCase() }}
                </div>
                <span class="font-semibold text-sky-700 group-hover:text-sky-900">{{ player.name }}</span>
            </div>
            </div>
        </div>

        <div class="mt-8 sm:hidden">
             <button v-if="match.status === 'pending'" @click="startMatch" class="w-full bg-sky-600 hover:bg-sky-700 text-gray-800 font-bold py-4 rounded-xl shadow-lg">
                Start Match
            </button>
            <button v-else @click="goToMatch" class="w-full bg-sky-600 hover:bg-sky-700 text-gray-800 font-bold py-4 rounded-xl shadow-lg">
                Go to Match
            </button>
        </div>
      </div>
    </div>
    <div v-else class="text-center py-24">
      <div v-if="error" class="text-red-500 font-bold mb-4">{{ error }}</div>
      <div v-else>
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-sky-600 mx-auto mb-4"></div>
        <p class="text-sky-500 font-medium">Loading match details...</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const router = useRouter();
const match = ref(null);
const error = ref(null);

const fetchMatch = async () => {
  try {
    const response = await axios.get(`/api/matches/${route.params.id}`);
    match.value = response.data;
  } catch (err) {
    console.error('Error fetching match:', err);
    error.value = 'Failed to load match details. Please try again.';
  }
};

const startMatch = async () => {
  try {
    await axios.post(`/api/matches/${match.value.id}/start`);
    match.value.status = 'active'; // Update local state
    goToMatch();
  } catch (err) {
    console.error('Error starting match:', err);
    alert('Failed to start match');
  }
};

const goToMatch = () => {
  router.push({ name: 'MatchView', params: { id: match.value.id } });
};

onMounted(() => {
  fetchMatch();
});
</script>
