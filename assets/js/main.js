var selectedKategori = []
var modalClosed = false

document.addEventListener("DOMContentLoaded", function () {
  var modal = new bootstrap.Modal(document.getElementById("kategoriModal"))

  if (
    localStorage.getItem("modalClosed") === null ||
    localStorage.getItem("modalClosed") === "false"
  ) {
    modal.show()
  }

  // set time
  const travelDateInput = document.getElementById("travelDate")
  const today = new Date().toISOString().split("T")[0]
  travelDateInput.setAttribute("min", today)

  document.querySelectorAll(".card.card-modal").forEach((item) => {
    item.addEventListener("click", function () {
      var kategori = this.getAttribute("data-kategori")
      if (selectedKategori.includes(kategori)) {
        selectedKategori = selectedKategori.filter((item) => item !== kategori)
        this.classList.remove("selected")
      } else {
        selectedKategori.push(kategori)
        this.classList.add("selected")
      }
      toggleSimpanButton()
    })
  })

  function toggleSimpanButton() {
    var simpanButton = document.getElementById("simpanKategori")
    if (selectedKategori.length > 0) {
      simpanButton.removeAttribute("disabled")
    } else {
      simpanButton.setAttribute("disabled", "disabled")
    }
  }

  document
    .getElementById("simpanKategori")
    .addEventListener("click", function () {
      document.getElementById("selectedKategori").value =
        selectedKategori.join(",")
      modal.hide()
      modalClosed = true
      localStorage.setItem("modalClosed", "true")
    })
})

document
  .getElementById("logout-link")
  .addEventListener("click", function (event) {
    localStorage.removeItem("modalClosed")
  })

document.addEventListener("DOMContentLoaded", function () {
  const navbar = document.querySelector(".navbar")

  window.addEventListener("scroll", function () {
    if (window.scrollY > 50) {
      navbar.classList.add("navbar-scroll")
    } else {
      navbar.classList.remove("navbar-scroll")
    }
  })
  var detailButtons = document.querySelectorAll(".lihat-detail")
  var modal = new bootstrap.Modal(document.getElementById("detailModal"))

  detailButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      var nama = this.getAttribute("data-nama")
      var deskripsi = this.getAttribute("data-deskripsi")
      var biaya = this.getAttribute("data-biaya")
      var gambar = this.getAttribute("data-gambar")
      var mapSrc = this.getAttribute("data-lokasi")
      var rating = parseFloat(this.getAttribute("data-rating")) // Mengambil data rating dan mengubahnya menjadi angka float

      document.getElementById("modal-nama").innerText = nama
      document.getElementById("modal-deskripsi").innerText = deskripsi
      document.getElementById("modal-biaya").innerText = biaya
      document.getElementById("modal-img").src = gambar
      document.getElementById("modal-map").src = mapSrc

      // Membuat bintang rating
      var ratingStarsContainer = document.getElementById("rating-stars")
      ratingStarsContainer.innerHTML = "" // Membersihkan kontainer bintang sebelum membuat yang baru

      for (var i = 1; i <= 5; i++) {
        var star = document.createElement("span")
        star.classList.add("star")
        if (i <= rating) {
          star.classList.add("active") // Menandai bintang yang sudah diisi (active)
        }
        star.innerHTML = "&#9733;" // Karakter bintang (asterisk)
        ratingStarsContainer.appendChild(star)
      }

      modal.show()
    })
  })
})
