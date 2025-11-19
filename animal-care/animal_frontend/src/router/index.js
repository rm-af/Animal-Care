import { createRouter, createWebHistory } from 'vue-router'

// =============== Import Semua Halaman ===============
import Login from '../pages/Login.vue'
import Regis from '../pages/Regis.vue'
import Home from '../pages/Home.vue'
import Antrian from '@/pages/Antrian.vue'
import Form_Antrian from '@/CRUD/Form_Antrian.vue'
import Dasboard from '@/pages/role_admin/Dasboard.vue'
import Antrian_Admin from '@/pages/role_admin/Antrian_Admin.vue'
import Layanan from '@/pages/role_admin/Layanan.vue'
import Hewan from '@/pages/role_admin/Hewan.vue'
import Update_Antrian from '@/CRUD/Update_Antrian.vue'
import Update_Layanan from '@/CRUD/Update_Layanan.vue'
import Update_Hewan from '@/CRUD/Update_Hewan.vue'

// =============== Import Halaman Pembayaran ===============
import PaymentPage from '@/pages/PaymentPage.vue'

// =============== Daftar Route ===============
const routes = [
  // Default route — langsung redirect ke /login
  { path: '/', redirect: '/login' },

  // =============== Halaman Auth ===============
  { path: '/login', name: 'Login', component: Login },
  { path: '/regis', name: 'Regis', component: Regis },

  // =============== Halaman User ===============
  {
    path: '/home',
    name: 'Home',
    component: Home,
    meta: { requiresAuth: true, role: 'User' },
  },
  {
    path: '/antrian',
    name: 'Antrian',
    component: Antrian,
    meta: { requiresAuth: true, role: 'User' },
  },
  {
    path: '/form_antrian',
    name: 'Form_Antrian',
    component: Form_Antrian,
    meta: { requiresAuth: true, role: 'User' },
  },

  // =============== Halaman Pembayaran (MIDTRANS) ===============
  {
    path: '/payment',
    name: 'Payment',
    component: PaymentPage,
    meta: { requiresAuth: true, role: 'User' },
  },

  // =============== Halaman Admin ===============
  {
    path: '/dasboard',
    name: 'Dasboard',
    component: Dasboard,
    meta: { requiresAuth: true, role: 'Admin' },
  },
  {
    path: '/antrian_admin',
    name: 'Antrian_Admin',
    component: Antrian_Admin,
    meta: { requiresAuth: true, role: 'Admin' },
  },
  {
    path: '/layanan',
    name: 'Layanan',
    component: Layanan,
    meta: { requiresAuth: true, role: 'Admin' },
  },
  {
    path: '/hewan',
    name: 'Hewan',
    component: Hewan,
    meta: { requiresAuth: true, role: 'Admin' },
  },

  // =============== CRUD (Admin) ===============
  {
    path: '/update_antrian/:id',
    name: 'Update_Antrian',
    component: Update_Antrian,
    meta: { requiresAuth: true },
  },
  {
    path: '/update_layanan/:id',
    name: 'Update_Layanan',
    component: Update_Layanan,
    meta: { requiresAuth: true, role: 'Admin' },
  },
  {
    path: '/update_hewan/:id',
    name: 'Update_Hewan',
    component: Update_Hewan,
    meta: { requiresAuth: true, role: 'Admin' },
  },

  // =============== Fallback 404 ===============
  { path: '/:pathMatch(.*)*', redirect: '/login' },
]

// =============== Inisialisasi Router ===============
const router = createRouter({
  history: createWebHistory(),
  routes,
})

// =============== Middleware Proteksi Route ===============
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')
  const user = JSON.parse(localStorage.getItem('user') || '{}')

  // Jika route butuh login
  if (to.meta.requiresAuth) {
    if (!token) {
      console.log('🔒 Tidak ada token — redirect ke login')
      return next('/login')
    }

    // Cek role
    if (to.meta.role && user.role !== to.meta.role) {
      console.log('🚫 Role tidak sesuai:', user.role)
      return next(user.role === 'Admin' ? '/dasboard' : '/home')
    }
  }

  // Jika sudah login tapi buka /login atau /regis
  if ((to.path === '/login' || to.path === '/regis') && token) {
    return next(user.role === 'Admin' ? '/dasboard' : '/home')
  }

  // Jika buka root langsung (/) tapi belum login → ke /login
  if (to.path === '/' && !token) {
    return next('/login')
  }

  next()
})

export default router
