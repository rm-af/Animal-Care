<template>
  <div class="payment-container">
    <h2 class="title">Pembayaran QRIS</h2>

    <div class="card">
      <label class="label">Jumlah Pembayaran (Rp)</label>
      <input
        type="number"
        v-model="amount"
        placeholder="Contoh: 25000"
        class="input"
      />

      <button @click="payNow" :disabled="loading" class="pay-btn">
        <span v-if="!loading">Bayar Sekarang</span>
        <span v-else>Memproses...</span>
      </button>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "PaymentPage",

  data() {
    return {
      amount: "",
      loading: false,
    };
  },

  mounted() {
    // Inject Snap JS
    const script = document.createElement("script");
    script.src = "https://app.sandbox.midtrans.com/snap/snap.js";
    script.setAttribute("data-client-key", "Mid-client-Z2zhCv8v5_sbiKsW");
    document.body.appendChild(script);
  },

  methods: {
    async payNow() {
      if (!this.amount || this.amount < 1000) {
        alert("Masukkan nominal yang valid (minimal 1000)");
        return;
      }

      this.loading = true;

      try {
        const response = await axios.post("http://127.0.0.1:8000/api/payment", {
          amount: this.amount,
          method: "qris",
        });

        const snapToken = response.data.snapToken;

        window.snap.pay(snapToken, {
          onSuccess: (result) => {
            console.log(result);
            alert("Pembayaran berhasil! 🎉");
          },
          onPending: (result) => {
            console.log(result);
            alert("Menunggu pembayaran...");
          },
          onError: (result) => {
            console.error(result);
            alert("Pembayaran gagal.");
          },
          onClose: () => {
            alert("Anda menutup pembayaran sebelum selesai.");
          },
        });
      } catch (err) {
        console.error(err);
        alert("Terjadi kesalahan membuat transaksi");
      }

      this.loading = false;
    },
  },
};
</script>

<style scoped>
.payment-container {
  max-width: 450px;
  margin: 40px auto;
  padding: 15px;
}

.title {
  text-align: center;
  font-weight: bold;
  margin-bottom: 20px;
}

.card {
  padding: 20px;
  border-radius: 8px;
  background: #ffffff;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.label {
  font-weight: bold;
  margin-bottom: 5px;
  display: block;
}

.input {
  width: 100%;
  padding: 12px;
  border-radius: 6px;
  border: 1px solid #ccc;
  margin-bottom: 20px;
}

.pay-btn {
  width: 100%;
  padding: 12px;
  border: none;
  background: #5b6ef5;
  color: white;
  border-radius: 6px;
  font-size: 16px;
  cursor: pointer;
}

.pay-btn:disabled {
  background: #9ea7ff;
  cursor: not-allowed;
}

.pay-btn:hover {
  background: #4b5ed9;
}
</style>
