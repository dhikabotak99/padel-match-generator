<template>
  <div class="flex flex-col items-center justify-center min-h-screen bg-fuchsia-50 text-fuchsia-900 font-sans">
    <div class="text-center p-6 md:p-10 max-w-4xl">
        <h1 class="text-4xl md:text-6xl font-extrabold mb-4 md:mb-6 text-fuchsia-800 tracking-tight">
            Padel<span class="text-fuchsia-500">In</span>
        </h1>
        <p class="text-lg md:text-2xl mb-8 md:mb-12 text-fuchsia-600 font-light">
            Effortlessly organize your Americano and Mexicano tournaments.
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <router-link to="/create" class="w-full sm:w-auto bg-fuchsia-600 hover:bg-fuchsia-700 text-gray-800 font-bold py-3 px-8 md:py-4 md:px-10 rounded-full shadow-xl transition transform hover:scale-105 hover:shadow-2xl flex items-center justify-center text-sm md:text-base">
                <span>Create New Match</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </router-link>
            
            <router-link to="/matches" class="w-full sm:w-auto bg-white border-2 border-fuchsia-200 hover:border-fuchsia-300 text-fuchsia-800 font-bold py-3 px-8 md:py-4 md:px-10 rounded-full shadow-xl transition transform hover:scale-105 hover:shadow-2xl flex items-center justify-center text-sm md:text-base">
                <span>View Matches</span>
            </router-link>
        </div>
    </div>
    
    <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8 text-center max-w-5xl px-4">
        <div class="p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition">
            <h3 class="text-xl font-bold text-fuchsia-700 mb-2">Americano</h3>
            <p class="text-fuchsia-500">Play with everyone, fair and fun mixing.</p>
        </div>
        <div class="p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition">
            <h3 class="text-xl font-bold text-fuchsia-700 mb-2">Mexicano</h3>
            <p class="text-fuchsia-500">Competitive matching based on current score.</p>
        </div>
        <div class="p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition">
            <h3 class="text-xl font-bold text-fuchsia-700 mb-2">Live Leaderboard</h3>
            <p class="text-fuchsia-500">Real-time tracking of points and positions.</p>
        </div>
    </div>
  </div>
</template>

<script setup>
</script>
