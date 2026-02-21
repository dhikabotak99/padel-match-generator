<template>
  <div class="min-h-screen bg-sage-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
      <div class="mb-8 text-center">
        <h2 class="text-4xl font-extrabold text-sage-800">Setup Match</h2>
        <p class="mt-2 text-lg text-sage-600">Configure your tournament settings</p>
      </div>
    
      <div class="bg-white shadow-xl rounded-2xl md:rounded-3xl overflow-hidden border border-sage-100">
        <div class="p-6 md:p-8 space-y-6">
          
          <!-- Name -->
          <div>
            <label class="block text-sm font-medium text-sage-700 mb-1">Match Name</label>
            <input v-model="form.name" type="text" class="w-full border-sage-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-sage-500 focus:border-sage-500 transition shadow-sm outline-none" placeholder="e.g. Friday Night Padel">
          </div>

          <!-- Type Selection -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
             <div>
                <label class="block text-sm font-medium text-sage-700 mb-1">Format</label>
                <div class="relative">
                    <select v-model="form.type" class="w-full appearance-none border-sage-200 rounded-xl px-4 py-3 bg-sage-50 focus:ring-2 focus:ring-sage-500 focus:border-sage-500 outline-none cursor-pointer">
                    <option value="americano">Americano</option>
                    <option value="mexicano">Mexicano</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-sage-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>
             </div>
             <div>
                <label class="block text-sm font-medium text-sage-700 mb-1">Scoring</label>
                <div class="relative">
                    <select v-model="form.scoring_type" class="w-full appearance-none border-sage-200 rounded-xl px-4 py-3 bg-sage-50 focus:ring-2 focus:ring-sage-500 focus:border-sage-500 outline-none cursor-pointer">
                    <option value="21">Points (21)</option>
                    <option value="tennis">Tennis Rules</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-sage-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>
             </div>
          </div>

          <!-- Category -->
          <div>
            <label class="block text-sm font-medium text-sage-700 mb-1">Category</label>
             <div class="relative">
                <select v-model="form.gender_type" class="w-full appearance-none border-sage-200 rounded-xl px-4 py-3 bg-sage-50 focus:ring-2 focus:ring-sage-500 focus:border-sage-500 outline-none cursor-pointer">
                <option value="open">Open (Any Gender)</option>
                <option value="mixed">Mixed Doubles (Man + Woman)</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-sage-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </div>
            </div>
          </div>
          
          <!-- Courts -->
          <div>
            <label class="block text-sm font-medium text-sage-700 mb-1">Number of Courts</label>
            <input v-model.number="form.courts_count" type="number" min="1" class="w-full border-sage-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-sage-500 focus:border-sage-500 transition shadow-sm outline-none" placeholder="1">
          </div>

          <!-- Players -->
          <div>
            <label class="block text-sm font-medium text-sage-700 mb-1">Players (One per line)</label>
            <div class="relative">
                <textarea v-model="playersInput" class="w-full border-sage-200 rounded-xl px-4 py-3 h-48 focus:ring-2 focus:ring-sage-500 focus:border-sage-500 transition shadow-sm outline-none resize-none bg-sage-50" placeholder="John Doe&#10;Jane Doe&#10;..."></textarea>
                <div class="absolute bottom-3 right-3 bg-white px-2 py-1 rounded-md text-xs font-bold text-sage-600 shadow-sm border border-sage-100">
                    {{ parsedPlayers.length }} Players
                </div>
            </div>
          </div>

          <!-- Gender Assignment (Mixed Only) -->
          <div v-if="form.gender_type === 'mixed' && parsedPlayers.length > 0" class="bg-sage-50 p-4 rounded-xl border border-sage-200">
             <h4 class="font-bold text-sage-800 mb-2">Assign Genders</h4>
             <p class="text-sm text-sage-600 mb-4">Please specify gender for each player. (Must have equal number of Men and Women)</p>
             
             <div class="space-y-2 max-h-60 overflow-y-auto pr-2">
                <div v-for="player in parsedPlayers" :key="player" class="flex items-center justify-between bg-white p-2 rounded-lg border border-sage-100">
                    <span class="font-medium text-sage-900 truncate mr-2">{{ player }}</span>
                    <div class="flex space-x-1">
                        <button 
                            @click="setGender(player, 'male')"
                            :class="{'bg-blue-100 text-blue-700 border-blue-300': getGender(player) === 'male', 'bg-gray-50 text-gray-400 border-transparent hover:bg-gray-100': getGender(player) !== 'male'}"
                            class="px-3 py-1 rounded-md text-sm font-bold border transition"
                        >
                            Man
                        </button>
                        <button 
                            @click="setGender(player, 'female')"
                            :class="{'bg-pink-100 text-pink-700 border-pink-300': getGender(player) === 'female', 'bg-gray-50 text-gray-400 border-transparent hover:bg-gray-100': getGender(player) !== 'female'}"
                            class="px-3 py-1 rounded-md text-sm font-bold border transition"
                        >
                            Woman
                        </button>
                    </div>
                </div>
             </div>

             <div class="mt-3 flex justify-between text-xs font-bold uppercase tracking-wide">
                 <span :class="{'text-blue-600': maleCount === femaleCount, 'text-red-500': maleCount !== femaleCount}">Men: {{ maleCount }}</span>
                 <span :class="{'text-pink-600': maleCount === femaleCount, 'text-red-500': maleCount !== femaleCount}">Women: {{ femaleCount }}</span>
             </div>
          </div>

          <!-- Action -->
          <button @click="createMatch" :disabled="parsedPlayers.length < 4" class="w-full bg-sage-600 hover:bg-sage-700 disabled:bg-sage-300 text-black font-bold py-4 px-6 rounded-xl shadow-lg transition transform hover:-translate-y-0.5 disabled:transform-none disabled:cursor-not-allowed">
            Create Match
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();

const form = ref({
  name: '',
  type: 'americano',
  gender_type: 'open',
  scoring_type: '21',
  courts_count: 1
});

const playersInput = ref('');
const playerGenders = ref({});

const setGender = (name, gender) => {
    playerGenders.value[name] = gender;
};

const getGender = (name) => {
    return playerGenders.value[name] || 'male'; 
};

const parsedPlayers = computed(() => {
  return playersInput.value.split('\n').map(p => p.trim()).filter(p => p.length > 0);
});

const maleCount = computed(() => parsedPlayers.value.filter(p => getGender(p) === 'male').length);
const femaleCount = computed(() => parsedPlayers.value.filter(p => getGender(p) === 'female').length);

const createMatch = async () => {
  if (parsedPlayers.value.length < 4) {
    alert('Need at least 4 players');
    return;
  }

  if (form.value.gender_type === 'mixed') {
      if (maleCount.value !== femaleCount.value) {
          alert(`Mixed doubles requires equal number of Men and Women.\nCurrent: ${maleCount.value} Men, ${femaleCount.value} Women`);
          return;
      }
  }

  try {
     const finalPlayers = parsedPlayers.value.map(name => {
        if (form.value.gender_type === 'mixed') {
            return { name, gender: getGender(name) };
        }
        return name;
    });

    const response = await axios.post('/api/matches', {
      ...form.value,
      players: finalPlayers
    });
    
    router.push({ name: 'MatchLobby', params: { id: response.data.id } });
  } catch (error) {
    console.error(error);
    alert('Error creating match');
  }
};
</script>
