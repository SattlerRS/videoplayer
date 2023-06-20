<template>
  <div class="h-screen bg-black flex flex-col items-center">
    <div class="max-w-lg mt-4  bg-black p-8 rounded-lg flex flex-col items-center shadow">
      <div v-if="user">
        <div class="mb-2 flex flex-col items-center">
          <h1 class="text-2xl font-bold mb-2 text-white">Benutzerdetails</h1>
          <img :src="selectedImage ? selectedImage : '/image/' + user.image" alt="Benutzerbild" ref="file"
            class="w-50 h-50 rounded-full ml-20 mr-20 " @click="selectImage"
            :style="{ cursor: selectedImage ? 'pointer' : 'default' }">
        </div>
        <div class="mb-2 flex flex-col items-center">
          <label for="name" class="mb-1 text-lg text-white">Name:</label>
          <input type="text" id="name" v-model="user.name"
            class="border border-gray-300 rounded-lg mx-2 px-2 py-1 mb-2 text-center text-lg">
        </div>
        <div class="mb-2 flex flex-col items-center">
          <label for="email" class="mb-1 text-lg text-white">E-Mail:</label>
          <input type="email" id="email" v-model="user.email"
            class="border border-gray-300 rounded-lg mx-2 px-2 py-1 text-center text-lg">
        </div>
        <div id="passwordcontainer" v-if="showPasswordPopup" class="mb-2 flex flex-col items-center">
          <label for="passwordOrigin" class="mb-1 text-lg text-white">Aktuelles Passwort:</label>
          <input type="password" id="passwordOrigin" v-model="user.passwordOrigin"
            class="border border-gray-300 rounded-lg mx-2 px-2 py-1 text-center text-lg" required>
        </div>
        <div id="passwordcontainer" v-if="showPasswordPopup" class="mb-2 flex flex-col items-center">
          <label for="password" class="mb-1 text-lg text-white">Neues Passwort:</label>
          <input type="password" id="password" v-model="user.password"
            class="border border-gray-300 rounded-lg mx-2 px-2 py-1 text-center text-lg" required>
        </div>
        <div id="passwordcontainer" v-if="showPasswordPopup" class="mb-2 flex flex-col items-center">
          <label for="passwordRepeat" class="mb-1 text-lg text-white">Passwort wiederholen:</label>
          <input type="password" id="passwordRepeat" v-model="user.passwordRepeat"
            class="border border-gray-300 rounded-lg mx-2 px-2 py-1 text-center text-lg" required>
        </div>
        <form @submit.prevent="submitUser" class="flex flex-col items-center">
          <input ref="fileInput" type="file" style="display: none" @change="handleImageChange"
            class="border border-gray-300 rounded-lg px-2 py-1">
          <button type="submit"
            class="bg-orange-500 text-black px-4 py-2 rounded-lg text-lg font-bold save-button">Speichern</button>
        </form>
        <div class="flex flex-row items-center justify-between">
          <button
            class="bg-orange-500 text-black w-20 mx-2 px-2 py-1 rounded-lg text-md font-bold mt-4 save-button flex-grow"
            @click="togglePasswordContainers">{{ showPasswordPopup ? '- Passwort' : '+ Passwort' }}</button>
          <button
            class="bg-orange-500 text-black w-20 mx-2 px-2 py-1 rounded-lg text-md font-bold mt-4 save-button flex-grow"
            @click="redirectToHome">Zurück</button>
        </div>

      </div>
      <div v-else>
        <p>Benutzerdaten werden geladen...</p>
      </div>
    </div>
  </div>
</template>


<script>

//Importe
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
  data() {
    return {
      user: null,
      selectedImage: null,
      showPasswordPopup: false
    };
  },

  //Methode die beim laden der Seite aufgerufen wird
  mounted() {
    this.fetchUser();
  },

  methods: {
    // Passwortsektion anzeigen und ausblenden
    togglePasswordContainers() {
      this.showPasswordPopup = !this.showPasswordPopup;
      if (this.showPasswordPopup === false) {
        this.user.passwordRepeat = '';
        this.user.password = '';
        this.user.passwordOrigin = '';
      }
    },

    // Startseite anzeigen
    redirectToHome() {
      window.location.href = '/';
    },

    // Userdaten vom app.blade abfragen und in this.user speichern
    fetchUser() {
      const userMeta = document.querySelector("meta[name='user']");
      if (userMeta) {
        const user = JSON.parse(userMeta.getAttribute('content'));
        this.user = user;
        if (user.image) {
          this.selectedImage = '/image/' + user.image;
        }
      }
    },

    // Clickevent für die Bildauswahl
    selectImage() {
      this.$refs.fileInput.click();
    },

    // ausgewähltes Bild im Frontend anzeigen und in die Variable speichern
    handleImageChange(event) {
      const file = event.target.files[0];
      this.selectedImage = URL.createObjectURL(file);
    },

    //UserDaten an das Backend senden
    submitUser() {
      // Inputfelderwerte in FormData angängen
      const formData = new FormData();
      formData.append('name', this.user.name);
      formData.append('email', this.user.email);
      //Wenn die Passwortfelder eingeblendet sind werden die Werte an die FormData angehängt
      if (this.showPasswordPopup === true) {
        formData.append('password', this.user.password);
        formData.append('passwordRepeat', this.user.passwordRepeat);
        formData.append('passwordOrigin', this.user.passwordOrigin);
      }
      if (this.$refs.fileInput.files[0]) {
        formData.append('image', this.$refs.fileInput.files[0]);
      }

      // Inputs an das Backend übergeben
      axios.post('/user/update', formData)
        .then(response => {
          // Bei erfolgreicher verarbeitung auf die Startseite wechseln
          window.location.href = '/';
        })
        .catch(error => {

          // Fehlermedlungen werden über das Popup ausgegeben
          Swal.fire({
            title: 'Achtung',
            text: error.response.data.message,
            icon: 'warning',
            confirmButtonText: 'OK',
            timer: 2000,
            timerProgressBar: true,
            confirmButtonColor: 'orangered',
            customClass: {
              popup: 'dark-mode-popup',
            },
          });
        });
    }
  }
};
</script>

<style>
/* Style für den Sweetalert */
.dark-mode-popup {
  background-color: #1e1e1e;
  border-color: #ffffff;
}</style>