<template>
  <div class="auth-container">
    <div class="card">
      <h2>Form Register</h2>
      <p>Silakan mengisi form Register untuk membuat akun anda.</p>
      <form @submit.prevent="handleRegister">
        <input type="text" v-model="namaLengkap" placeholder="Nama lengkap" required />
        <input type="text" v-model="role" placeholder="Role" required />
        <input type="email" v-model="email" placeholder="Email" required />
        <input type="password" v-model="password" placeholder="Password" required />
        <button type="submit">Daftar</button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: "Register",
  data() {
    return {
      namaLengkap: "",
      role: "",
      email: "",
      password: ""
    };
  },
  methods: {
    handleRegister() {
      alert(`Register: ${this.namaLengkap} - ${this.role} - ${this.email}`);
      // Nanti bisa dihubungkan ke API Laravel
    }
  }
};
</script>

<style scoped>
.auth-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: #4b4bb7;
}
.card {
  background: white;
  padding: 2rem;
  border-radius: 10px;
  width: 300px;
}
h2 {
  margin-bottom: 0.5rem;
}
p {
  font-size: 14px;
  margin-bottom: 1rem;
}
input {
  width: 100%;
  padding: 10px;
  margin-bottom: 0.8rem;
  border: 1px solid #ccc;
  border-radius: 5px;
}
button {
  width: 100%;
  padding: 10px;
  background: #0094c7;
  color: white;
  border: none;
  border-radius: 5px;
}
</style>
