@extends('layouts.app')

@section('title', __('sale.pos_sale'))

@section('content')
    @php
        // Product grid is shown next to the cart (and the cart becomes a drawer on tablet/mobile)
        $pos_show_products = empty($pos_settings['hide_product_suggestion']);
    @endphp
    <section class="content no-print pos-screen {{ $pos_show_products ? 'pos-screen--with-products' : 'pos-screen--cart-only' }}">
        <input type="hidden" id="amount_rounding_method" value="{{ $pos_settings['amount_rounding_method'] ?? '' }}">
        @if (!empty($pos_settings['allow_overselling']))
            <input type="hidden" id="is_overselling_allowed">
        @endif
        @if (session('business.enable_rp') == 1)
            <input type="hidden" id="reward_point_enabled">
        @endif
        @php
            $is_discount_enabled = $pos_settings['disable_discount'] != 1 ? true : false;
            $is_rp_enabled = session('business.enable_rp') == 1 ? true : false;
        @endphp
        {!! Form::open([
            'url' => action([\App\Http\Controllers\SellPosController::class, 'store']),
            'method' => 'post',
            'id' => 'add_pos_sell_form',
        ]) !!}
        <div class="row pos-shell" style="margin:0;">
            <div class="col-md-12 pos-shell-col" style="padding:0;">
                <div class="row pos-main">
                    <div class="pos-cart-col @if ($pos_show_products) pos-cart-compact @endif" id="pos_cart_panel" @if ($pos_show_products) role="dialog" aria-label="@lang('lang_v1.cart')" @endif>

                        <div class="pos-cart-card tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-rounded-2xl tw-bg-white">
                            @if ($pos_show_products)
                                {{-- Drawer header: only visible when the cart is shown as a drawer (< 992px) --}}
                                <div class="pos-cart-drawer-head">
                                    <span class="pos-cart-drawer-title">
                                        <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                                        @lang('lang_v1.cart')
                                        <span class="pos-cart-drawer-count" data-pos-cart-count>0</span>
                                    </span>
                                    <button type="button" class="pos-cart-drawer-close" data-pos-cart-close aria-label="@lang('messages.close')">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    </button>
                                </div>
                            @endif

                                <div class="box-body pb-0 pos-cart-body">
                                    {!! Form::hidden('location_id', $default_location->id ?? null, [
                                        'id' => 'location_id',
                                        'data-receipt_printer_type' => !empty($default_location->receipt_printer_type)
                                            ? $default_location->receipt_printer_type
                                            : 'browser',
                                        'data-default_payment_accounts' => $default_location->default_payment_accounts ?? '',
                                    ]) !!}
                                    <!-- sub_type -->
                                    {!! Form::hidden('sub_type', isset($sub_type) ? $sub_type : null) !!}
                                    <input type="hidden" id="item_addition_method"
                                        value="{{ $business_details->item_addition_method }}">
                                    @include('sale_pos.partials.pos_form')

                                    @include('sale_pos.partials.pos_form_totals')
                                </div>
                        </div>
                    </div>
                    @if ($pos_show_products)
                        <div class="pos-products-col" id="pos_sidebar_wrap">
                            {{-- Search/scan box is moved here by pos_layout_script on tablet/mobile (cart is a drawer there) --}}
                            <div class="pos-search-slot" id="pos_search_slot"></div>
                            @include('sale_pos.partials.pos_sidebar')
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Form-bound modals live outside the cart column so they are never trapped inside the cart drawer --}}
        @include('sale_pos.partials.payment_modal')

        @if (empty($pos_settings['disable_suspend']))
            @include('sale_pos.partials.suspend_note_modal')
        @endif

        @if (empty($pos_settings['disable_recurring_invoice']))
            @include('sale_pos.partials.recurring_invoice_modal')
        @endif

        @if ($pos_show_products)
            <div class="pos-cart-backdrop" data-pos-cart-close aria-hidden="true"></div>
        @endif
        @include('sale_pos.partials.pos_form_actions', ['cart_drawer' => $pos_show_products])
        {!! Form::close() !!}
    </section>

    <!-- This will be printed -->
    <section class="invoice print_section" id="receipt_section">
    </section>
    <div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        @include('contact.create', ['quick_add' => true])
    </div>
    {{-- The product grid is always inline on this page, so the old phone-only product modal is not needed --}}
    <!-- /.content -->
    <div class="modal fade register_details_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade close_register_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
    <!-- quick product modal -->
    <div class="modal fade quick_add_product_modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"></div>

    <div class="modal fade" id="expense_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade" id="pos_pay_contact_due_modal" tabindex="-1" role="dialog">
    </div>

    @include('sale_pos.partials.configure_search_modal')

    @include('sale_pos.partials.recent_transactions_modal')

    @include('sale_pos.partials.weighing_scale_modal')

@stop
@section('css')
    <!-- include module css -->
    @if (!empty($pos_module_data))
        @foreach ($pos_module_data as $key => $value)
            @if (!empty($value['module_css_path']))
                @includeIf($value['module_css_path'])
            @endif
        @endforeach
    @endif
@stop
@section('javascript')
    <script src="{{ asset('js/pos.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/printer.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/opening_stock.js?v=' . $asset_v) }}"></script>
    @include('sale_pos.partials.keyboard_shortcuts')

    <!-- Call restaurant module if defined -->
    @if (in_array('tables', $enabled_modules) ||
            in_array('modifiers', $enabled_modules) ||
            in_array('service_staff', $enabled_modules))
        <script src="{{ asset('js/restaurant.js?v=' . $asset_v) }}"></script>
    @endif
    <!-- include module js -->
    @if (!empty($pos_module_data))
        @foreach ($pos_module_data as $key => $value)
            @if (!empty($value['module_js_path']))
                @includeIf($value['module_js_path'], ['view_data' => $value['view_data']])
            @endif
        @endforeach
    @endif
    @include('sale_pos.partials.pos_layout_script', ['form_id' => 'add_pos_sell_form'])
@endsection
