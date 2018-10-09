
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.$ = window.jQuery = require('jquery'); 
    
window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('clase', require('./components/Clase.vue'));
Vue.component('item', require('./components/Item.vue'));
Vue.component('zona', require('./components/Zona.vue'));
Vue.component('parametro', require('./components/Parametro.vue'));
Vue.component('rol', require('./components/Rol.vue'));
Vue.component('user', require('./components/User.vue'));
Vue.component('ganadero', require('./components/Ganadero.vue'));
Vue.component('estancia', require('./components/Estancia.vue'));
Vue.component('visita', require('./components/Visita.vue'));
Vue.component('dashboard', require('./components/Dashboard.vue'));
Vue.component('consultavisita', require('./components/consultaVisita.vue'));
Vue.component('notification', require('./components/Notification.vue'));

Vue.component('ganaderobaja', require('./components/Ganaderobaja.vue'));
Vue.component('estanciabaja', require('./components/Estanciabaja.vue'));

Vue.component('rpestancia', require('./components/Rpestancia'));
Vue.component('rpvisita', require('./components/Rpvisita.vue'));
const app = new Vue({
    el: '#app',
    data :{
        menu : 0,
        notifications:[],
        ruta :'http://97.0.0.118/sistemasgg/public/'    
    }, 
    created() {
        let me = this;     
        axios.post( this.ruta + 'notification/get').then(function(response) {
           //console.log(response.data);
           me.notifications=response.data;    
        }).catch(function(error) {
            console.log(error);
        });

        var userId = $('meta[name="userId"]').attr('content');
        
        Echo.private('App.User.' + userId).notification((notification) => {
             me.notifications.unshift(notification); 
        }); 
        
    } 
});
