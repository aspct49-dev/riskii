/**
 * Scroll reveals.
 *
 *   data-reveal                one element fades and rises in when it enters view
 *   data-reveal="fade"         fades only — for things that should not move
 *   data-reveal-stagger        its direct children reveal one after another
 *
 * The head of every page adds `js-reveal` to <html> before first paint, which
 * is what hides the targets. This file marks the page `reveal-ready` and then
 * shows each target once, the first time it is on screen. Reduced motion never
 * gets `js-reveal`, so for those visitors nothing is hidden or animated.
 */
(function () {
    'use strict';

    var root = document.documentElement;
    if (!root.classList.contains('js-reveal')) return;
    root.classList.add('reveal-ready');

    // Give each child of a stagger group its slot, so CSS can delay it.
    Array.prototype.forEach.call(document.querySelectorAll('[data-reveal-stagger]'), function (group) {
        Array.prototype.forEach.call(group.children, function (child, i) {
            child.style.setProperty('--reveal-i', Math.min(i, 8));
        });
    });

    var targets = document.querySelectorAll('[data-reveal], [data-reveal-stagger]');

    var show = function (el) { el.classList.add('is-in'); };

    if (!('IntersectionObserver' in window)) {
        Array.prototype.forEach.call(targets, show);
        return;
    }

    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            show(entry.target);
            io.unobserve(entry.target);   // one-shot: scrolling back up never re-hides
        });
    }, {
        // Fire once the top edge is 8% clear of the bottom of the screen. A
        // threshold of 0 rather than a percentage matters for tall blocks: a
        // stacked column on a phone may never be 12% visible at once.
        rootMargin: '0px 0px -8% 0px',
        threshold: 0,
    });

    Array.prototype.forEach.call(targets, function (el) { io.observe(el); });
})();
