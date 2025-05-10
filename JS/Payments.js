document.getElementById("addPaymentBtn").onclick = function () {
    document.getElementById("paymentModal").classList.remove("hidden");
  };
  document.getElementById("cancelPaymentModal").onclick = function () {
    document.getElementById("paymentModal").classList.add("hidden");
  };
  