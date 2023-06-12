<template>
  <div class="wrapper">
    <div class="header">
      <form action="#" v-on:submit.prevent="onSubmit">
        <div class="d-flex">
          <div class="p-2 flex-shrink-1">
            <button class="btn btn-link ml-2" @click="goUpFolder(source)">↑</button>
          </div>
          <div class="p-2 w-100">
            <input type="text" v-model="source" class="form-control">
          </div>
          <div class="p-2 flex-shrink-1">
            <button class="btn btn-outline-primary ml-2" @click="requestGenerateFileTreeObject(source)">Refresh</button>
          </div>
        </div>
      </form>
    </div>

    <div class="d-flex flex-row align-items-stretch">
      <div class="sidebar flex-column flex-shrink-1">
        <h3>Video player</h3>
        <ul class="nav">
          <template v-for="(file, index) in files">
            <a v-if="file.prefix" @click="requestGenerateFileTreeObject(source + '/' + file.prefix)">{{ file.prefix }}</a>
            <li class="nav-item" :class="{ active: file.path == currentFile.path, prefixed: prefixed }" v-if="file.isValid()">
              <file-component classes="nav-link" :file="file" :index="index" :play="play"></file-component>
            </li>
          </template>
      </ul>
    </div>
    <div class="content flex-column flex-fill">
      <div v-if="currentFile">
        <h3 class="mb-3">{{ currentFile.name }}</h3>
        <div class="embed-responsive embed-responsive-16by9">
          <video controls :src="currentFile.path" @click="toggle"></video>
        </div>
        <hr>
        <form action="#" class="form-inline justify-content-end" v-on:submit.prevent="onSubmit">
          Playback speed:
          <select class="form-control ml-2" v-model="speed">
            <option :value="option" v-for="option in [1, 1.25, 1.5, 1.75, 2, 2.5, 3, 4, 5]">{{ option }}</option>
          </select>
        </form>
      </div>
      <div v-else>
        <h3 class="mb-3">Select a video to start</h3>
      </div>
    </div>
  </div>
</div>
</template>
 <script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
 </script>
 <style scoped>
     html, body {
         background-color: 
#fff;
         color: 
#636b6f;
         font-family: 'Raleway', sans-serif;
         font-weight: 100;
         height: 100vh;
         margin: 0;
     }

     .full-height {
         height: 100vh;
     }

     .flex-center {
         align-items: center;
         display: flex;
         justify-content: center;
     }

     .position-ref {
         position: relative;
     }

     .top-right {
         position: absolute;
         right: 10px;
         top: 18px;
     }

     .content {
         text-align: center;
     }

     .title {
         font-size: 84px;
     }

     .links > a {
         color: 
#636b6f;
         padding: 0 25px;
         font-size: 12px;
         font-weight: 600;
         letter-spacing: .1rem;
         text-decoration: none;
         text-transform: uppercase;
     }

     .m-b-md {
         margin-bottom: 30px;
     }
 </style>