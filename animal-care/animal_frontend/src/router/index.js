import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/Home.vue'
import Login from '../pages/Login.vue'
import Regis from '../pages/Regis.vue'
import Antrian from '@/pages/Antrian.vue'
import Form_Antrian from '@/CRUD/Form_Antrian.vue'
import Dasboard from '@/pages/role_admin/Dasboard.vue'
import Antrian_Admin from '@/pages/role_admin/Antrian_Admin.vue'
import Layanan from '@/pages/role_admin/Layanan.vue'
import Hewan from '@/pages/role_admin/Hewan.vue'

const routes = [

  // Landing Page user
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

  // Landing Page Admin
  {
    path : '/Dasboard',
    name : 'Dasboard',
    component : Dasboard
  },
  {
    path : '/Antrian_Admin',
    name : 'Antrian_Admin',
    component : Antrian_Admin
  },
  {
    path : '/Layanan',
    name : 'Layanan',
    component : Layanan
  },
  {
    path : '/Hewan',
    name : 'Hewan',
    component : Hewan
  }


  // CRUD
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
