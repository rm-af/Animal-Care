import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/Home.vue'
import Login from '../pages/Login.vue'
import Regis from '../pages/Regis.vue'

const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/login', name: 'Login', component: Login },
  { path: '/regis', name: 'Regis', component: Regis}
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
