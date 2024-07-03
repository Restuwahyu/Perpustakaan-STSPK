<footer style="background-color: #2a3446;color: white;">
    <div class="container p-4">
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="footer-logo">
                    <img src="{{ asset('Zegva/Zegva_v1.0.0/Admin/vertical/assets/images/Logolight.png') }}"
                        class="logo-lg mb-2" alt="" height="70">
                </div>
                <div class="footer-info">
                    <p class="footer-address"><i class="fas fa-map-marker-alt"></i> <a
                            href="https://g.co/kgs/QjsQkhY">Jl. Kaliurang KM. 7 Condongcatur,
                            Depok, Sleman, DIY 55283</a></p>
                    <p class="footer-phone"><i class="fas fa-phone"></i> (0274) 885714, 880027</p>
                    <p class="footer-email"><i class="fas fa-envelope"></i><a
                            href="mailto:seminari.kentungan@gmail.com"> seminari.kentungan@gmail.com</a></p>
                    <p class="footer-whatsapp"><i class="fab fa-whatsapp"></i><a href="http://wa.me/6281327470014"> 0813
                            2747 0014</a> (Pastoral)</p>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 mb-4">
                <h5 class="mb-3" style="letter-spacing: 2px;">TENTANG KAMI</h5>
                <p>
                    Seminari Tinggi Santo Paulus Yogyakarta merupakan wadah pembinaan atau formasi pendidikan bagi
                    calon-calon imam diosesan masa depan Gereja untuk Keuskupan Agung Semarang, Keuskupan Purwokerto,
                    dan Keuskupan Ketapang.

                    Seminari Tinggi Santo Paulus didirikan pada tanggal 15 Agustus 1936 oleh Vikariat Apostolik Batavia,
                    Mgr. P. Willekens, SJ.
                </p>
                <h5 class="mb-1" style="letter-spacing: 2px;">Jam Operasional</h5>
                <p>
                    Senin - Jumat : 08.00 - 16.00 WIB
                </p>
            </div>
        </div>
    </div>
</footer>

<footer class="bottom-footer">
    <span id="year"></span> © Seminari Tinggi St. Paulus Kentungan <span class="d-none d-sm-inline-block">
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
<script src="{{ asset('storage/script.js') }}"></script>

<script>
    document.getElementById('year').textContent = new Date().getFullYear();
</script>
</body>

</html>
