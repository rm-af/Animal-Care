<template>
  <div class="wrapper">
    <div class="card">

      <!-- LOGO + BRAND -->
      <div class="brand">
        <img src="/animal.png" alt="logo" class="logo" />
        <h5 class="title-brand">
          Animal <span class="highlight">Care</span>
        </h5>
      </div>

      <h2 class="payment-title">Pembayaran QRIS</h2>

      <div class="form">
        <label>Jumlah Pembayaran (Rp)</label>

        <input
          v-model="amount"
          type="number"
          placeholder="Masukkan nominal"
        />

        <button
          @click="payNow"
          :disabled="loading"
        >
          <span v-if="!loading">Bayar Sekarang</span>
          <span v-else>Memproses...</span>
        </button>
      </div>

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
    const snap = document.createElement("script");
    snap.src = "https://app.sandbox.midtrans.com/snap/snap.js";
    snap.setAttribute("data-client-key", "Mid-client-Z2zhCv8v5_sbiKsW");
    document.body.appendChild(snap);
  },

  methods: {
    async payNow() {
      if (!this.amount || this.amount < 1000) {
        alert("Minimal pembayaran adalah 1000");
        return;
      }

      this.loading = true;

      try {
        const response = await axios.post("http://127.0.0.1:8000/api/payment", {
          amount: this.amount,
          method: "qris",
        });

        const token = response.data.snapToken;

        window.snap.pay(token, {
          onSuccess: () => alert("Pembayaran berhasil!"),
          onPending: () => alert("Menunggu pembayaran..."),
          onError: () => alert("Pembayaran gagal"),
          onClose: () => alert("Anda menutup pembayaran"),
        });

      } catch (err) {
        console.error(err);
        alert("Gagal membuat transaksi.");
      }

      this.loading = false;
    },
  },
};
</script>

<style scoped>
/* ======================== */
/*       WRAPPER + BG       */
/* ======================== */
.wrapper {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;

  /* BACKGROUND PREMIUM */
  background: radial-gradient(circle at top left, #7c89ff, #4a54e0 40%, #2e3ab5);
  background-attachment: fixed;

  padding: 20px;
}

/* ======================== */
/*         CARD UI          */
/* ======================== */
.card {
  width: 100%;
  max-width: 430px;
  background: rgba(255, 255, 255, 0.85);
  padding: 28px;
  border-radius: 22px;
  box-shadow: 0 18px 40px rgba(0, 0, 0, 0.22);
  backdrop-filter: blur(18px);
  animation: fadeIn .55s ease-out;
}

/* ======================== */
/*        BRANDING          */
/* ======================== */
.brand {
  text-align: center;
  margin-bottom: 12px;
}

.logo {
  width: 70px;
  height: 70px;
  object-fit: contain;
  margin-bottom: 5px;
  filter: drop-shadow(0 3px 6px rgba(0,0,0,0.25));
}

.title-brand {
  font-size: 20px;
  margin-top: 0;
  font-weight: 700;
  color: #2b2b2b;
}

.title-brand .highlight {
  color: #f7b731;
}

/* ======================== */
/*      PAYMENT TITLE       */
/* ======================== */
.payment-title {
  font-size: 22px;
  text-align: center;
  margin-bottom: 22px;
  font-weight: 700;
  color: #2b2b2b;
}

/* ======================== */
/*         FORM UI          */
/* ======================== */
.form label {
  font-size: 14px;
  font-weight: 600;
  color: #4a4a4a;
  margin-bottom: 6px;
  display: block;
}

.form input {
  width: 100%;
  padding: 14px;
  border-radius: 12px;
  border: 1.6px solid #c8ceff;
  font-size: 15px;
  margin-bottom: 20px;
  transition: .25s ease;
}

.form input:focus {
  border-color: #5a6dfc;
  box-shadow: 0 0 10px rgba(90, 109, 252, .45);
}

/* ======================== */
/*        BUTTON UI         */
/* ======================== */
.form button {
  width: 100%;
  padding: 14px;
  border: none;
  border-radius: 12px;
  background: #5a6dfc;
  color: #fff;
  font-weight: 600;
  font-size: 16px;
  cursor: pointer;
  transition: .25s ease;
}

.form button:hover {
  background: #4f60f2;
  transform: translateY(-2px);
}

.form button:disabled {
  background: #9ba3ff;
  cursor: not-allowed;
  transform: none;
}

/* ======================== */
/*        ANIMATIONS        */
/* ======================== */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(.93);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
</style>
