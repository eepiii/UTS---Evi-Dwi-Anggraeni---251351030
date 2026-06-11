<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Smart Support - Live Streaming</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-900 text-gray-100 flex h-screen antialiased">

    <div class="w-64 bg-gray-950 p-4 hidden md:flex flex-col justify-between border-r border-gray-800">
        <div>
            <h2 class="text-lg font-bold mb-4 tracking-wide text-indigo-400">⚡ AI Support</h2>
            <div id="chat-history" class="space-y-2 overflow-y-auto max-h-[80vh]">
                @forelse($messages as $msg)
                <div class="p-2 hover:bg-gray-800 rounded cursor-pointer transition text-sm truncate text-gray-400">
                    {{ $msg->prompt }}
                </div>
                @empty
                <p class="text-xs text-gray-600 id='no-history'">Belum ada percakapan.</p>
                @endforelse
            </div>
        </div>
        <div class="text-xs text-gray-500 text-center">Laravel 13 AI Core</div>
    </div>

    <div class="flex-1 flex flex-col justify-between bg-gray-900">
        <header class="bg-gray-950/50 backdrop-blur p-4 border-b border-gray-800 flex justify-between items-center">
            <span class="font-medium text-gray-200">AI Assistant (Streaming)</span>
            <span class="text-xs px-2 py-1 bg-emerald-500/20 text-emerald-400 rounded-full font-mono">Live</span>
        </header>

        <div id="chat-container" class="flex-1 overflow-y-auto p-6 space-y-6 max-w-4xl w-full mx-auto">
            @if($messages->isEmpty())
            <div id="welcome-screen" class="flex flex-col items-center justify-center h-full text-center space-y-3">
                <div class="p-4 bg-indigo-600/10 rounded-full text-indigo-400 text-3xl">🤖</div>
                <h3 class="text-xl font-semibold">Ada yang bisa saya bantu hari ini?</h3>
                <p class="text-sm text-gray-400 max-w-md">Tanyakan apa saja, AI akan menjawab secara real-time.</p>
            </div>
            @else
            @foreach($messages->reverse() as $msg)
            <div class="flex items-start justify-end space-x-3">
                <div class="bg-indigo-600 text-white p-3 rounded-2xl rounded-tr-none max-w-lg text-sm shadow-md">
                    {{ $msg->prompt }}
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-xs font-bold text-white shrink-0 shadow-inner">AI</div>
                <div class="bg-gray-800 p-4 rounded-2xl rounded-tl-none max-w-2xl text-sm leading-relaxed text-gray-300 shadow-md">
                    {!! nl2br(e($msg->response)) !!}
                </div>
            </div>
            @endforeach
            @endif
        </div>

        <footer class="p-4 bg-gray-950/30 border-t border-gray-800">
            <div class="max-w-4xl mx-auto flex items-center space-x-3">
                <input type="text" id="user-input" required placeholder="Ketik pertanyaan Anda di sini..."
                    class="flex-1 bg-gray-800 text-gray-100 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-gray-700 placeholder-gray-500 text-sm transition">
                <button type="button" id="send-btn"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-5 py-3 rounded-xl transition text-sm shadow-lg">
                    Kirim
                </button>
            </div>
        </footer>
    </div>

    <script>
        const sendBtn = document.getElementById('send-btn');
        const userInput = document.getElementById('user-input');
        const chatContainer = document.getElementById('chat-container');
        const welcomeScreen = document.getElementById('welcome-screen');

        sendBtn.addEventListener('click', startStreaming);
        userInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') startStreaming();
        });

        function startStreaming() {
            const promptText = userInput.value.trim();
            if (!promptText) return;

            if (welcomeScreen) welcomeScreen.remove();

            // 1. Tampilkan Chat User
            const userBubble = `
            <div class="flex items-start justify-end space-x-3">
                <div class="bg-indigo-600 text-white p-3 rounded-2xl rounded-tr-none max-w-lg text-sm shadow-md">
                    ${escapeHtml(promptText)}
                </div>
            </div>`;
            chatContainer.insertAdjacentHTML('beforeend', userBubble);

            // 2. Tampilkan Loading AI
            const aiResponseId = 'ai-' + Date.now();
            const aiBubble = `
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-xs font-bold text-white shrink-0 shadow-inner">AI</div>
                <div id="${aiResponseId}" class="bg-gray-800 p-4 rounded-2xl rounded-tl-none max-w-2xl text-sm leading-relaxed text-gray-300 shadow-md">
                    <span class="animate-pulse text-gray-500">Berpikir...</span>
                </div>
            </div>`;
            chatContainer.insertAdjacentHTML('beforeend', aiBubble);
            chatContainer.scrollTop = chatContainer.scrollHeight;
            userInput.value = '';

            const targetDiv = document.getElementById(aiResponseId);

            // 3. Ambil data menggunakan Fetch API biasa (Bebas dari masalah block/empty stream)
            fetch('/chat/stream', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        prompt: promptText
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        targetDiv.innerHTML = `<span class="text-red-400">${data.error}</span>`;
                        return;
                    }

                    targetDiv.innerHTML = ''; // Hapus tulisan "Berpikir..."

                    // 4. Jalankan Efek Mengetik di Sisi Browser (Typewriter Effect)
                    let rawText = data.text;
                    let index = 0;

                    function typeWriter() {
                        if (index < rawText.length) {
                            let char = rawText.charAt(index);
                            // Jika ada baris baru, ubah ke <br>
                            targetDiv.innerHTML += char === '\n' ? '<br>' : char;
                            index++;
                            chatContainer.scrollTop = chatContainer.scrollHeight;
                            setTimeout(typeWriter, 15); // Kecepatan mengetik (15ms per karakter)
                        }
                    }
                    typeWriter();
                })
                .catch(error => {
                    console.error(error);
                    targetDiv.innerHTML = `<span class="text-red-400">Gagal memuat respon. Silakan periksa koneksi atau API Key Anda.</span>`;
                });
        }

        // Helper untuk keamanan XSS
        function escapeHtml(text) {
            return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
        }
    </script>
</body>

</html>