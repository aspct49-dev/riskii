/**
 * Shared behaviour for the Gamba-era pages: the race countdown and the
 * click-to-copy promo chip. Both appear on more than one page, so they live
 * here rather than inline in each template.
 */
(function () {
    'use strict';

    // --- Countdown ----------------------------------------------------------
    // The deadline is rendered as an absolute epoch, so a visitor whose clock
    // is in another timezone still sees the same remaining time.
    var cd = document.getElementById('gm-countdown');
    if (cd) {
        var endsAt = parseInt(cd.dataset.ends, 10) * 1000;
        var cells = {};
        Array.prototype.forEach.call(cd.querySelectorAll('[data-unit]'), function (n) {
            cells[n.dataset.unit] = n;
        });

        var pad = function (n) { return n < 10 ? '0' + n : String(n); };
        var timer;

        var tick = function () {
            var left = endsAt - Date.now();
            if (left <= 0) {
                Object.keys(cells).forEach(function (k) { cells[k].textContent = '00'; });
                clearInterval(timer);
                return;
            }
            var s = Math.floor(left / 1000);
            if (cells.days)    cells.days.textContent    = pad(Math.floor(s / 86400));
            if (cells.hours)   cells.hours.textContent   = pad(Math.floor(s / 3600) % 24);
            if (cells.minutes) cells.minutes.textContent = pad(Math.floor(s / 60) % 60);
            if (cells.seconds) cells.seconds.textContent = pad(s % 60);
        };

        tick();
        timer = setInterval(tick, 1000);
    }

    // --- Copy the promo code ------------------------------------------------
    Array.prototype.forEach.call(document.querySelectorAll('.gm-code'), function (chip) {
        chip.setAttribute('role', 'button');
        chip.setAttribute('tabindex', '0');

        var copy = function () {
            var code = chip.dataset.code || '';
            var icon = chip.querySelector('i');
            var done = function () {
                if (!icon) return;
                icon.className = 'fa-solid fa-check';
                setTimeout(function () { icon.className = 'fa-regular fa-copy'; }, 1600);
            };

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(code).then(done).catch(function () {});
                return;
            }
            // Plain http:// and older Safari never get the async clipboard API.
            var t = document.createElement('textarea');
            t.value = code;
            t.style.position = 'fixed';
            t.style.opacity = '0';
            document.body.appendChild(t);
            t.select();
            try { document.execCommand('copy'); done(); } catch (e) { /* nothing to do */ }
            document.body.removeChild(t);
        };

        chip.addEventListener('click', copy);
        chip.addEventListener('keydown', function (ev) {
            if (ev.key === 'Enter' || ev.key === ' ') { ev.preventDefault(); copy(); }
        });
    });
})();
