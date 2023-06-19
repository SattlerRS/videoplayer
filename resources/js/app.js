/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


import { createApp } from 'vue';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */



const app = createApp({});


import WelcomeComponent from './components/Welcome.vue';
app.component('welcome-component', WelcomeComponent);

import HomeComponent from './components/Home.vue';
app.component('home-component',HomeComponent)

import Profil from './components/Profil.vue';
app.component('profil-component', Profil);


app.mount('#app');


// Logik für Tooglebutton
document.addEventListener('DOMContentLoaded', function () {
        var toggleButton = document.querySelector('[data-te-dropdown-toggle-ref]');
        var dropdownMenu = document.querySelector('[data-te-dropdown-menu-ref]');

        toggleButton.addEventListener('click', function () {
            dropdownMenu.classList.toggle('hidden');
        });
});

// Logik für Button Seitenanfang
window.addEventListener('scroll', function() {
            var scrollToTopBtn = document.getElementById('scrollToTopBtn');
            if (window.pageYOffset > 0) {
                scrollToTopBtn.classList.remove('hidden');
            } else {
                scrollToTopBtn.classList.add('hidden');
            }
        });

        document.getElementById('scrollToTopBtn').addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
