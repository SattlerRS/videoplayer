<template>
  <div class="min-h-screen bg-black flex flex-col justify-start">
    <div class="relative p-12 w-full sm:max-w-3xl sm:mx-auto">
      <div class="overflow-hidden z-0 rounded-full relative p-3">
        <form role="form" class="relative flex z-50 bg-orange-500 rounded-full" @submit.prevent="submitSearch">
          <input v-model="searchText" type="text" placeholder="enter your search here"
            class="rounded-full flex-1 px-6 py-4 text-black focus:outline-none">
          <button type="submit"
            class="bg-black text-white rounded-full font-semibold px-8 py-4 hover:bg-gray-900 focus:bg-gray-700 focus:outline-none">Search</button>
        </form>
        <div class="glow glow-1 z-10 bg-orange-500 absolute"></div>
        <div class="glow glow-2 z-20 bg-orange-400 absolute"></div>
        <div class="glow glow-3 z-30 bg-orange-300 absolute"></div>
        <div class="glow glow-4 z-40 bg-orange-200 absolute"></div>
      </div>
    </div>
    <div class=" d-flex">
      <div class="w-75">

        <!-- Videoplayer Favourites -->
        <div v-if="showVideoPlayer" class="flex justify-center items-center">
          <div class="m-5 flex flex-col justify-center items-center">
            <iframe :src="getVideoUrlFav(favVideoId)" frameborder="0" allowfullscreen
              style="width: 800px; height: 600px;"></iframe>
            <button type="button" @click="hidePlayer()"
              class="text-black mt-1 bg-gradient-to-br from-pink-500 to-orange-400 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-pink-200 dark:focus:ring-pink-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center">X</button>
          </div>
        </div>
        <!-- Videoplayer Favourites End -->

        <!-- Search-->
        <div class="mt-2 mr-5 ml-5 flex flex-wrap" id="searches">
          <div v-for="video in searchResults" :key="video.id" class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5">
            <div class="flex justify-center m-2">
              <div class="card card-compact w-96 bg-orange-500 shadow-xl mx-2" style="width: 300px; height: 275px;">
                <div class="p-2 flex justify-center">
                  <div class="video-container">
                    <iframe :src="getVideoUrl(video.id)" frameborder="0" allowfullscreen></iframe>
                  </div>
                </div>
                <div class="card-body">
                  <h2 class="card-title text-black font-black text-center">{{ video.snippet.title }}</h2>
                  <h3>{{ video.duration }}</h3>
                  <div class="card-actions justify-end absolute bottom-0 right-0 mb-2 mr-2">
                    <button type="button" @click="addToFavorites(video)"
                      class="text-black bg-gradient-to-br from-pink-500 to-orange-400 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-pink-200 dark:focus:ring-pink-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Fav</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Search End -->

      <!-- Favorites -->
      <div id="favorites" class="border-2 border-white rounded-lg m-2">>
        <h2 class="text-white text-lg font-bold mx-4">Ihre Favoriten:</h2>
        <div v-for="fav in favVideos" :key="fav.id" class="flex justify-center mb-4">
          <div class="card card-compact w-96 bg-orange-500 shadow-xl mx-4" style="height: 100px;">
            <div class="flex items-center">
              <div class="thumbnail-container w-100">
                <img :src="fav.thumbnail" alt="Video Thumbnail" class="thumbnail-image">
              </div>
              <div class="card-body flex-grow">
                <h2 class="card-title text-black font-black">{{ fav.titel }}</h2>
              </div>
              <div class="card-actions flex flex-col items-center">
                <button type="button" @click="playVideo(fav)"
                  class="text-black m-1 bg-gradient-to-br from-pink-500 to-orange-400 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-pink-200 dark:focus:ring-pink-800 font-medium rounded-lg text-sm px-3 py-2.5 text-center">Play</button>
                <button type="button" @click="delFromFavorites(fav)"
                  class="text-black m-1 bg-gradient-to-br from-pink-500 to-orange-400 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-pink-200 dark:focus:ring-pink-800 font-medium rounded-lg text-sm px-3 py-2.5 text-center">Del</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Favorites End-->
    </div>
  </div>
</template>
<script>
import axios from 'axios';

export default {
  data() {
    return {
      favVideoId: '',
      searchText: '',
      favVideoId: '',
      searchResults: [],
      favVideos: [],
      showVideoPlayer: false,
    };
  },
  mounted() {
    this.fetchFavVideos();
  },
  methods: {

    hidePlayer() {
      this.showVideoPlayer = false;
    },

    playVideo(fav) {
      this.favVideoId = fav.video_id;
      this.showVideoPlayer = true;
    },
    delFromFavorites(fav) {
      axios
        .post('/api/delFavVideo', { favVideoId: fav.video_id })
        .then(response => {
          // Erfolgsfall: Das Favoritenvideo wurde erfolgreich gelöscht
          console.log(response.data.message);
          this.fetchFavVideos();
          // Hier kannst du weitere Aktualisierungen vornehmen, z. B. die favVideos-Liste aktualisieren
        })
        .catch(error => {
          // Fehlerfall: Es ist ein Fehler aufgetreten
          console.error(error);
        });
    },
    fetchFavVideos() {
      axios
        .get('/api/getFavVideos')
        .then(response => {
          this.favVideos = response.data;
          console.log(this.favVideos); // Ausgabe der Backend-Antwort auf der Konsole
          this.favVideos.forEach(video => {
            if (video.titel.length > 45) {
              video.titel = video.titel.substring(0, 45) + "...";
            }
          });
        })
        .catch(error => {
          console.error(error);
        });
    },
    submitSearch() {
      if (this.searchText !== '') {
        axios
          .post('/home', { search: this.searchText })
          .then(response => {
            // Successful response received from the backend
            this.searchResults = response.data.items;
            console.log(this.searchResults); // Store the search results in the data property
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
    getVideoUrlFav(videoId) {

      return 'https://www.youtube.com/embed/' + videoId;
    },
    addToFavorites(video) {
      const videoData = {
        ID: video.id.videoId,
        Title: video.snippet.title,
        Thumbnail: video.snippet.thumbnails.default.url
      };
      axios
        .post('/api/index', videoData)
        .then(response => {
          console.log(response.data.message);
          this.fetchFavVideos();// Ausgabe des Backend-Responses
        })
        .catch(error => {
          console.error(error);
        });
        console.log(video.duration);
    }

  }
};
</script>
  
<style scoped>

</style>