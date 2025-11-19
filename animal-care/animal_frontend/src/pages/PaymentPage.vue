<template>
  <div class="payment-container">
    <h2>Pembayaran</h2>

    <input 
      type="number" 
      v-model="amount" 
      placeholder="Masukkan jumlah pembayaran"
      class="input"
    />

    <button @click="payNow" class="pay-btn">
      Bayar Sekarang
    </button>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "PaymentPage",
  data() {
    return {
      amount: 0,
    };
  },
  methods: {
    async payNow() {
      try {
        const res = await axios.post("http://127.0.0.1:8000/payment", {
          amount: this.amount,
        });

        const snapToken = res.data.snapToken;

        window.snap.pay(snapToken, {
          onSuccess: (result) => {
            console.log("Success:", result);
            alert("Pembayaran berhasil!");
          },
          onPending: (result) => {
            console.log("Pending:", result);
            alert("Pembayaran pending.");
          },
          onError: (result) => {
            console.log("Error:", result);
            alert("Pembayaran gagal.");
          },
          onClose: () => {
            alert("Anda menutup popup tanpa menyelesaikan pembayaran.");
          },
        });

      } catch (error) {
        console.error("Gagal membuat transaksi:", error);
      }
    }
  }
};
</script>

<style>
.payment-container {
  max-width: 400px;
  margin: 40px auto;
}

.input {
  width: 100%;
  padding: 10px;
  margin-bottom: 15px;
}

.pay-btn {
  padding: 10px 20px;
  background-color: #4CAF50;
  border: none;
  color: white;
  cursor: pointer;
  width: 100%;
}

.pay-btn:hover {
  background-color: #45a049;
}
</style>
