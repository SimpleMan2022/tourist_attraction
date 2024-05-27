var chatHistory = []
var dataWisataElement = document.getElementsByClassName("list-wisata")[0]
var dataWisataJSON = dataWisataElement.innerText
var dataWisata = JSON.parse(dataWisataJSON)

document.getElementById("openChatBtn").addEventListener("click", function () {
  var chatModal = new bootstrap.Modal(document.getElementById("chatModal"))
  chatModal.show()
})

document
  .getElementById("chatInput")
  .addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
      sendMessage()
    }
  })

document.getElementById("sendMessageBtn").addEventListener("click", sendMessage)

function sendMessage() {
  var message = document.getElementById("chatInput").value
  if (message.trim() === "") return

  appendMessage("user", message)

  chatHistory.push({ sender: "user", message: message })
  var prompt = chatHistory
    .map((chat) => (chat.sender === "user" ? chat.message : ""))
    .join(" ")
  prompt =
    "Prompt: Kamu adalah seorang asistan yang memberikan informasi tentang data data objek wisata. " +
    dataWisata.data
      .map(
        (wisata) =>
          `${wisata.nama},deskripsi:${wisata.deskripsi},biaya:${wisata.biaya},lokasi:${wisata.lokasi}`
      )
      .join(", ") +
    " pertanyaan: " +
    prompt
  console.log(prompt)
  // Kirim pesan ke API
  $.ajax({
    url:
      "https://api.ngodingaja.my.id/api/gpt?prompt=" +
      encodeURIComponent(prompt),
    method: "GET",
    success: function (response) {
      var botResponse = response.hasil

      // Check if the response is similar to previous responses
      if (isDuplicateResponse(botResponse)) {
        botResponse = rephraseResponse(botResponse)
      }

      appendMessage("bot", botResponse)

      chatHistory.push({ sender: "bot", message: botResponse })
      console.log(chatHistory)
      console.log(prompt)
    },
    error: function () {
      appendMessage("bot", "Maaf, terjadi kesalahan dalam mengambil respons.")
      chatHistory.push({
        sender: "bot",
        message: "Maaf, terjadi kesalahan dalam mengambil respons.",
      })
    },
  })

  document.getElementById("chatInput").value = ""
}

function appendMessage(sender, message) {
  var chatContainer = document.querySelector(".chat-container")
  var newMessage = document.createElement("div")

  if (sender === "user") {
    newMessage.classList.add("user-message")
    newMessage.innerText = message
  } else if (sender === "bot") {
    newMessage.classList.add("chat-message")
    newMessage.innerHTML = `<i class="fas fa-robot chat-icon fa-lg"></i>${message}`
  }

  chatContainer.appendChild(newMessage)
  chatContainer.scrollTop = chatContainer.scrollHeight
}

function isDuplicateResponse(response) {
  return chatHistory.some(
    (chat) => chat.sender === "bot" && chat.message === response
  )
}

function rephraseResponse(response) {
  // Simple rephrase example, you can make this more complex
  return response + " (information has been updated)"
}
