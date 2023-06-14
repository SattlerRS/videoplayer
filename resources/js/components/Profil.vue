<template>
  <div class="max-h-screen bg-gray-100 flex flex-col items-center justify-center">
    <div class="max-w-lg bg-black shadow-lg p-8 rounded-lg flex flex-col items-center">
      
      <div v-if="user">
        <div class="mb-2 flex flex-col items-center">
          <h1 class="text-2xl font-bold mb-2 text-white">Benutzerdetails</h1>
          <img :src="selectedImage ? selectedImage : '/image/' + user.image" alt="Benutzerbild" ref="file" class="rounded-full mb-4" @click="selectImage" :style="{ cursor: selectedImage ? 'pointer' : 'default' }">
        </div>
        <div class="mb-2 flex flex-col items-center">
          <label for="name" class="mb-1 text-lg text-white">Name:</label>
          <input type="text" id="name" v-model="user.name" class="border border-gray-300 rounded-lg mx-2 px-2 py-1 mb-2 text-center text-lg">
        </div>
        <div class="mb-2 flex flex-col items-center">
          <label for="email" class="mb-1 text-lg text-white">E-Mail:</label>
          <input type="email" id="email" v-model="user.email" class="border border-gray-300 rounded-lg mx-2 px-2 py-1 text-center text-lg">
        </div>
        <div id="passwordcontainer" v-if="showPasswordPopup" class="mb-2 flex flex-col items-center">
          <label for="passwordOrigin" class="mb-1 text-lg text-white">Aktuelles Passwort:</label>
          <input type="password" id="passwordOrigin" v-model="user.passwordOrigin" class="border border-gray-300 rounded-lg mx-2 px-2 py-1 text-center text-lg">
        </div>
        <div id="passwordcontainer" v-if="showPasswordPopup" class="mb-2 flex flex-col items-center">
          <label for="password" class="mb-1 text-lg text-white">Neues Passwort:</label>
          <input type="password" id="password" v-model="user.password" class="border border-gray-300 rounded-lg mx-2 px-2 py-1 text-center text-lg">
        </div>
        <div id="passwordcontainer" v-if="showPasswordPopup" class="mb-2 flex flex-col items-center">
          <label for="passwordRepeat" class="mb-1 text-lg text-white">Passwort wiederholen:</label>
          <input type="password" id="passwordRepeat" v-model="user.passwordRepeat" class="border border-gray-300 rounded-lg mx-2 px-2 py-1 text-center text-lg">
        </div>
        <form @submit.prevent="submitUser" class="flex flex-col items-center">
          <input ref="fileInput" type="file" style="display: none" @change="handleImageChange" class="border border-gray-300 rounded-lg px-2 py-1">
          <button type="submit" class="bg-orange-500 text-black px-4 py-2 rounded-lg text-lg font-bold save-button">Speichern</button>
        </form>
        <div class="mb-4 flex flex-row items-center justify-center">
          <button class="bg-orange-500 text-black mx-2 px-2 py-1 rounded-lg text-md font-bold mt-4 save-button" @click="togglePasswordContainers">Passwort ändern</button>
          <button class="bg-orange-500 text-black mx-2 px-2 py-1 rounded-lg text-md font-bold mt-4 save-button" @click="redirectToHome">Back</button>
        </div>
      </div>
      <div v-else>
        <p>Benutzerdaten werden geladen...</p>
      </div>
    </div>
  </div>
</template>


<script>
import axios from 'axios';

export default {
  data() {
    return {
      user: null,
      selectedImage: null,
      showPasswordPopup: false
    };
  },
  mounted() {
    this.fetchUser();
  },
  methods: {
    togglePasswordContainers() {
      this.showPasswordPopup = !this.showPasswordPopup;
      if (this.showPasswordPopup === false) {
        this.user.passwordRepeat = '';
        this.user.password = '';
        this.user.passwordOrigin = '';
  }
},
    redirectToHome() {
      window.location.href = '/';
    },
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
    selectImage() {
      this.$refs.fileInput.click();
    },
    handleImageChange(event) {
      const file = event.target.files[0];
      this.selectedImage = URL.createObjectURL(file);
    },
    submitUser() {
      const formData = new FormData();
      formData.append('name', this.user.name);
      formData.append('email', this.user.email);
      if (this.showPasswordPopup === true) {
        formData.append('password', this.user.password);
        formData.append('passwordRepeat', this.user.passwordRepeat);
        formData.append('passwordOrigin', this.user.passwordOrigin);
      }
      if (this.$refs.fileInput.files[0]) {
        formData.append('image', this.$refs.fileInput.files[0]);
      }

      axios.post('/user/update', formData)
        .then(response => {
          // Erfolgreiche Antwort verarbeiten
          window.location.href = '/';
          console.log(response.data);
        })
        .catch(error => {
          // Fehler verarbeiten
          console.error(error.response.data);
        });
    }
  }
};
</script>