<template>
  <!-- padding agar form tidak ketutup -->
  <div
    class="container-fluid vh-100 d-flex align-items-center justify-content-center text-white"
    style="background-color: #5b6ef5; padding-top: 80px;"
  >
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

    <div class="row w-100">
      <div class="col-md-6 d-flex flex-column justify-content-center align-items-start ps-5">
        <h2 class="fw-bold">Form Register</h2>
        <p>Silahkan mengisikan Form Register untuk membuat akun anda.</p>
      </div>

      <div class="col-md-6 d-flex justify-content-center">
        <div class="card p-4 shadow rounded-4" style="min-width: 350px;">
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
</template>

<script>
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
    };
  },
  methods: {
    handleSubmit() {
      console.log("Form Data:", this.form);
      alert("Registrasi berhasil!");
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
  height: 45px;
  width: auto;
}
</style>
