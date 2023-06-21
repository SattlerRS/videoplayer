<template>
  <div class="min-h-screen bg-black flex flex-col justify-start pb-5">
    <div class="relative p-12 w-full sm:max-w-3xl sm:mx-auto">
      <div class="overflow-hidden z-0 rounded-full relative p-3">
        <form role="form" class="relative flex z-50 bg-orange-500 rounded-full" @submit.prevent="submitSearch">
          <input v-model="searchText" type="text" placeholder="Nach Videos suchen"
            class="rounded-full flex-1 px-6 py-4 text-black focus:outline-none">
          <button type="submit"
            class="bg-black text-white rounded-full font-semibold px-8 py-4 hover:bg-gray-900 focus:bg-gray-700 focus:outline-none">Suchen</button>
        </form>
        <div class="glow glow-1 z-10 bg-orange-500 absolute"></div>
        <div class="glow glow-2 z-20 bg-orange-400 absolute"></div>
        <div class="glow glow-3 z-30 bg-orange-300 absolute"></div>
        <div class="glow glow-4 z-40 bg-orange-200 absolute"></div>
      </div>
    </div>

    <div v-if="showVideoPlayer" class="flex justify-center items-center">
      <div class="mb-5 flex flex-col justify-center items-center shadow p-4 rounded">
        <iframe :src="getVideoUrlFav(favVideoId)" frameborder="0" allowfullscreen
          style="width: 50vw; height: 50vh;"></iframe>
        <button type="button" @click="hidePlayer()"
          class="text-white mt-4 bg-black border-2 border-orangered rounded-lg text-sm px-3 py-2.5 flex items-center justify-center favButton flex-grow">
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
            <path fill="#FFFFFF"
              d="M7.414 6l4.293-4.293a1 1 0 1 0-1.414-1.414L6 4.586 1.707.293A1 1 0 0 0 .293 1.707L4.586 6 .293 10.293a1 1 0 1 0 1.414 1.414L6 7.414l4.293 4.293a1 1 0 1 0 1.414-1.414L7.414 6z" />
          </svg>
        </button>
      </div>
    </div>
    <div
      class="mt-2 mr-5 ml-5 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4"
      id="searches">
      <div v-for="video in searchResults" :key="video.id" class="w-full">
        <div class="flex justify-center m-2">
          <div class="card card-compact bg-black shadow mx-4"
            style="min-width: 260px; max-width: 260px; min-height: 270px; max-height: 260px; box-shadow: 0px 0px 10px #FF4500;">
            <div class="p-2">
              <div class="video-container" style="position: relative;">
                <img :src="video.snippet.thumbnails.default.url"
                  style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 100%; height: 100%; object-fit: cover;">
              </div>
            </div>
            <div class="card-body">
              <h2 class="card-title text-white font-black text-center">{{ getVideoTitel(video.snippet.title) }}</h2>
              <div class="card-actions justify-end absolute bottom-0 right-0 mb-2 mr-2">
                <div class="flex">
                  <button type="button" @click="playVideo(video.id.videoId)"
                    class="text-white my-2 mx-1 p-1 bg-black border-2 border-orangered rounded-lg text-sm px-2 py-2.5 flex items-center justify-center favButton flex-grow">
                    <svg class="h-4 w-4 fill-current mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z" />
                    </svg>
                    Play
                  </button>
                </div>
              </div>
              <h3 style="position: absolute; bottom: 0; left: 0; margin-left: 24px; margin-bottom: 24px; color: white;">{{
                video.duration }}</h3>
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
      favVideoId: '',
      showVideoPlayer: false,
      searchResults: []
    };
  },
  mounted() {
    this.getRandomVideos();  
  },
  methods: {

    // Random Videos von Youtube anzeigen
    getRandomVideos() {
      axios
        .get('/getRamdomVideos')
        .then(response => {
          // Erfolgreiche Antwort vom Backend erhalten
          this.searchResults = response.data.items;
          // Speichere die Suchergebnisse in der data-Eigenschaft
        })
        .catch(error => {
          // Fehler beim Senden der Anfrage
          console.error(error);
        });
    },

    // Videoplayer verstecken
    hidePlayer() {
      this.showVideoPlayer = false;
    },

    // VideoUrl kombinieren
    getVideoUrlFav(videoId) {
      return 'https://www.youtube.com/embed/' + videoId;
    },

    // Video abspielen, Player einblenden und zum Seitenanfang scrollen
    playVideo(id) {
      this.favVideoId = id;
      this.showVideoPlayer = true;
      window.scrollTo({
        top: 0,
        behavior: 'smooth' // Smooth-Scrolling aktivieren, um einen animierten Übergang zu erzielen
      });
    },

    // Searchstring an das Backend schicken und Videodetails abfragen
    submitSearch() {
      if (this.searchText !== '') {
        axios
          .post('/home', { search: this.searchText })
          .then(response => {
            this.searchResults = response.data.items;
          })
          .catch(error => {
            console.error(error);
          });
      }
    },

    // Videotitel auf eine gewisse Länge trimmen und returnen
    getVideoTitel(videoTitel) {
      if (videoTitel.length > 45) {
        videoTitel = videoTitel.substring(0, 45) + "...";
      }
      return videoTitel;
    },
  }
};
</script>