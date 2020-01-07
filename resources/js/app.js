require('./bootstrap');
window.Vue = require('vue');
import moment from 'moment'

//Vue Form for installation....
import Vue from 'vue'
import { Form, HasError, AlertError } from 'vform'
window.Form = Form

//Vue Notify installation....
import Snotify, { SnotifyPosition } from 'vue-snotify'

const options = {
  toast: {
    position: SnotifyPosition.rightTop
  }
}

Vue.use(Snotify, options)

//Vue Router for installation....
import VueRouter from 'vue-router'
Vue.use(VueRouter)


//All Router are includes here....
import Dashboard from './components/Dashboard.vue'
import Profile from './components/Profile.vue'
import Users from './components/Users.vue'
import Customer from './components/Customer.vue'

// import Dashboard from './components/Dashboard.vue';

const routes= [

    { path: '/dashboard', component: Dashboard },
    { path: '/profile', component: Profile },
    { path: '/users', component: Users },
    { path: '/customer', component: Customer },

]

var router = new VueRouter({
	mode: 'history',
    routes
});




    



// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('pagination-component', require('./components/PaginationComponent.vue').default);

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// For creattion the uppercase character...
Vue.filter('upText', function(text) {
	return text[0].toUpperCase() + text.slice(1)
});

Vue.filter('myDate', function(date) {
	return moment(date).format('L');
});



const app = new Vue({
    el: '#app',
    router,

    
});
