<template>
  <div
    class="container-fluid vh-100 d-flex align-items-center justify-content-center text-white"
    style="background-color: #5b6ef5; padding-top: 80px;"
  >
    <!-- Navbar -->
    <nav
      class="navbar navbar-expand-lg navbar-dark shadow-sm fixed-top"
      style="background-color: #5b6ef5; z-index: 1000;"
    >
      <div class="container">
        <a class="navbar-brand fw-bold text-white d-flex align-items-center" href="#">
          <img src="/animal.png" alt="logo" class="logo-navbar me-2" />
          <span class="fs-4">
            Animal <span class="text-warning">Care</span>
          </span>
        </a>
      </div>
    </nav>

    <!-- Konten -->
    <div class="container">
      <div class="row w-100 align-items-center">
        <!-- Kolom Kiri -->
        <div class="col-md-6 d-flex flex-column justify-content-center">
          <h1 class="fw-bold display-4 mb-3">Form Register</h1>
          <p class="lead fs-5">
            Silahkan mengisikan Form Register untuk membuat akun baru Anda dan mulai menggunakan layanan Animal Care.
          </p>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-md-6 d-flex justify-content-center">
          <div class="card p-4 shadow rounded-4" style="min-width: 380px; max-width: 420px;">
            <form @submit.prevent="handleSubmit">
              <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" v-model="form.username" class="form-control" required />
              </div>

              <div class="mb-3">
                <label class="form-label">Role</label>
                <input type="text" v-model="form.role" class="form-control" required />
              </div>

              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" v-model="form.email" class="form-control" required />
              </div>

              <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" v-model="form.password" class="form-control" required />
              </div>

              <button type="submit" class="btn btn-custom w-100 fw-bold">
                Kirim
              </button>

              <p class="mt-3 text-center">
                Sudah punya akun?
                <router-link to="/login" class="fw-bold text-decoration-none">Login</router-link>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- POPUP ALERT -->
    <transition name="fade-scale">
      <div v-if="showPopup" class="popup-overlay">
        <div class="popup-card text-center p-4 rounded-4 shadow-lg">
          <img src="/popup.png" alt="success" class="popup-img mb-3" />
          <h4 class="fw-bold mb-2">Registrasi berhasil!</h4>
          <p class="text-muted">Akun Anda telah berhasil dibuat.</p>
          <button class="btn btn-popup mt-3 fw-bold" @click="closePopup">Continue</button>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "RegisterForm",
  data() {
    return {
      form: {
        username: "",
        role: "",
        email: "",
        password: "",
      },
      showPopup: false,
    };
  },
  methods: {
    async handleSubmit() {
      try {
        const response = await axios.post("http://127.0.0.1:8000/api/register", this.form);

        // Tampilkan popup sukses
        this.showPopup = true;

        // Reset form
        this.form.username = "";
        this.form.role = "";
        this.form.email = "";
        this.form.password = "";
      } catch (error) {
        if (error.response) {
          alert(error.response.data.message || "Gagal registrasi!");
        } else {
          alert("Tidak dapat terhubung ke server.");
        }
      }
    },
    closePopup() {
      this.showPopup = false;
      this.$router.push("/login");
    },
  },
};
</script>

<style scoped>
.btn-custom {
  background-color: #485ae3ff;
  color: #fff;
  border: none;
}

.btn-custom:hover {
  background-color: #5b6ef5;
  color: #fff;
}

.logo-navbar {
  height: 56px;
  width: auto;
}

/* === POPUP STYLE === */
.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2000;
  backdrop-filter: blur(2px);
}

.popup-card {
  background: #fff;
  color: #000;
  width: 360px;
  animation: scaleIn 0.4s ease forwards;
}

.popup-img {
  width: 160px;
  height: auto;
  display: block;
  margin: 0 auto;
}

.btn-popup {
  background: linear-gradient(to right, #667eea, #764ba2);
  color: white;
  border: none;
  border-radius: 10px;
  padding: 10px 20px;
  transition: 0.3s;
}

.btn-popup:hover {
  opacity: 0.9;
  transform: scale(1.05);
}

/* === ANIMATIONS === */
.fade-scale-enter-active,
.fade-scale-leave-active {
  transition: all 0.3s ease;
}

.fade-scale-enter-from,
.fade-scale-leave-to {
  opacity: 0;
  transform: scale(0.9);
}

@keyframes scaleIn {
  from {
    transform: scale(0.8);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}
</style>
