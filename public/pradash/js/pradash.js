/* Pradash - Bootstrap 5 HTML Admin Template
 * ==============================================================
 * Author: M. Fajar Pratama
 * Created: 2026
 * Copyright 2026 Pradash Admin. All rights reserved.
 * For inquiries or purchase:
 * Website: https://praport.netlify.app/
 * Email:  xfajarpratamaaa@gmail.com
 * ===============================================================
 */

/*
 ** ==== SIDEBAR ==========================================
 ** Pradash Admin
 */
window.addEventListener("DOMContentLoaded", (event) => {
  const activeNavLink = document.querySelector(
    ".sb-sidenav-menu .nav-link.active",
  );

  if (activeNavLink) {
    activeNavLink.scrollIntoView({
      behavior: "smooth",
      block: "center",
    });
  }
});

/*
 ** ==== MODAL TABLE IMAGE PREVIEW ===========================================
 ** Pradash Admin
 */
function previewImage(src) {
  document.getElementById("modalImage").src = src;
}

/*
 ** ==== SWEETALERT ==========================================
 ** Pradash Admin
 */
// 1. Success Alert
function showSuccess() {
  Swal.fire({
    title: "Good job!",
    text: "Data has been saved successfully.",
    icon: "success",
    confirmButtonColor: "#162d4d",
  });
}

// 2. Error Alert
function showError() {
  Swal.fire({
    title: "Oops...",
    text: "Something went wrong! Please try again.",
    icon: "error",
    confirmButtonColor: "#ef4444",
  });
}

// 3. Warning Alert
function showWarning() {
  Swal.fire({
    title: "Attention!",
    text: "Check your input data carefully.",
    icon: "warning",
    confirmButtonColor: "#f59e0b",
  });
}

// 4. Info Alert
function showInfo() {
  Swal.fire({
    title: "Did you know?",
    text: "You can customize these alerts very easily.",
    icon: "info",
    confirmButtonColor: "#162d4d",
  });
}

// 5. Confirmation Dialog
function showConfirm() {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#162d4d",
    cancelButtonColor: "#8898aa",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: "Deleted!",
        text: "Your record has been deleted.",
        icon: "success",
        confirmButtonColor: "#162d4d",
      });
    }
  });
}

// 6. Auto Close Timer
function showTimer() {
  let timerInterval;
  Swal.fire({
    title: "Auto close alert!",
    html: "I will close in <b></b> milliseconds.",
    timer: 2000,
    timerProgressBar: true,
    didOpen: () => {
      Swal.showLoading();
      const b = Swal.getHtmlContainer().querySelector("b");
      timerInterval = setInterval(() => {
        b.textContent = Swal.getTimerLeft();
      }, 100);
    },
    willClose: () => {
      clearInterval(timerInterval);
    },
  });
}

// 7. Custom Image Alert
function showCustomImage() {
  Swal.fire({
    title: "Pradash Admin!",
    text: "Custom images are supported.",
    imageUrl:
      "https://images.unsplash.com/photo-1614850523296-d8c1af93d400?w=400",
    imageWidth: 400,
    imageHeight: 200,
    imageAlt: "Custom image",
    confirmButtonColor: "#162d4d",
  });
}

/*
 ** ==== TOAST ==========================================
 ** Pradash Admin
 */
function showToast(toastId) {
  const toastElement = document.getElementById(toastId);
  if (toastElement) {
    const toast = new bootstrap.Toast(toastElement, {
      autohide: true,
      delay: 5000,
    });
    toast.show();
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const toastElList = [].slice.call(document.querySelectorAll(".toast"));
  toastElList.map(function (toastEl) {
    return new bootstrap.Toast(toastEl);
  });
});

/*
 ** ==== PREVIEW ADD IMAGE ==========================================
 ** Pradash Admin
 */
function previewAddImage(event) {
  const input = event.target;
  const preview = document.getElementById("previewImg");
  const placeholder = document.getElementById("placeholderText");
  const container = document.getElementById("imageContainer");

  if (input.files && input.files[0]) {
    const reader = new FileReader();

    reader.onload = function (e) {
      preview.src = e.target.result;
      preview.classList.remove("d-none");
      placeholder.classList.add("d-none");
      container.style.borderStyle = "solid";
    };

    reader.readAsDataURL(input.files[0]);
  }
}

/*
 ** ==== PREVIEW EDIT IMAGE ==========================================
 ** Pradash Admin
 */
function previewEditImage(event) {
  const input = event.target;
  const preview = document.getElementById("previewImgEdit");

  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function (e) {
      preview.src = e.target.result;
      // Tambahkan efek kilat saat gambar berubah
      preview.style.opacity = "0";
      setTimeout(() => {
        preview.style.opacity = "1";
      }, 100);
    };
    reader.readAsDataURL(input.files[0]);
  }
}
