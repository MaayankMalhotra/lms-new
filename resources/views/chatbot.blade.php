<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thinkchamp Chatbot</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .chat-container {
            width: 100%;
            max-width: 800px;
            height: 90vh;
            background: #fff;
            display: flex;
            flex-direction: column;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .chat-header {
            padding: 15px;
            background: #10a37f;
            color: #fff;
            font-weight: bold;
            font-size: 18px;
            text-align: center;
        }
        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .message {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 12px;
            line-height: 1.4;
            font-size: 15px;
            white-space: pre-wrap;
        }
        .user-message {
            background: #10a37f;
            color: white;
            align-self: flex-end;
        }
        .bot-message {
            background: #f1f1f1;
            color: #333;
            align-self: flex-start;
        }
        .chat-input {
            display: flex;
            border-top: 1px solid #ddd;
        }
        .chat-input input {
            flex: 1;
            padding: 15px;
            border: none;
            outline: none;
            font-size: 15px;
        }
        .chat-input button {
            background: #10a37f;
            border: none;
            padding: 0 20px;
            color: white;
            font-size: 15px;
            cursor: pointer;
        }
        .chat-input button:hover {
            background: #0e8a68;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">💬 Thinkchamp Chatbot (Qwen2)</div>
        <div class="chat-messages" id="chat-messages"></div>
        <div class="chat-input">
            <input type="text" id="message" placeholder="Type your message..." autocomplete="off">
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>

    <script>
        async function sendMessage() {
            const input = document.getElementById("message");
            const chatMessages = document.getElementById("chat-messages");
            const userMessage = input.value.trim();

            if (!userMessage) return;

            // Show user message
            const userDiv = document.createElement("div");
            userDiv.className = "message user-message";
            userDiv.innerText = userMessage;
            chatMessages.appendChild(userDiv);
            input.value = "";
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // Call backend API
            const responseDiv = document.createElement("div");
            responseDiv.className = "message bot-message";
            responseDiv.innerText = "Typing...";
            chatMessages.appendChild(responseDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;

            try {
                const res = await fetch("/chat-bot/send", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ message: userMessage })
                });
                const data = await res.json();
                responseDiv.innerText = data.reply || "No response";
            } catch (e) {
                responseDiv.innerText = "⚠️ Error: Could not reach chatbot";
            }

            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        document.getElementById("message").addEventListener("keypress", function (e) {
            if (e.key === "Enter") sendMessage();
        });
    </script>
</body>
</html>
