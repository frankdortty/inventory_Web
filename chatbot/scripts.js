// script.js - Frontend-only Chatbot (Not Recommended for Production)

// DOM Elements
const chatContainer = document.getElementById("chat");
const inputField = document.getElementById("input");
const sendButton = document.getElementById("button");

// Display a message in the chat
function displayMessage(role, content) {
  const messageDiv = document.createElement("div");
  messageDiv.className = `message ${role}`;

  const avatar = document.createElement("div");
  avatar.className = "avatar";

  const text = document.createElement("div");
  text.className = "text";
  text.textContent = content;

  messageDiv.append(avatar, text);
  chatContainer.appendChild(messageDiv);
  chatContainer.scrollTop = chatContainer.scrollHeight;
  
  return text; // Return text element for later updates
}

// Get chatbot response (Frontend-only version)
async function getChatbotResponse(userInput) {
  const API_URL = "https://api.deepseek.com/v1/chat/completions";
  const API_KEY = "sk-faef1ef422d14153b75317ebd2f81f16"; // 🔴 WARNING: This exposes your key!

  try {
    const response = await fetch(API_URL, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${API_KEY}`
      },
      body: JSON.stringify({
        model: "deepseek-chat",
        messages: [
          { role: "system", content: "You are a helpful assistant." },
          { role: "user", content: userInput }
        ],
        temperature: 0.7
      })
    });

    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
    
    const data = await response.json();
    return data.choices[0].message.content;
  } catch (error) {
    console.error("API Error:", error);
    return "Sorry, I encountered an error. Please try again.";
  }
}

// Handle message sending
async function handleSendMessage() {
  const userInput = inputField.value.trim();
  if (!userInput) return;

  // Display user message and clear input
  displayMessage("user", userInput);
  inputField.value = "";

  // Display typing indicator and get reference
  const botResponseElement = displayMessage("bot", "Typing...");

  try {
    const botResponse = await getChatbotResponse(userInput);
    botResponseElement.textContent = botResponse;
  } catch (error) {
    botResponseElement.textContent = "Error connecting to the chatbot service.";
    console.error("Chat Error:", error);
  }
}

// Event Listeners
sendButton.addEventListener("click", handleSendMessage);
inputField.addEventListener("keypress", (e) => {
  if (e.key === "Enter") handleSendMessage();
});