@php
    $pos_form_id = $form_id ?? 'add_pos_sell_form';
@endphp
<script>
/*
 * POS layout helpers (layout only — no business logic).
 *
 * Heights are handled entirely by CSS flexbox in public/css/pos-responsive.css.
 * This script only:
 *   1. collapses form rows whose columns are all hidden,
 *   2. publishes the fixed action bar height as --pos-actions-h,
 *   3. toggles the compact (card) cart-row layout,
 *   4. opens/closes the cart drawer on tablet/mobile and mirrors count/total
 *      into the sticky cart bar.
 */
(function() {
    var POS_FORM_ID = @json($pos_form_id);
    var DRAWER_MQ = window.matchMedia('(max-width: 991.98px)');
    var PHONE_MQ = window.matchMedia('(max-width: 767.98px)');
    var root = document.documentElement;

    function collapseHiddenFormRows() {
        var form = document.getElementById(POS_FORM_ID);
        if (!form) return;
        var rows = form.querySelectorAll('.pos-cart-body > .row');
        rows.forEach(function(row) {
            if (row.querySelector('.pos_product_div')) return;
            var cols = row.querySelectorAll('[class*="col-"]');
            if (cols.length === 0) return;
            var allHidden = true;
            cols.forEach(function(col) {
                if (col.parentElement !== row) return;
                var visible = colHasVisibleContent(col);
                if (visible) allHidden = false;
                var display = visible ? '' : 'none';
                if (col.style.display !== display) col.style.display = display;
            });
            var rowDisplay = allHidden ? 'none' : '';
            if (row.style.display !== rowDisplay) row.style.display = rowDisplay;
        });
    }

    function colHasVisibleContent(el) {
        if (el.classList.contains('hide')) return false;
        var children = el.children;
        for (var i = 0; i < children.length; i++) {
            var child = children[i];
            if (child.tagName === 'INPUT' && child.type === 'hidden') continue;
            if (child.classList.contains('hide') || child.style.display === 'none') continue;
            return true;
        }
        return !!(el.innerText && el.innerText.trim().length > 0);
    }

    function setActionsHeight() {
        var bar = document.querySelector('.pos-form-actions');
        var h = bar ? Math.ceil(bar.getBoundingClientRect().height) : 0;
        root.style.setProperty('--pos-actions-h', h + 'px');
    }

    function hasCartDrawer() {
        return !!document.querySelector('.pos-screen--with-products');
    }

    // Compact cart rows: always when the cart shares the screen with products
    // (cart column is narrow), otherwise only on phones.
    function syncCompactCart() {
        var col = document.querySelector('.pos-cart-col');
        if (!col) return;
        col.classList.toggle('pos-cart-compact', hasCartDrawer() || PHONE_MQ.matches);
    }

    // On tablet/mobile the cart is a drawer, so the search / barcode box would be hidden while
    // browsing products. Move the same DOM node (keeps its autocomplete + scanner bindings)
    // above the product grid, and back into the cart header on desktop.
    var searchGroup = null, searchHome = null;
    function placeSearch() {
        var slot = document.getElementById('pos_search_slot');
        if (!slot) return;
        if (!searchGroup) {
            var input = document.getElementById('search_product');
            searchGroup = input ? input.closest('.form-group') : null;
            searchHome = searchGroup ? searchGroup.parentElement : null;
        }
        if (!searchGroup || !searchHome) return;
        var target = (hasCartDrawer() && DRAWER_MQ.matches) ? slot : searchHome;
        if (searchGroup.parentElement !== target) {
            var hadFocus = document.activeElement && searchGroup.contains(document.activeElement);
            target.appendChild(searchGroup);
            if (hadFocus) document.getElementById('search_product').focus();
        }
        slot.classList.toggle('is-filled', target === slot);
        collapseDebounced();
    }

    function setCartOpen(open) {
        if (!hasCartDrawer()) return;
        open = !!open && DRAWER_MQ.matches;
        document.body.classList.toggle('pos-cart-open', open);
        document.querySelectorAll('[data-pos-cart-open]').forEach(function(btn) {
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        // Action bar height changes (cart bar <-> pay buttons)
        setActionsHeight();
    }

    function syncCartBar() {
        var qty = document.querySelector('.pos_form_totals .total_quantity');
        var total = document.querySelector('#total_payable');
        var count = qty ? qty.textContent.trim() : '0';
        var rows = document.querySelectorAll('#pos_table tbody tr.product_row').length;
        document.querySelectorAll('[data-pos-cart-count]').forEach(function(el) {
            el.textContent = rows ? count : '0';
        });
        document.querySelectorAll('[data-pos-cart-total]').forEach(function(el) {
            el.textContent = total ? total.textContent.trim() : '0';
        });
    }

    var _collapseTimer = null;
    function collapseDebounced() {
        clearTimeout(_collapseTimer);
        _collapseTimer = setTimeout(collapseHiddenFormRows, 80);
    }

    $(document).ready(function() {
        collapseHiddenFormRows();
        setTimeout(collapseHiddenFormRows, 300);
        setTimeout(collapseHiddenFormRows, 800);
        syncCompactCart();
        placeSearch();
        setActionsHeight();
        syncCartBar();

        var bar = document.querySelector('.pos-form-actions');
        if (bar && window.ResizeObserver) {
            new ResizeObserver(setActionsHeight).observe(bar);
        }
        $(window).on('resize orientationchange', setActionsHeight);

        var onBreakpoint = function() {
            syncCompactCart();
            placeSearch();
            if (!DRAWER_MQ.matches) setCartOpen(false);
        };
        if (DRAWER_MQ.addEventListener) {
            DRAWER_MQ.addEventListener('change', onBreakpoint);
            PHONE_MQ.addEventListener('change', onBreakpoint);
        } else if (DRAWER_MQ.addListener) {
            DRAWER_MQ.addListener(onBreakpoint);
            PHONE_MQ.addListener(onBreakpoint);
        }

        // Only the header rows of the cart need re-collapsing (module fields toggle)
        var cartBody = document.querySelector('#' + POS_FORM_ID + ' .pos-cart-body');
        if (cartBody) {
            Array.prototype.forEach.call(cartBody.children, function(child) {
                if (child.classList.contains('row') && !child.classList.contains('pos-cart-list-row')) {
                    new MutationObserver(collapseDebounced).observe(child, {
                        childList: true, subtree: true, attributes: true, attributeFilter: ['class']
                    });
                }
            });
        }

        // Mirror item count / total payable into the cart bar + drawer header
        var mirrorTargets = [document.querySelector('.pos_form_totals .total_quantity'), document.querySelector('#total_payable'), document.querySelector('#pos_table tbody')];
        var mirrorObserver = new MutationObserver(syncCartBar);
        mirrorTargets.forEach(function(el) {
            if (el) mirrorObserver.observe(el, { childList: true, characterData: true, subtree: true });
        });

        // Drawer open / close
        $(document).on('click', '[data-pos-cart-open]', function() { setCartOpen(true); });
        $(document).on('click', '[data-pos-cart-close]', function() { setCartOpen(false); });
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && document.body.classList.contains('pos-cart-open') && !$('.modal.in').length) {
                setCartOpen(false);
            }
        });
    });
})();
</script>
