import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/Home.vue'
import Login from '../pages/Login.vue'
import Regis from '../pages/Regis.vue'
import Antrian from '@/pages/Antrian.vue'
import Form_Antrian from '@/pages/Form_Antrian.vue'

const routes = [

  // Landing Page
  { path: '/',
    name: 'Home',
    component: Home
  },
  { path: '/login',
    name: 'Login',
    component: Login
  },
  { path: '/regis',
    name: 'Regis',
    component: Regis
  },
  {
    path : '/Antrian',
    name : 'Antrian',
    component : Antrian
  },
  {
    path : '/Form_Antrian',
    name : 'Form_Antrian',
    component : Form_Antrian
  },


  // CRUD
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
