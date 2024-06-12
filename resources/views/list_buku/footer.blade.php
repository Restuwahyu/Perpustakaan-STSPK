<footer class="footer1">
    2023 © Seminari Tinggi St. Paulus Kentungan <span class="d-none d-sm-inline-block">
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- <script src="https://cdn.jsdelivr.net/npm/botman-web-widget/build/js/chat.js"></script> --}}
<script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
<script src="{{ asset('storage/script.js') }}"></script>
{{-- <script>
    const bookCards = document.querySelectorAll('.card'); // Get all book card elements

    bookCards.forEach(card => {
        let entryTime = null; // Initialize entryTime for each card

        card.addEventListener('mouseover', () => {
            entryTime = Date.now(); // Store entryTime when entering card
        });

        card.addEventListener('mouseout', () => {
            const exitTime = Date.now();
            const timeSpent = Math.floor((exitTime - entryTime) /
                1000); // Calculate timeSpent in seconds
            const bookTitle = card.querySelector('.title')
                .textContent; // Get book title from title element
            console.log(`Time spent on "${bookTitle}" (book ${card.id}): ${timeSpent} seconds`);
        });
    });

    window.onload = function() {
        BotManWidget.init({
            frameEndpoint: '/chatbot',
            chatServer: '/chatbot',
            title: 'Perpustakaan Chatbot',
            mainColor: '#3367D6',
            bubbleBackground: '#3367D6',
            bubbleAvatarUrl: '/img/avatar.png',
            aboutText: 'Perpustakaan Chatbot',
            introMessage: "Halo! Saya adalah chatbot perpustakaan. Ada yang bisa saya bantu?",
        });
    };

    var botmanWidget = {
        // frameEndpoint: '/chatbot',
        chatServer: '/chatbot',
        title: 'Perpustakaan Chatbot',
        mainColor: '#3367D6',
        bubbleBackground: '#3367D6',
        aboutText: 'Perpustakaan Chatbot',
        introMessage: "Halo! Saya adalah chatbot perpustakaan. Ada yang bisa saya bantu?",
    };

    document.getElementById("pesanBukuBtn").addEventListener("click", function() {
        @if (session()->has('member'))
            // Jika pengguna memiliki sesi member, lakukan fungsi pesan buku di sini
            // Ganti URL sesuai dengan rute yang sesuai dengan fungsi pesan buku
            // window.location.href = "{{ route('dashboardMember', $bukuData['buku']->buku_id) }}";
            window.location.href = "{{ route('dashboardMember') }}";
        @else
            Swal.fire({
                title: 'Peringatan!',
                text: 'Anda harus login terlebih dahulu untuk melakukan pemesanan buku.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Login',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    window.location.href = "{{ route('login') }}";
                }
            });
        @endif
    });
</script> --}}

</body>

</html>
