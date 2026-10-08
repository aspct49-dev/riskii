/**
 * Mobile menu.
 *
 * The old toggle flipped `.add` on the list, but style.css hid the list with
 * display:none on phones and only showed it for `.menu-right.active` — so the
 * button did nothing below 768px. The open state is now one class on the
 * header, styled in css/refresh.css, and the button reports it to screen
 * readers.
 */
(function () {
    'use strict';

    var header = document.querySelector('.site-header');
    var toggle = header && header.querySelector('.nav-toggle');
    if (!toggle) return;

    function setOpen(open) {
        header.classList.toggle('nav-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
    }

    toggle.addEventListener('click', function (ev) {
        ev.stopPropagation();
        setOpen(!header.classList.contains('nav-open'));
    });

    // Close on Escape, on a tap outside the header, and when a link is chosen.
    document.addEventListener('keydown', function (ev) {
        if (ev.key === 'Escape' && header.classList.contains('nav-open')) {
            setOpen(false);
            toggle.focus();
        }
    });
    document.addEventListener('click', function (ev) {
        if (header.classList.contains('nav-open') && !header.contains(ev.target)) setOpen(false);
    });
    header.querySelectorAll('.menu-list a').forEach(function (a) {
        a.addEventListener('click', function () { setOpen(false); });
    });

    // Rotating to desktop with the menu open would leave the class behind.
    var desktop = window.matchMedia('(min-width: 992px)');
    var onChange = function (mq) { if (mq.matches) setOpen(false); };
    if (desktop.addEventListener) desktop.addEventListener('change', onChange);
    else if (desktop.addListener) desktop.addListener(onChange);
})();
