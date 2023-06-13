
Hier ist der erweiterte Code mit einem Submit-Button, der die Benutzerdaten über einen Axios POST-Request an den Server übermittelt:

html
Copy code
<template>
  <div class="max-h-screen bg-gray-100 flex flex-col items-center justify-center">
    <div class="max-w-md bg-white shadow-md p-8 rounded-lg flex flex-col items-center">
      
      <div v-if="user">
        <div class="mb-4 flex flex-col items-center">
          <h1 class="text-2xl font-bold mb-4">Benutzer-Detailansicht</h1>
          <img :src="selectedImage ? selectedImage : '/image/' + user.image" alt="Benutzerbild" ref="file" class="rounded-full mb-4" @click="selectImage" :style="{ cursor: selectedImage ? 'pointer' : 'default' }">
        </div>
        <div class="mb-4 flex flex-row items-center">
          <label for="name" class="mb-1">Name: </label>
          <input type="text" id="name" v-model="user.name" class="border border-gray-300 rounded-lg mx-2 px-2 py-1 mb-2">
        </div>
        <div class="mb-4 flex flex-row items-center">
          <label for="email" class="mb-1">E-Mail: </label>
          <input type="email" id="email" v-model="user.email" class="border border-gray-300 rounded-lg mx-2 px-2 py-1">
        </div>
        <form @submit.prevent="submitUser">
          <input ref="fileInput" type="file" style="display: none" @change="handleImageChange" class="border border-gray-300 rounded-lg px-2 py-1">
          <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Speichern</button>
        </form>
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
      selectedImage: null
    };
  },
  mounted() {
    this.fetchUser();
  },
  methods: {
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
      if (this.$refs.fileInput.files[0]) {
        formData.append('image', this.$refs.fileInput.files[0]);
      }

      axios.post('/user/update', formData)
        .then(response => {
          // Erfolgreiche Antwort verarbeiten
          console.log(response.data);
        })
        .catch(error => {
          // Fehler verarbeiten
          console.error(error);
        });
    }
  }
};
</script>