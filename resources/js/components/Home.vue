<template>
    <div class="min-h-screen bg-black flex flex-col justify-start">
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
      <div class="mt-8 flex flex-wrap">
        <div v-for="video in searchResults" :key="video.id" class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 mb-4">
          <div class="flex justify-center">
            <div class="card card-compact w-96 bg-orange-500 shadow-xl mx-2" style="width: 300px; height: 275px;">
              <div class="p-2 flex justify-center">
                <div class="video-container">
                  <iframe :src="getVideoUrl(video.id)" frameborder="0" allowfullscreen></iframe>
                </div>
              </div>
              <div class="card-body">
                <h2 class="card-title text-black font-black text-center">{{ video.snippet.title }}</h2>
                <div class="card-actions justify-end absolute bottom-0 right-0 mb-2 mr-2">
                  <button type="button" @click="addToFavorites(video)" class="text-black bg-gradient-to-br from-pink-500 to-orange-400 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-pink-200 dark:focus:ring-pink-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Fav</button>
                </div>
              </div>
            </div>
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
      },
      addToFavorites(video) {
        const videoData = {
            ID: video.id.videoId,
            Title: video.snippet.title,
        };
        console.log(videoData.Title)
        console.log(videoData.ID)
        axios
        .post('/api/index', videoData)
        .then(response => {
          console.log(response.data.message); // Ausgabe des Backend-Responses
          })
        .catch(error => {
      console.error(error);
    });
        
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
  
  .video-container {
    position: relative;
    width: 100%;
    height: 0;
    padding-top: 56.25%; /* 16:9 Aspect Ratio (Höhe/Breite * 100) */
  }
  
  .video-container iframe {
    position: absolute;
    top: 1.3%;
    left: 1.3%;
    right: 1.3%;
    width: 97.4%;
    height: 94%;
  }
  </style>