<template>
  <div class="login-page">
    <div class="form-container">
      <h2>Form Login</h2>
      <input v-model="email" type="email" placeholder="Email" />
      <input v-model="password" type="password" placeholder="Password" />
      <button @click="login">Login</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LoginPage',
  data() {
    return {
      email: '',
      password: ''
    }
  },
  methods: {
    login() {
      alert(`Login dengan email: ${this.email}`)
    }
  }
}
</script>

<style scoped>
.login-page {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color: #eef3ff;
}
.form-container {
  background: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
  width: 300px;
}
input {
  width: 100%;
  padding: 8px;
  margin-top: 10px;
}
button {
  margin-top: 15px;
  width: 100%;
  background: #4a90e2;
  color: white;
  border: none;
  padding: 10px;
}
</style>
