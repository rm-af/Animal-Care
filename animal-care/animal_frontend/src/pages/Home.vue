<template>
  <div>
    <!-- Navbar -->
    <nav
      class="navbar navbar-expand-lg navbar-dark shadow-sm fixed-top"
      style="background-color: #5b6ef5; z-index: 1000;"
    >
      <div class="container">
        <a class="navbar-brand fw-bold text-white d-flex align-items-center" href="#">
          <img src="/animal.png" alt="logo" class="logo-navbar me-2" />
          <span class="fs-4">Animal <span class="text-warning">Care</span></span>
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto align-items-lg-center">
            <li class="nav-item me-4">

              <router-link to="/" class="nav-link text-white nav-anim" exact>Home</router-link>
            </li>
            <li class="nav-item me-4">

              <a
                href="#layanan"
                class="nav-link text-white nav-anim"
                :class="{ active: activeSection === 'layanan' }"
                @click.prevent="scrollToSection('layanan')"
              >
                Layanan
              </a>
            </li>
            <li class="nav-item me-4">
              <a
                href="#hewan"
                class="nav-link text-white nav-anim"
                :class="{ active: activeSection === 'hewan' }"
                @click.prevent="scrollToSection('hewan')"
              >
                Hewan
              </a>
            </li>
            <li class="nav-item me-4">
              <router-link to="/Antrian" class="nav-link text-white nav-anim">Antrian</router-link>
            </li>

            <li class="nav-item">
              <button
                v-if="isLoggedIn"
                @click="logout"
                class="btn btn-danger ms-3"
              >
                Logout
              </button>
              <router-link
                v-else
                to="/login"
                class="btn btn-success ms-3"
              >
                Login
              </router-link>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Popup Sambutan Modern -->
    <transition name="fade-popup">
      <div v-if="showWelcome" class="welcome-overlay">
        <div class="welcome-card shadow-lg">
          <div class="welcome-content text-center">
            <img src="/animal.png" alt="logo" class="welcome-logo mb-3" />
            <h4 class="fw-bold text-primary animate-fade">
              Selamat Datang{{ currentUser ? ', ' + currentUser.username : '' }} di
              <span class="text-warning">Animal Care</span>!
            </h4>
            <p class="text-muted animate-slide">🐾 Senang bisa melihatmu! 🐾</p>
          </div>
          <div class="welcome-progress"></div>
        </div>
      </div>
    </transition>

    <!-- Hero Section -->
    <section
      class="d-flex align-items-center"
      style="background-color: #5b6ef5; height: 100vh; padding-top: 80px;"
      id="home"
    >
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-6 text-white">
            <h1 class="fw-bold mb-4 display-4 hero-headline" style="line-height: 1.3;">
              <span class="typed">{{ typedText }}</span><span class="caret">|</span>
            </h1>

            <p class="mb-4 fs-5" style="color: #e6e6e6; max-width: 480px;">
              Cek berkala kesehatan hewan kesayangan anda agar tetap sehat dan happy
            </p>
            <router-link
              to="/Form_Antrian"
              class="btn btn-daftar fw-bold d-inline-flex align-items-center gap-2"
            >
              Daftar Antrian
              <i class="fas fa-arrow-right"></i>
              <span class="neon-border"></span>
            </router-link>
          </div>

          <div class="col-md-6 text-center">
            <img
              src="/Ilustrasi.png"
              alt="Ilustrasi Vet Clinic"
              class="img-fluid hero-img"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- Layanan Section -->
    <section id="layanan" class="py-5 section-bg">
      <div class="container text-center">
        <h2 class="fw-bold mb-2 section-title">Layanan Kami</h2>
        <p class="text-muted mb-5">Peliharaan sehat menghadirkan kebahagiaan abadi</p>

        <div class="row g-4" v-if="layananList.length > 0">
          <div
            class="col-md-4"
            v-for="(layanan, index) in layananList"
            :key="layanan.id"
            :class="{'fade-up': true, 'visible': visibleCards.includes(index)}"
            ref="layananCards"
          >
            <div class="card layanan-card h-100 text-center p-4">
              <div v-if="layanan.logo" class="icon-glass-circle mb-3">
                <img :src="getImageUrl(layanan.logo)" alt="icon layanan" />
              </div>
              <h5 class="fw-bold section-title">{{ layanan.nama_layanan }}</h5>
              <p class="text-muted small">{{ layanan.deskripsi }}</p>
            </div>
          </div>
        </div>
        <p v-else class="text-muted">Belum ada layanan tersedia</p>
      </div>
    </section>

    <!-- Jenis Hewan Section -->
    <section id="hewan" class="py-5 section-bg">
      <div class="container text-center">
        <h2 class="fw-bold mb-2 section-title">Jenis Hewan</h2>
        <p class="text-muted mb-5">Macam-macam hewan peliharaan yang bisa kami layani</p>

        <div class="row g-4" v-if="hewanList.length > 0">
          <div
            class="col-md-4 col-lg-3"
            v-for="(hewan, index) in hewanList"
            :key="hewan.id"
            :class="{'fade-up': true, 'visible': visibleHewan.includes(index)}"
            ref="hewanCards"
          >
            <div class="card hewan-card shadow-sm border-0 h-100 text-center p-3">
              <div v-if="hewan.gambar" class="hewan-image-wrapper mb-3">
                <img :src="getImageUrl(hewan.gambar)" alt="hewan" />
              </div>
              <h6 class="fw-bold section-title">{{ hewan.nama_hewan }}</h6>
              <p class="text-muted small">{{ hewan.deskripsi }}</p>
            </div>
          </div>
        </div>
        <p v-else class="text-muted">Belum ada data hewan tersedia</p>
      </div>
    </section>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "HomeView",
  data() {
    return {
      layananList: [],
      hewanList: [],
      visibleCards: [],
      visibleHewan: [],
      refreshInterval: null,
      isLoggedIn: !!localStorage.getItem("token"),
      showWelcome: false,
      currentUser: null,
      activeSection: "home",
      phrases: [
        "Ayo Periksa Hewan Kesayanganmu",
        "Pastikan mereka selalu sehat, ceria, dan bebas penyakit."
      ],
      typedText: "",
      typingIndex: 0,
      charIndex: 0,
      typingSpeed: 50,
      deletingSpeed: 30,
      pauseAfterTyped: 3000,
      pauseAfterDeleted: 400,
      typingTimer: null,
      deletingTimer: null,
      loopTimer: null
    };
  },
  mounted() {
    this.fetchLayanan();
    this.fetchHewan();
    this.isLoggedIn = !!localStorage.getItem("token");

    const userData = localStorage.getItem("user");
    if (userData) {
      this.currentUser = JSON.parse(userData);
    }

    window.addEventListener("scroll", this.handleScroll);
    this.handleScroll();

    this.refreshInterval = setInterval(() => {
      this.fetchLayanan();
      this.fetchHewan();
    }, 10000);

    if (this.isLoggedIn) {
      this.showWelcome = true;
      setTimeout(() => (this.showWelcome = false), 3500);
    }

    this.startTyping();
  },
  beforeUnmount() {
    window.removeEventListener("scroll", this.handleScroll);
    clearInterval(this.refreshInterval);
    clearTimeout(this.typingTimer);
    clearTimeout(this.deletingTimer);
    clearTimeout(this.loopTimer);
  },
  methods: {
    getImageUrl(path) {
      if (!path) return "/no-image.png";
      if (path.startsWith("http")) return path;
      return `http://127.0.0.1:8000/storage/${path}`;
    },
    async fetchLayanan() {
      try {
        const res = await axios.get("http://127.0.0.1:8000/api/layanan");
        this.layananList = Array.isArray(res.data) ? res.data : [];
      } catch (error) {
        console.error("Gagal fetch layanan:", error);
      }
    },
    async fetchHewan() {
      try {
        const res = await axios.get("http://127.0.0.1:8000/api/hewan");
        this.hewanList = Array.isArray(res.data) ? res.data : [];
      } catch (error) {
        console.error("Gagal fetch hewan:", error);
      }
    },
    handleScroll() {
      const scrollTop = window.scrollY + window.innerHeight * 0.6;

      this.$refs.layananCards?.forEach((el, i) => {
        if (el.offsetTop < scrollTop && !this.visibleCards.includes(i)) {
          this.visibleCards.push(i);
        }
      });
      this.$refs.hewanCards?.forEach((el, i) => {
        if (el.offsetTop < scrollTop && !this.visibleHewan.includes(i)) {
          this.visibleHewan.push(i);
        }
      });

      const layanan = document.getElementById("layanan");
      const hewan = document.getElementById("hewan");
      if (hewan && window.scrollY + 150 >= hewan.offsetTop) {
        this.activeSection = "hewan";
      } else if (layanan && window.scrollY + 150 >= layanan.offsetTop) {
        this.activeSection = "layanan";
      } else {
        this.activeSection = "home";
      }
    },
    setActiveSection(section) {
      this.activeSection = section;
    },

    scrollToSection(sectionId) {
      const el = document.getElementById(sectionId);
      if (!el) return;
      const top = el.getBoundingClientRect().top + window.scrollY - 70; // offset for fixed navbar
      window.scrollTo({
        top,
        behavior: "smooth"
      });
      this.activeSection = sectionId;
    },
    logout() {
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      this.isLoggedIn = false;
      alert("Anda telah logout.");
      this.$router.push("/login");
    },
    startTyping() {
      this.charIndex = 0;
      this.typedText = "";
      this.typeChar();
    },
    typeChar() {
      const phrase = this.phrases[this.typingIndex];
      if (this.charIndex < phrase.length) {
        this.typedText += phrase.charAt(this.charIndex);
        this.charIndex++;
        this.typingTimer = setTimeout(() => this.typeChar(), this.typingSpeed);
      } else {
        this.loopTimer = setTimeout(() => this.deleteChar(), this.pauseAfterTyped);
      }
    },
    deleteChar() {
      if (this.charIndex > 0) {
        this.typedText = this.typedText.slice(0, -1);
        this.charIndex--;
        this.deletingTimer = setTimeout(() => this.deleteChar(), this.deletingSpeed);
      } else {
        this.typingIndex = (this.typingIndex + 1) % this.phrases.length;
        this.loopTimer = setTimeout(() => this.typeChar(), this.pauseAfterDeleted);
      }
    }
  }
};
</script>

<style scoped>
html {
  scroll-behavior: smooth;
}


.nav-anim {
  position: relative;
  font-weight: 500;
  padding-bottom: 6px;
  transition: color 0.3s ease, transform 0.15s ease;
}


.nav-anim::after {
  content: "";
  position: absolute;
  bottom: -2px;
  left: 50%;
  width: 0;
  height: 3px;
  background: #ffffff;
  transition: all 0.28s cubic-bezier(.2,.9,.2,1);
  transform: translateX(-50%);
  border-radius: 2px;
}


.nav-anim:hover::after {
  width: 100%;
}


.nav-anim.router-link-active::after,
.nav-anim.router-link-exact-active::after,
.nav-anim.active::after {
  width: 100%;
}


.nav-anim.router-link-active,
.nav-anim.router-link-exact-active,
.nav-anim.active {
  color: #ffffff !important;
  font-weight: 600;
  text-shadow: 0 0 10px rgba(255,255,255,0.85);
}


.nav-anim:hover {
  color: #ffffff !important;
  transform: translateY(-2px);
}


.welcome-overlay {
  position: fixed;
  inset: 0;
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(10px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 3000;
  animation: fadeInOverlay 0.8s ease;
}
.welcome-card {
  background: #fff;
  border-radius: 24px;
  padding: 35px 40px;
  text-align: center;
  position: relative;
  box-shadow: 0 12px 30px rgba(91, 110, 245, 0.25);
  overflow: hidden;
  animation: scaleUp 0.7s ease;
}
.welcome-logo {
  width: 80px;
  height: 80px;
  animation: popIn 1s ease;
}
.welcome-progress {
  position: absolute;
  bottom: 0;
  left: 0;
  height: 4px;
  width: 0;
  background: linear-gradient(90deg, #5b6ef5, #00c2ff);
  animation: progressBar 3.5s linear forwards;
}
@keyframes fadeInOverlay {
  from { opacity: 0; }
  to { opacity: 1; }
}
@keyframes scaleUp {
  from { transform: scale(0.8); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}
@keyframes popIn {
  0% { transform: scale(0) rotate(-180deg); opacity: 0; }
  100% { transform: scale(1) rotate(0deg); opacity: 1; }
}
@keyframes progressBar {
  from { width: 0; }
  to { width: 100%; }
}
.animate-fade { animation: fadeInUp 0.8s ease both; }
.animate-slide { animation: fadeInUp 1.2s ease 0.3s both; }


.fade-up {
  opacity: 0;
  transform: translateY(40px);
  transition: all 0.7s ease;
}
.fade-up.visible {
  opacity: 1;
  transform: translateY(0);
}


.icon-glass-circle {
  width: 110px;
  height: 110px;
  border-radius: 50%;
  margin: 0 auto 20px;
  background: radial-gradient(circle at 30% 30%, #ffffff 0%, #e4e9ff 55%, #c3cbff 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: inset 0 3px 8px rgba(255, 255, 255, 0.7), 0 5px 15px rgba(91, 110, 245, 0.25);
}
.icon-glass-circle img {
  width: 70%;
  height: 70%;
  object-fit: contain;
  transition: transform 0.3s ease;
}
.layanan-card:hover .icon-glass-circle img { transform: scale(1.08); }
.layanan-card {
  background-color: #fff;
  border-radius: 20px;
  box-shadow: 0 4px 15px rgba(91, 110, 245, 0.15);
  transition: all 0.3s ease;
  padding: 30px 20px;
}
.layanan-card:hover {
  background-color: #5b6ef5;
  transform: translateY(-6px);
  box-shadow: 0 10px 25px rgba(91, 110, 245, 0.35);
}
.layanan-card:hover .section-title,
.layanan-card:hover .text-muted { color: #fff !important; }

.hewan-card { border-radius: 20px; background-color: #fff; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(91, 110, 245, 0.15); }
.hewan-card:hover { background-color: #5b6ef5; transform: translateY(-6px); box-shadow: 0 10px 25px rgba(91, 110, 245, 0.35); }
.hewan-card:hover .section-title, .hewan-card:hover .text-muted { color: #fff !important; }
.hewan-image-wrapper { border-radius: 16px; overflow: hidden; width: 100%; height: 180px; }
.hewan-image-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease; }
.hewan-card:hover .hewan-image-wrapper img { transform: scale(1.05); }


.btn-daftar {
  position: relative;
  font-size: 1.2rem;
  padding: 14px 45px;
  border-radius: 12px;
  box-shadow: 0 0 20px rgba(91, 110, 245, 0.4);
  transition: all 0.3s ease;
  overflow: hidden;
  border: none;
  background-color: #28a745;
  color: #fff;
}
.btn-daftar:hover { transform: translateY(-3px); box-shadow: 0 0 25px rgba(91, 110, 245, 0.8); }
.btn-daftar .neon-border {
  content: "";
  position: absolute;
  top: -3px;
  left: -3px;
  right: -3px;
  bottom: -3px;
  border-radius: 14px;
  background: linear-gradient(45deg, #00ff88, #5b6ef5, #ffc107, #ff0080, #00ff88);
  background-size: 400%;
  z-index: -1;
  animation: spinNeon 5s linear infinite;
  filter: blur(5px);
}
@keyframes spinNeon {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

.section-bg { background-color: #e8edff; }
.section-title { color: #5b6ef5; transition: color 0.3s ease; }
.text-muted { transition: color 0.3s ease; }
.logo-navbar { height: 56px; }
.hero-img { max-width: 95%; transform: scale(1.05); }


.hero-headline { min-height: 3.2rem; }
.typed { font-weight: 800; }
.caret {
  display: inline-block;
  margin-left: 6px;
  opacity: 0.9;
  animation: blinkCaret 1s steps(1) infinite;
  transform: translateY(-4px);
  color: rgba(255,255,255,0.95);
}
@keyframes blinkCaret { 0% { opacity: 1; } 50% { opacity: 0; } 100% { opacity: 1; } }

</style>
