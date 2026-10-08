<footer class="footer site-footer position-relative z-2">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-3">
                <div class="footer-left d-flex flex-column">
                    <a href="index">
                        <img src="images/footer-logo.png" alt="<?= e(SITE_NAME) ?>" class="img-fluid">
                    </a>
                    <p class="mb-0 font-pop-bld">&copy; <?= date('Y') ?> <?= e(SITE_NAME) ?></p>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="footer-middle">
                    <h3 class="mb-0 font-pop-bld">Disclaimer</h3>
                    <p class="mb-0 font-pop-bld">
                        18+ only. Gamble responsibly. You are responsible for your own bets — we take no
                        responsibility for losses on any site linked here.
                    </p>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="footer-right">
                    <ul class="mb-0 ps-0 d-flex align-items-center justify-content-lg-end">
                        <li><a target="_blank" rel="noopener" href="<?= e(SITE_TWITTER) ?>" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a></li>
                        <li><a target="_blank" rel="noopener" href="<?= e(SITE_INSTA) ?>" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a></li>
                        <li><a target="_blank" rel="noopener" href="<?= e(SITE_DISCORD) ?>" aria-label="Discord"><i class="fa-brands fa-discord"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="footer-sponsor d-flex align-items-center justify-content-center gap-3">
            <span class="font-pop-bld">Official partner</span>
            <a href="<?= e(SPONSOR_LINK) ?>" target="_blank" rel="noopener sponsored">
                <img src="<?= e(SPONSOR_LOGO) ?>" alt="<?= e(SPONSOR_NAME) ?>" width="364" height="112">
            </a>
        </div>
    </div>
</footer>

<!-- jQuery and Bootstrap's JS used to load here (~165 KB, render-blocking).
     Nothing on the site calls either any more. -->
<script src="js/custom.js" defer></script>
<script src="js/gamba.js" defer></script>
<script src="js/reveal.js" defer></script>

</body>
</html>
