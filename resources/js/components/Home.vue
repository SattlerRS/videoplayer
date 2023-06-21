<template>
  <div class="min-h-screen bg-black flex flex-col justify-start pb-5">
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
        <!-- Videoplayer Favourites End -->

                <!-- Search -->
       
  <div class="mt-2 mr-5 ml-5 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-4" id="searches">
    <div v-for="video in searchResults" :key="video.id" class="w-full">
      <div class="flex justify-center m-2">
        <div class="card card-compact bg-black shadow mx-4" style="min-width: 260px; max-width: 260px; min-height: 270px; max-height: 260px;">
          <div class="p-2">
            <div class="video-container" style="position: relative;">
              <img :src="video.snippet.thumbnails.default.url" style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 100%; height: 100%; object-fit: cover;">
            </div>
          </div>
          <div class="card-body">
            <h2 class="card-title text-white font-black text-center">{{ getVideoTitel(video.snippet.title) }}</h2>
            <div class="card-actions justify-end absolute bottom-0 right-0 mb-2 mr-2">
              <div class="flex">
                <button type="button" @click="playVideo(video.id.videoId)" class="text-white my-2 mx-1 p-1 bg-black border-2 border-orangered rounded-lg text-sm px-2 py-2.5 flex items-center justify-center favButton flex-grow">
                  <svg class="h-4 w-4 fill-current mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z" />
                  </svg>
                  Play
                </button>
                <button type="button" @click="addToFavorites(video)"
                          :class="{ 'fav-video': isFavorite(video.id.videoId) }"
                          :disabled="isFavorite(video.id.videoId)"
                          :style="{ 'pointer-events': isFavorite(video.id.videoId) ? 'none' : 'auto' }"
                          class="text-white my-2 mx-1 p-1 bg-black border-2 border-orangered rounded-lg text-sm px-2 py-2.5 flex items-center justify-center favButton flex-grow">
                          <svg class="h-4 w-4 fill-current mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path
                              d="M12 21.35L4.81 17.4C2.39 15.9 1 13.36 1 10.65c0-2.91 2.37-5.28 5.28-5.28 1.59 0 3.03 0.71 4.02 1.83C11.69 6.09 11.86 6 12 6c0.14 0 0.31 0.09 0.7 0.2 0.99-1.12 2.43-1.83 4.02-1.83C20.63 5.37 23 7.74 23 10.65c0 2.71-1.39 5.25-3.81 6.75L12 21.35z" />
                          </svg>
                          Fav
                        </button>
                      </div>
                    </div>
                    <h3 style="position: absolute; bottom: 0; left: 0; margin-left: 24px; margin-bottom: 24px; color: white;">{{ video.duration }}</h3>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <!-- Search End -->


      <!-- Favorites -->
      <div v-if="favVideos.length > 0" id="favorites">
        <div class="border-2 border-orangered rounded-lg m-2">
          <h2 class="text-orangered text-lg mx-4 mb-2">Ihre Favoriten:</h2>
          <div v-for="(fav) in paginatedFavorites" :key="fav.id" class="flex justify-center mb-4">
            <div class="card card-compact w-96 bg-black shadow mx-4 h-50 text-white" style="height: 100px;">
              <div class="flex items-center w-100">
                <div class="thumbnail-container w-75 ">
                  <img :src="fav.thumbnail" alt="Video Thumbnail" class="thumbnail-image">
                </div>
                <div class="card-body flex-grow">
                  <h2 class="card-title font-black">{{ fav.titel }}</h2>
                </div>
                <div class="card-actions flex flex-col items-center">
                  <button type="button" @click="playVideo(fav.video_id)"
                    class="text-white m-2 p-1 bg-black border-2 border-orangered rounded-lg text-sm px-3 py-2.5 flex items-center justify-center favButton flex-grow">
                    <svg class="h-4 w-4 fill-current mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z" />
                    </svg>
                    Play
                  </button>
                  <button type="button" @click="delFromFavorites(fav)"
                    class="text-white m-2 p-1 bg-black border-2 border-orangered rounded-lg text-sm px-3 py-2.5 flex items-center justify-center favButton flex-grow">
                    <svg class="h-4 w-4 fill-current mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                      <path d="M6 6 L18 18 M6 18 L18 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    </svg>
                    Del
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="flex justify-center">
            <button @click="previousPage" :disabled="currentPage === 0"
              class="text-white m-2 p-1 bg-black border-2 border-orangered rounded-lg text-sm px-3 py-2.5 favButton">
              Zurück
            </button>
            <button @click="nextPage" :disabled="currentPage === totalPages - 1"
              class="text-white m-2 p-1 bg-black border-2 border-orangered rounded-lg text-sm px-3 py-2.5 favButton">
              Weiter
            </button>
          </div>
          <div class="flex justify-center mt-2 text-white text-sm">
            {{ displayRange }}
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
      searchResults: [],
      favVideos: [],
      showVideoPlayer: false,
      currentPage: 0, // Aktuelle Seite der Pagination
      itemsPerPage: 5, // Anzahl der Videos pro Seite
    };
  },
  mounted() {
    this.fetchFavVideos();
    this.getRandomVideos();
  },
  computed: {
    isFavorite() {
      return videoId => this.favVideos.some(fav => fav.video_id === videoId);
    },
    paginatedFavorites() {
       // Diese Methode gibt eine paginierte Teilmenge der favorisierten Videos zurück
      // Der Startindex der Teilmenge wird berechnet, indem die aktuelle Seite mit der Anzahl der Elemente pro Seite multipliziert wird
      const startIndex = this.currentPage * this.itemsPerPage;
      // Der Endindex der Teilmenge wird berechnet, indem der Startindex zur Anzahl der Elemente pro Seite addiert wird
      const endIndex = startIndex + this.itemsPerPage;
      // Die Teilmenge der favorisierten Videos wird mit Hilfe der slice() Methode erstellt
      return this.favVideos.slice(startIndex, endIndex);
    },

    // Diese Methode gibt die Gesamtzahl der Seiten für die paginierte Darstellung zurück
  // Die Gesamtzahl der Seiten wird berechnet, indem die Länge der favorisierten Videos durch die Anzahl der Elemente pro Seite geteilt wird
  // Math.ceil() wird verwendet, um sicherzustellen, dass eine Rundung nach oben durchgeführt wird
    totalPages() {
      return Math.ceil(this.favVideos.length / this.itemsPerPage);
    },
    displayRange() {
      return this.getDisplayRange();
    },
  },
  methods: {
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
    getDisplayRange() {
      // Der Startindex wird berechnet, indem die aktuelle Seite mit der Anzahl der Elemente pro Seite multipliziert und 1 addiert wird
      const startIndex = this.currentPage * this.itemsPerPage + 1;
      // Der Endindex wird berechnet, indem das Produkt aus der nächsten Seite und der Anzahl der Elemente pro Seite mit der Länge der favorisierten Videos verglichen wird
      // Der kleinere Wert wird als Endindex verwendet, um sicherzustellen, dass der Bereich nicht über die Länge der Videos hinausgeht
      const endIndex = Math.min((this.currentPage + 1) * this.itemsPerPage, this.favVideos.length);
      return `Video (${startIndex}-${endIndex}) von ${this.favVideos.length}`;
    },

     // Diese Methode wechselt zur vorherigen Seite, sofern verfügbar
    previousPage() {
      if (this.currentPage > 0) {
        this.currentPage--;
      }
    },

    // Diese Methode wechselt zur nächsten Seite, sofern verfügbar
    nextPage() {
      if (this.currentPage < this.totalPages - 1) {
        this.currentPage++;
      }
    },
    hidePlayer() {
      this.showVideoPlayer = false;
    },

    playVideo(id) {
      this.favVideoId = id;
      this.showVideoPlayer = true;
      window.scrollTo({
        top: 0,
        behavior: 'smooth' // Smooth-Scrolling aktivieren, um einen animierten Übergang zu erzielen
      });
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
    
    getVideoUrl(video) {
      return 'https://www.youtube.com/embed/' + video.videoId;
    },
    getVideoUrlFav(videoId) {
      return 'https://www.youtube.com/embed/' + videoId;
    },
    getVideoTitel(videoTitel) {
      if (videoTitel.length > 45) {
        videoTitel = videoTitel.substring(0, 45) + "...";
      }
      return videoTitel;
    },
    //hier wird ein neuer Datensatz in die Datenbank geschrieben
    addToFavorites(video) {
      const videoData = {
        ID: video.id.videoId,
        Title: video.snippet.title,
        Thumbnail: video.snippet.thumbnails.default.url,
        Duration: video.duration,
      };
      axios
        .post('/api/index', videoData)
        .then(response => {
          console.log(response.data.message);
          this.fetchFavVideos();// Ausgabe des Backend-Responses
          Fav.methods.fetchFavVideos();
        })
        .catch(error => {
          console.error(error);
        });
      
    }

  }
};
</script>