import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter)
import example from './components/ExampleComponent.vue';
import video from './components/VideoComponent.vue';
const routes = [
    {
        path: '/example',
        component: example
    },
    {
        path: '/test',
        component: video
    }
]

export default new VueRouter({ mode: 'history', routes: routes })
