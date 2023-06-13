<template>
  <div class="min-h-screen bg-white flex flex-col justify-start">
    <div class="relative p-12 w-full sm:max-w-3xl sm:mx-auto">
      <div class="overflow-hidden z-0 rounded-full relative p-3">
        <form role="form" class="relative flex z-50 bg-orange-500 rounded-full" @submit.prevent="submitSearch">
          <input v-model="searchText" type="text" placeholder="enter your search here" class="rounded-full flex-1 px-6 py-4 text-black focus:outline-none">
          <button type="submit" class="bg-black text-white rounded-full font-semibold px-8 py-4 hover:bg-gray-900 focus:bg-gray-700 focus:outline-none">Search</button>
        </form>
        <div class="glow glow-1 z-10 bg-orange-500 absolute"></div>
        <div class="glow glow-2 z-20 bg-orange-400 absolute"></div>
        <div class="glow glow-3 z-30 bg-orange-300 absolute"></div>
        <div class="glow glow-4 z-40 bg-orange-200 absolute"></div>
      </div>
    </div>
    <div class="mt-8">
      <div v-for="video in searchResults" :key="video.id" class="mb-4">
        <h2 class="text-xl font-semibold"></h2>
        <div class="flex">
          <iframe :src="getVideoUrl(video.id)" width="560" height="315" frameborder="0" allowfullscreen></iframe>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      searchText: '',
      searchResults: [] // Array to store the search results
    };
  },
  methods: {
    submitSearch() {
      if (this.searchText !== '') {
        axios
          .post('/home', { search: this.searchText })
          .then(response => {
            // Successful response received from the backend
            this.searchResults = response.data.items; // Store the search results in the data property
          })
          .catch(error => {
            // Error sending the request
            console.error(error);
          });
      }
    },
    getVideoUrl(videoId) {
     
      return 'https://www.youtube.com/embed/' + videoId.videoId;
    }
  }
};
</script>

<style scoped>
.glow {
  top: -10%;
  left: -10%;
  width: 120%;
  height: 120%;
  border-radius: 100%;
}

.glow-1 {
  animation: glow1 4s linear infinite;
}

.glow-2 {
  animation: glow2 4s linear infinite;
  animation-delay: 100ms;
}

.glow-3 {
  animation: glow3 4s linear infinite;
  animation-delay: 200ms;
}

.glow-4 {
  animation: glow4 4s linear infinite;
  animation-delay: 300ms;
}

@keyframes glow1 {
  0% {
    transform: translate(10%, 10%) scale(1);
  }
  25% {
    transform: translate(-10%, 10%) scale(1);
  }
  50% {
    transform: translate(-10%, -10%) scale(1);
  }
  75% {
    transform: translate(10%, -10%) scale(1);
  }
  100% {
    transform: translate(10%, 10%) scale(1);
  }
}

@keyframes glow2 {
  0% {
    transform: translate(-10%, -10%) scale(1);
  }
  25% {
    transform: translate(10%, -10%) scale(1);
  }
  50% {
    transform: translate(10%, 10%) scale(1);
  }
  75% {
    transform: translate(-10%, 10%) scale(1);
  }
  100% {
    transform: translate(-10%, -10%) scale(1);
  }
}

@keyframes glow3 {
  0% {
    transform: translate(-10%, 10%) scale(1);
  }
  25% {
    transform: translate(-10%, -10%) scale(1);
  }
  50% {
    transform: translate(10%, -10%) scale(1);
  }
  75% {
    transform: translate(10%, 10%) scale(1);
  }
  100% {
    transform: translate(-10%, 10%) scale(1);
  }
}

@keyframes glow4 {
  0% {
    transform: translate(10%, -10%) scale(1);
  }
  25% {
    transform: translate(10%, 10%) scale(1);
  }
  50% {
    transform: translate(-10%, 10%) scale(1);
  }
  75% {
    transform: translate(-10%, -10%) scale(1);
  }
  100% {
    transform: translate(10%, -10%) scale(1);
  }
}
</style>