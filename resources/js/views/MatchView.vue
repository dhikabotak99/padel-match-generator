<template>
  <div class="min-h-screen bg-sky-50 py-8 px-4">
    <div v-if="match" class="max-w-5xl mx-auto flex flex-col min-h-[80vh] bg-white rounded-3xl shadow-xl overflow-hidden border border-sky-100">
      <!-- Header -->
      <div class="p-6 md:p-8 bg-white border-b border-sky-100 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 relative overflow-hidden">
        <div class="relative z-10 w-full sm:w-auto text-center sm:text-left">
            <div class="flex items-center justify-center sm:justify-start space-x-3 mb-1">
                 <h1 class="text-2xl md:text-3xl font-extrabold text-sky-900 tracking-tight">{{ match.name }}</h1>
                 <span class="bg-sky-100 text-sky-700 text-xs px-2 py-1 rounded-md font-bold uppercase">{{ match.type }}</span>
            </div>
           <p class="text-sky-500 font-medium">Round <span class="text-sky-700 font-bold">{{ currentRoundNumber }}</span> in progress</p>
        </div>
        <div class="relative z-10 w-full sm:w-auto">
            <button @click="nextRound" :disabled="isLoading" class="w-full sm:w-auto bg-sky-600 hover:bg-sky-700 text-gray-800 font-bold py-3 px-8 rounded-xl shadow-lg transition transform hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed">
            {{ isLoading ? 'Generating...' : 'Next Round' }}
            </button>
        </div>
        
        <!-- Decorative bg -->
        <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-sky-50 to-transparent pointer-events-none"></div>
      </div>

      <!-- Tabs -->
      <div class="flex border-b border-sky-100 bg-sky-50/50">
        <button 
          @click="activeTab = 'games'"
          :class="{'border-b-2 border-sky-600 text-sky-800 bg-white': activeTab === 'games', 'text-sky-500 hover:text-sky-700 hover:bg-sky-50': activeTab !== 'games'}"
          class="flex-1 py-3 px-4 md:py-4 md:px-6 text-center font-bold text-base md:text-lg focus:outline-none transition-all duration-300"
        >
          Games
        </button>
        <button 
          @click="activeTab = 'leaderboard'"
          :class="{'border-b-2 border-sky-600 text-sky-800 bg-white': activeTab === 'leaderboard', 'text-sky-500 hover:text-sky-700 hover:bg-sky-50': activeTab !== 'leaderboard'}"
          class="flex-1 py-3 px-4 md:py-4 md:px-6 text-center font-bold text-base md:text-lg focus:outline-none transition-all duration-300"
        >
          Leaderboard
        </button>
        <button 
          @click="activeTab = 'history'"
          :class="{'border-b-2 border-sky-600 text-sky-800 bg-white': activeTab === 'history', 'text-sky-500 hover:text-sky-700 hover:bg-sky-50': activeTab !== 'history'}"
          class="flex-1 py-3 px-4 md:py-4 md:px-6 text-center font-bold text-base md:text-lg focus:outline-none transition-all duration-300"
        >
          History
        </button>
      </div>

      <!-- Content -->
      <div class="p-4 md:p-8 flex-grow bg-white">
        <!-- Games Tab -->
        <transition name="fade" mode="out-in">
          <div v-if="activeTab === 'games'" key="games">
            <div v-if="currentRound" class="space-y-6">
              <div class="flex items-center justify-between mb-2">
                  <h3 class="text-xl font-bold text-sky-800">Matchups</h3>
                  <span class="text-sm text-sky-500 italic">Enter scores to auto-save</span>
              </div>
              
              <div v-for="(games, courtId) in gamesByCourt" :key="courtId" class="mb-8">
                <h4 class="text-lg font-bold text-sky-700 mb-3 flex items-center">
                    <span class="bg-sky-200 text-sky-800 py-1 px-3 rounded-lg mr-2">
                        {{ getCourtName(courtId) }}
                    </span>
                    <span class="text-sm font-normal text-sky-500">{{ games.length }} Game(s)</span>
                </h4>
                <div class="grid gap-4 md:gap-6"> 
                    <div v-for="game in games" :key="game.id" class="bg-sky-50 rounded-2xl p-4 md:p-6 border border-sky-100 hover:border-sky-200 transition shadow-sm hover:shadow-md">
                        <div class="flex flex-col md:flex-row items-center justify-between gap-4 md:gap-6">
                            <!-- Team A -->
                            <div class="flex-1 flex flex-col items-center md:items-start text-center md:text-left space-y-1">
                                <div class="font-bold text-sky-900 text-base md:text-lg">{{ game.team_a_player1.name }}</div>
                                <div class="font-bold text-sky-900 text-base md:text-lg">{{ game.team_a_player2.name }}</div>
                            </div>

                            <!-- Score Input -->
                            <div class="flex items-center space-x-4 bg-white px-4 py-2 md:px-6 md:py-3 rounded-xl shadow-inner border border-sky-100">
                                <input 
                                v-model.number="game.score_team_a" 
                                @input="updateScore(game, 'a')"
                                type="number" 
                                class="w-12 md:w-16 text-center border-none bg-transparent text-2xl md:text-3xl font-black text-sky-800 focus:ring-0 p-0"
                                placeholder="0"
                                >
                                <span class="text-sky-300 font-light text-xl md:text-2xl">/</span>
                                <input 
                                v-model.number="game.score_team_b" 
                                @input="updateScore(game, 'b')"
                                type="number" 
                                class="w-12 md:w-16 text-center border-none bg-transparent text-2xl md:text-3xl font-black text-sky-800 focus:ring-0 p-0"
                                placeholder="0"
                                >
                            </div>

                            <!-- Team B -->
                            <div class="flex-1 flex flex-col items-center md:items-end text-center md:text-right space-y-1">
                                <div class="font-bold text-sky-900 text-base md:text-lg">{{ game.team_b_player1.name }}</div>
                                <div class="font-bold text-sky-900 text-base md:text-lg">{{ game.team_b_player2.name }}</div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-20 text-sky-400">
               <p class="text-xl">No active round.</p>
               <button @click="nextRound" class="mt-4 text-sky-600 underline hover:text-sky-800">Start Round 1</button>
            </div>
          </div>
          <div v-else-if="activeTab === 'leaderboard'" key="leaderboard">
            <div class="overflow-x-auto bg-white rounded-2xl border border-sky-100 shadow-sm">
              <table class="min-w-full">
                <thead>
                  <tr class="bg-sky-50 text-sky-600 uppercase text-xs leading-normal">
                    <th class="py-4 px-4 md:px-6 text-left font-bold tracking-wider rounded-tl-2xl">Rank</th>
                    <th class="py-4 px-4 md:px-6 text-left font-bold tracking-wider">Player</th>
                    <th class="py-4 px-4 md:px-6 text-right font-bold tracking-wider">Points</th>
                    <th class="py-4 px-4 md:px-6 text-right font-bold tracking-wider rounded-tr-2xl">Games</th>
                  </tr>
                </thead>
                <tbody class="text-sky-700 text-sm">
                  <tr v-for="(player, index) in leaderboard" :key="player.id" class="border-b border-sky-50 hover:bg-sky-50/50 transition duration-150">
                    <td class="py-4 px-4 md:px-6 font-bold whitespace-nowrap">
                       <span class="inline-flex items-center justify-center w-8 h-8 rounded-full" :class="{'bg-yellow-100 text-yellow-700': index === 0, 'bg-gray-100 text-gray-700': index > 0}">{{ index + 1 }}</span>
                    </td>
                    <td class="py-4 px-4 md:px-6 font-semibold whitespace-nowrap">
                      {{ player.name }}
                    </td>
                    <td class="py-4 px-4 md:px-6 text-right font-black text-lg text-sky-800">
                      {{ player.points }}
                    </td>
                     <td class="py-4 px-4 md:px-6 text-right">
                      <span class="bg-sky-100 text-sky-600 py-1 px-3 rounded-full text-xs font-bold">{{ player.gamesPlayed }}</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div v-else-if="activeTab === 'history'" key="history">
            <div v-if="pastRounds.length > 0" class="space-y-8">
              <div v-for="round in pastRounds" :key="round.id" class="bg-white rounded-2xl border border-sky-100 shadow-sm overflow-hidden">
                <div class="bg-sky-50 px-6 py-4 border-b border-sky-100 flex justify-between items-center">
                  <h3 class="text-lg font-bold text-sky-800">Round {{ round.round_number }}</h3>
                  <span class="text-sm text-sky-500">{{ round.games.length }} Games</span>
                </div>
                <div class="p-6 grid gap-4 md:grid-cols-2">
                   <div v-for="game in round.games" :key="game.id" class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex justify-between items-center">
                      <!-- Team A -->
                      <div class="flex-1 text-center md:text-left">
                          <div class="font-bold text-gray-700 text-sm">{{ game.team_a_player1.name }}</div>
                          <div class="font-bold text-gray-700 text-sm">{{ game.team_a_player2.name }}</div>
                      </div>

                      <!-- Score -->
                      <div class="px-4 flex items-center space-x-2">
                          <span class="text-2xl font-black text-sky-700">{{ game.score_team_a || 0 }}</span>
                          <span class="text-gray-300 text-xl">/</span>
                          <span class="text-2xl font-black text-sky-700">{{ game.score_team_b || 0 }}</span>
                      </div>

                      <!-- Team B -->
                      <div class="flex-1 text-center md:text-right">
                          <div class="font-bold text-gray-700 text-sm">{{ game.team_b_player1.name }}</div>
                          <div class="font-bold text-gray-700 text-sm">{{ game.team_b_player2.name }}</div>
                      </div>
                   </div>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-20 text-sky-400">
               <p class="text-xl">No past rounds yet.</p>
               <p class="text-sm mt-2">Finish a round to see history here.</p>
            </div>
          </div>
        </transition>
      </div>
    </div>
    <div v-else class="flex flex-col justify-center items-center min-h-[80vh]">
      <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-sky-600 mb-6"></div>
       <p class="text-sky-500 font-medium text-lg">Loading match...</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const match = ref(null);
const activeTab = ref('games');
const isLoading = ref(false);

const gamesByCourt = computed(() => {
  if (!currentRound.value || !currentRound.value.games) return {};
  
  const groups = {};
  // Sort games by ID first to ensure stability
  const sortedGames = [...currentRound.value.games].sort((a, b) => a.id - b.id);
  
  sortedGames.forEach(game => {
      const courtId = game.court_id || 'uncourted';
      if (!groups[courtId]) {
          groups[courtId] = [];
      }
      groups[courtId].push(game);
  });
  
  return groups;
});

const getCourtName = (courtId) => {
    if (courtId === 'uncourted') return 'No Court Assigned';
    if (!match.value.courts) return `Court ${courtId}`;
    const court = match.value.courts.find(c => c.id == courtId);
    return court ? court.name : `Court ${courtId}`;
};

const currentRound = computed(() => {
  if (!match.value || !match.value.rounds || match.value.rounds.length === 0) return null;
  return match.value.rounds[match.value.rounds.length - 1]; // Last round
});

const currentRoundNumber = computed(() => currentRound.value ? currentRound.value.round_number : 0);

const pastRounds = computed(() => {
  if (!match.value || !match.value.rounds) return [];
  // Return all rounds except the last one (current), reversed to show newest first
  return match.value.rounds.slice(0, -1).reverse();
});

const leaderboard = computed(() => {
  if (!match.value) return [];
  
  // Calculate points from all games
  const scores = {};
  
  // Initialize
  match.value.players.forEach(p => {
    scores[p.id] = { id: p.id, name: p.name, points: 0, gamesPlayed: 0 };
  });
  
  if (match.value.rounds) {
    match.value.rounds.forEach(round => {
      if (round.games) {
        round.games.forEach(game => {
           // Only count if scores > 0? Or always?
           // Assuming played if score updated?
           // For MVP, if score > 0
           
           if (game.score_team_a > 0 || game.score_team_b > 0) {
               // Team A
               scores[game.team_a_player_1_id].points += game.score_team_a;
               scores[game.team_a_player_1_id].gamesPlayed++;
               scores[game.team_a_player_2_id].points += game.score_team_a;
               scores[game.team_a_player_2_id].gamesPlayed++;
               
               // Team B
               scores[game.team_b_player_1_id].points += game.score_team_b;
               scores[game.team_b_player_1_id].gamesPlayed++;
               scores[game.team_b_player_2_id].points += game.score_team_b;
               scores[game.team_b_player_2_id].gamesPlayed++;
           }
        });
      }
    });
  }
  
  return Object.values(scores).sort((a, b) => b.points - a.points);
});


const fetchMatch = async () => {
  try {
    const response = await axios.get(`/api/matches/${route.params.id}`);
    match.value = response.data;
  } catch (error) {
    console.error('Error fetching match:', error);
  }
};

const autoCalculateScore = (game, changedTeam) => {
    if (match.value.scoring_type !== '21') return;

    if (changedTeam === 'a') {
        if (game.score_team_a > 21) game.score_team_a = 21;
        game.score_team_b = 21 - (game.score_team_a || 0);
    } else {
        if (game.score_team_b > 21) game.score_team_b = 21;
        game.score_team_a = 21 - (game.score_team_b || 0);
    }
};

const updateScore = async (game, changedTeam = null) => {
  if (changedTeam) {
      autoCalculateScore(game, changedTeam);
  }

  try {
     await axios.post(`/api/games/${game.id}/score`, {
         score_team_a: game.score_team_a,
         score_team_b: game.score_team_b
     });
     // Optionally refresh match to ensure sync?
     // Or just trust local state for UI speed
  } catch (error) {
      console.error('Error updating score:', error);
  }
};

const nextRound = async () => {
    if (isLoading.value) return;
    isLoading.value = true;
    try {
        await axios.post(`/api/matches/${match.value.id}/rounds`);
        await fetchMatch();
    } catch (error) {
        console.error('Error generating round:', error);
        alert('Could not generate next round');
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
  fetchMatch();
});
</script>
