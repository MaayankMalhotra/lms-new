@extends('admin.layouts.app')

@section('content')
<div class="px-3">
    <section class="bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-gray-700 mb-4">Chat System</h1>
        <div class="row">
            <div class="col-md-4 user-list">
                @if(auth()->user()->role == '2') <!-- Teacher -->
                    <h3 class="text-lg font-bold text-gray-700 mb-3">Students Chats</h3>
                    <div class="list-group" id="student-list">
                        @foreach($students as $student)
                            <div class="list-group-item d-flex align-items-center p-2 mb-2 rounded-lg shadow-sm border border-gray-200 hover:bg-blue-50 transition-colors" data-student-id="{{ $student->id }}">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold text-sm">{{ substr($student->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <!-- Student Name -->
                                <div class="flex-grow ml-2">
                                    <a href="#" onclick="loadChat({{ $student->id }})" class="text-gray-800 font-medium text-sm hover:text-blue-600 transition-colors leading-tight">{{ $student->name }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="col-md-8">
                <div class="chat-container">
                    <!-- Chat Header with Teacher/Student Name -->
                    <div class="chat-header">
                        @if($errorMessage)
                            {{ $errorMessage }}
                        @elseif(auth()->user()->role == '3' && $selectedReceiverId && $teachers->isNotEmpty())
                            Chatting with: {{ $teachers->first()->name }}
                        @elseif(auth()->user()->role == '2' && $students->isNotEmpty())
                            <span id="chat-receiver-name">
                                Chatting with: {{ $students->first()->name }}
                            </span>
                        @else
                            No one to chat with
                        @endif
                    </div>
                    <div id="chat-box" class="chat-box"></div>
                    <div id="error-message" class="text-red-500 text-center mb-2" style="display: none;"></div>
                    <form id="message-form" class="chat-form">
                        @csrf
                        <input type="hidden" id="receiver_id">
                        <div class="input-group">
                            <textarea id="message" class="form-control" placeholder="Type your message...." required></textarea>
                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .chat-container {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    .chat-header {
        background-color: #007bff;
        color: #fff;
        padding: 15px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        font-size: 1.2rem;
        font-weight: 500;
    }
    .chat-box {
        height: 400px;
        overflow-y: auto;
        padding: 20px;
        background-color: #f1f3f5;
    }
    .chat-message {
        margin-bottom: 15px;
        display: flex;
        align-items: flex-start;
    }
    .chat-message.sent {
        justify-content: flex-end;
    }
    .chat-message.received {
        justify-content: flex-start;
    }
    .chat-message .message-bubble {
        max-width: 70%;
        padding: 10px 15px;
        border-radius: 15px;
        position: relative;
    }
    .chat-message.sent .message-bubble {
        background-color: #007bff;
        color: #fff;
        border-bottom-right-radius: 5px;
    }
    .chat-message.received .message-bubble {
        background-color: #e9ecef;
        color: #333;
        border-bottom-left-radius: 5px;
    }
    .chat-message .message-sender {
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 5px;
    }
    .chat-form {
        padding: 15px;
        background-color: #fff;
    }
    .chat-form .input-group {
        display: flex;
        align-items: center;
    }
    .chat-form textarea {
        resize: none;
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 10px;
        height: 40px;
        font-size: 0.9rem;
        color: #6c757d;
        flex: 1;
        min-width: 0;
    }
    .chat-form textarea::placeholder {
        color: #6c757d;
    }
    .chat-form button {
        border-radius: 5px;
        padding: 8px 20px;
        font-size: 0.9rem;
        margin-left: 10px;
        background-color: #007bff;
        border-color: #007bff;
    }
    .chat-form button:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
    .user-list .list-group-item {
        border: none;
        border-radius: 8px;
        margin-bottom: 5px;
        background-color: #fff;
        transition: background-color 0.2s;
    }
    .user-list .list-group-item:hover {
        background-color: #f1f3f5;
    }
</style>

<script>
    let receiverId = null;
    let receiverName = '';

    // Agar user student ya teacher hai aur selectedReceiverId set hai, toh automatically load karo
    @if($selectedReceiverId)
        receiverId = {{ $selectedReceiverId }};
        document.getElementById('receiver_id').value = receiverId;
        fetchMessages();
    @endif

    function loadChat(id) {
        receiverId = id;
        document.getElementById('receiver_id').value = id;

        // Update chat header with selected student's name (for teacher)
        @if(auth()->user()->role == '2')
            const student = @json($students->keyBy('id'));
            receiverName = student[id] ? student[id].name : 'Unknown';
            document.getElementById('chat-receiver-name').innerText = `Chatting with: ${receiverName}`;

            // Highlight the active student
            document.querySelectorAll('.list-group-item').forEach(item => {
                item.classList.remove('bg-blue-100', 'border-blue-300');
            });
            const activeStudent = document.querySelector(`.list-group-item[data-student-id="${id}"]`);
            if (activeStudent) {
                activeStudent.classList.add('bg-blue-100', 'border-blue-300');
            }
        @endif

        fetchMessages();
    }

    function fetchMessages() {
        if (!receiverId) return;
        fetch(`/messages/${receiverId}`)
            .then(response => response.json())
            .then(messages => {
                let chatBox = document.getElementById('chat-box');
                chatBox.innerHTML = '';
                messages.forEach(msg => {
                    const isSent = msg.sender_id === {{ auth()->id() }};
                    chatBox.innerHTML += `
                        <div class="chat-message ${isSent ? 'sent' : 'received'}">
                            <div class="message-bubble">
                                <div class="message-sender">${isSent ? 'You' : 'Them'}</div>
                                ${msg.message}
                            </div>
                        </div>
                    `;
                });
                chatBox.scrollTop = chatBox.scrollHeight;
            })
            .catch(error => {
                console.error('Error fetching messages:', error);
            });
    }

    document.getElementById('message-form').addEventListener('submit', function(e) {
        e.preventDefault();
        let message = document.getElementById('message').value;
        let errorMessage = document.getElementById('error-message');

        if (!receiverId) {
            errorMessage.innerText = 'Please select a user to chat with.';
            errorMessage.style.display = 'block';
            return;
        }

        errorMessage.style.display = 'none';

        const url = `/message/send?receiver_id=${receiverId}&message=${encodeURIComponent(message)}`;

        fetch(url, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(response => response.json())
        .then(data => {
            if (data.status === 'Error') {
                errorMessage.innerText = data.message;
                errorMessage.style.display = 'block';
                return;
            }

            document.getElementById('message').value = '';
            fetchMessages();

            @if(auth()->user()->role == '2')
                if (data.status === 'Message Sent!' && data.receiver_id) {
                    const studentElement = document.querySelector(`.list-group-item[data-student-id="${data.receiver_id}"]`);
                    if (studentElement) {
                        studentElement.remove();
                    }

                    const studentList = document.getElementById('student-list');
                    if (studentList.children.length === 0) {
                        document.getElementById('chat-receiver-name').innerText = 'No one to chat with';
                        document.getElementById('chat-box').innerHTML = '';
                    }
                }
            @endif
        }).catch(error => {
            console.error('Error sending message:', error);
            errorMessage.innerText = 'Failed to send message. Please try again.';
            errorMessage.style.display = 'block';
        });
    });

    Echo.channel(`chat.{{ auth()->id() }}`)
        .listen('MessageSent', (e) => {
            if (e.message.sender_id === receiverId || e.message.receiver_id === receiverId) {
                fetchMessages();
            }
        });
</script>
@endsection