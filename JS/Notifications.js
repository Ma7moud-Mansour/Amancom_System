document.querySelectorAll(".mark-read").forEach((button) => {
    button.addEventListener("click", (e) => {
      const notification = e.target.closest(".notification");
      notification.style.opacity = "0.5";
      button.innerText = "✔️ مقروء";
      button.disabled = true;
    });
  });
  
  document.querySelectorAll(".delete").forEach((button) => {
    button.addEventListener("click", (e) => {
      const notification = e.target.closest(".notification");
      notification.remove();
    });
  });
  
  document.getElementById("filterNotifications").addEventListener("change", (e) => {
    const value = e.target.value;
    document.querySelectorAll(".notification").forEach((notification) => {
      if (value === "all" || notification.querySelector("p").innerText.includes(value)) {
        notification.style.display = "flex";
      } else {
        notification.style.display = "none";
      }
    });
  });
  