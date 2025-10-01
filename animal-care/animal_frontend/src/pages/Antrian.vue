<template>
  <div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: #5b6ef5;">
      <div class="container">
        <a class="navbar-brand fw-bold text-white" href="#">Animal Care</a>
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
              <router-link to="/" class="nav-link text-white">Home</router-link>
            </li>
            <li class="nav-item me-4">
              <router-link to="/layanan" class="nav-link text-white">Layanan</router-link>
            </li>
            <li class="nav-item me-4">
              <router-link to="/hewan" class="nav-link text-white">Hewan</router-link>
            </li>
            <li class="nav-item me-4">
              <router-link to="/Antrian" class="nav-link text-white">Antrian</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/login" class="btn btn-success ms-3">Login</router-link>
            </li>
          </ul>
        </div>
      </div>
    </nav>

   
  </div>
</template>

<script>
export default {};
</script>

<style scoped>
.card img {
  height: 200px;
  object-fit: cover;
}

/* Active menu underline */
.nav-link.router-link-active,
.nav-link.router-link-exact-active {
  border-bottom: 2px solid #fff;
  font-weight: bold;
  transition: all 0.3s ease;
}

/* Hover underline effect */
.nav-link {
  position: relative;
  transition: all 0.3s ease;
}

.nav-link::after {
  content: "";
  position: absolute;
  width: 0%;
  height: 2px;
  bottom: 0;
  left: 0;
  background-color: #fff;
  transition: width 0.3s ease;
}

.nav-link:hover::after {
  width: 100%;
}
</style>
