require('./bootstrap');
import Vue from 'vue';
import VideoComponent from  './components/VideoComponent.vue';

// import router from './router';
const app = new Vue({
  el: '#app',
  components: { VideoComponent}
});
