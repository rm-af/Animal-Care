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
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
            <router-link to="/login" class="btn btn-success ms-2">Login</router-link>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <section class="py-5 text-white" style="background-color: #5b6ef5;">
      <div class="container text-center">
        <h2 class="fw-bold">Ayo Periksa Hewan Kesayanganmu</h2>
        <p class="lead">Cek kesehatan hewanmu agar tetap sehat dan happy</p>
        <button class="btn btn-success btn-lg mt-3">Daftar Antrian</button>
      </div>
    </section>
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
</style>
