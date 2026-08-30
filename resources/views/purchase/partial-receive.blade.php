@extends('layouts.app')
@section('title', 'Purchase Partial Receive')

@section('content')

    @php
        $custom_labels = json_decode(session('business.custom_labels'), true);
    @endphp
            <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">Purchase Partial Receive <i class="fa fa-keyboard-o hover-q text-muted" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="bottom" data-content="@include('purchase.partials.keyboard_shortcuts_details')" data-html="true" data-trigger="hover" data-original-title="" title=""></i></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Page level currency setting -->
        <input type="hidden" id="p_code" value="{{$currency_details->code}}">
        <input type="hidden" id="p_symbol" value="{{$currency_details->symbol}}">
        <input type="hidden" id="p_thousand" value="{{$currency_details->thousand_separator}}">
        <input type="hidden" id="p_decimal" value="{{$currency_details->decimal_separator}}">

        @if(auth()->user()->hidePurchasePrice())
            <style>.add_without_price_hide{display:none !important;}</style>
        @endif

        @include('layouts.partials.error')


        @php
            $currency_precision = session('business.currency_precision', 2);
        @endphp

        <input type="hidden" id="purchase_id" value="{{ $purchase->id }}">

        @component('components.widget', ['class' => 'box-primary'])

            <div class="row">
                <div class="col-sm-12">
                    <h4>
                        <b>Reference:</b> {{$purchase->ref_no}},
                        <b>Location:</b> {{$purchase->location->name}},
                        <b>Supplier:</b> {{$purchase->contact->supplier_business_name}},
                    </h4>
                </div>
                <div class="col-sm-12">
                    @php
                        $hide_tax = '';
                        if( session()->get('business.enable_inline_tax') == 0){
                            $hide_tax = 'hide';
                        }
                        $currency_precision = session('business.currency_precision', 2);
                        $quantity_precision = session('business.quantity_precision', 2);
                    @endphp

                    {{-- Search input above table --}}
                    <div class="row mb-10">
                        <div class="col-sm-12 col-md-6"></div>
                        <div class="col-sm-12 col-md-6">
                            <div class="input-group">
                                <input type="text" id="tableSearch" class="form-control"
                                       placeholder="Search table..."
                                       aria-label="Search">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button" id="clearSearch" title="Clear search">
                                    <i class="fa fa-times" style="font-size: 18px;"></i>
                                </button>
                            </span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-condensed table-bordered table-th-green text-center table-striped" id="purchase_entry_table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang( 'product.product_name' )</th>
                                <th>@if(empty($is_purchase_order)) @lang( 'purchase.purchase_quantity' ) @else @lang( 'lang_v1.order_quantity' ) @endif</th>
                                <th class="add_without_price_hide">Receive Quantity</th>
                                <th class="add_without_price_hide">Pending Quantity</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $row_count = 0; ?>
                            @foreach($purchase->purchase_lines as $key => $purchase_line)

                                @php $pendingQuantity = $purchase_line->quantity - $purchase_line->quantity_received @endphp
                                <tr @if(!empty($purchase_line->purchase_order_line) && !empty($common_settings['enable_purchase_order'])) data-purchase_order_id="{{$purchase_line->purchase_order_line->transaction_id}}" @endif  @if(!empty($purchase_line->purchase_requisition_line) && !empty($common_settings['enable_purchase_requisition'])) data-purchase_requisition_id="{{$purchase_line->purchase_requisition_line->transaction_id}}" @endif>
                                    <td><span class="sr_number">{{$key+1}}</span></td>
                                    <td>
                                        {{ $purchase_line->product->name }} ({{$purchase_line->variations->sub_sku}})
                                        @if( $purchase_line->product->type == 'variable')
                                            <br/>(<b>{{ $purchase_line->variations->product_variation->name}}</b> : {{ $purchase_line->variations->name}})
                                        @endif
                                    </td>

                                    <td>{{@format_quantity($purchase_line->quantity)}}</td>
                                    <td class="add_without_price_hide text-info">
                                        <b>{{ @format_quantity($purchase_line->quantity_received ?? 0) }}</b>
                                    </td>

                                    <td class="add_without_price_hide {{$pendingQuantity == 0 ? 'text-success' : 'text-danger'  }}">
                                        <b>{{ @format_quantity($pendingQuantity ?? 0) }}</b>
                                    </td>

                                    <td>
                                        @if($pendingQuantity != 0)
                                            <button type="button" class="tw-dw-btn tw-dw-btn-primary tw-dw-btn-sm tw-text-white partial-receive" data-toggle="modal"
                                                    data-transaction-id="{{ $purchase->id }}"
                                                    data-purchase-line-id="{{ $purchase_line->id }}"
                                                    data-product-id="{{ $purchase_line->product->id }}"
                                                    data-product-name="{{ $purchase_line->product->name }}"
                                                    title="Edit">
                                                <i class="fa fa-plus"></i>
                                                Partial Rcv
                                            </button>
                                        @endif

                                        @if($purchase_line->partialReceiveHistories->count() > 0)
                                            <a class="tw-dw-btn tw-dw-btn-success tw-dw-btn-sm tw-text-white"
                                               href="{{route('product.partial.receive.history', ['product_id' => $purchase_line->product->id, 'transaction_id' => $purchase_line->transaction_id])}}">
                                                <i class="fa fa-history"></i>
                                                History
                                            </a>
                                        @endif

                                    </td>
                                </tr>
                                    <?php $row_count = $loop->index + 1 ; ?>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" id="row_count" value="{{ $row_count }}">
                </div>
            </div>
        @endcomponent


        <div class="modal fade" id="partialReceiveModalSizeLg" tabindex="-1" role="dialog" aria-labelledby="partialReceiveModalSizeLg" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 id="exampleModalLabel" class="tw-font-bold">Add partial receive</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>

                    <form id="cardEditForm" method="POST" action="">
                        <h4  style="margin-left: 20px;">Procuct Name:
                            <span id="product-name"></span>
                        </h4>
                        @csrf
                        <input type="hidden" name="product_id" id="product-id">
                        <input type="hidden" name="purchase_line_id" id="purchase-line-id">
                        <div class="card-body" style="padding: 20px;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Receive Quantity<span class="text-danger">*</span></label>
                                        <input type="number" name="received_quantity" class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('received_quantity') }}"
                                               placeholder="Enter partial received quantity" />
                                        @error('received_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Receive Date<span class="text-danger">*</span></label>
                                        <input class="form-control" type="date" name="date">
                                        @error('received_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Note</label>
                                        <input type="text" name="note" class="form-control @error('note') is-invalid @enderror"
                                               value="{{ old('note') }}"
                                               placeholder="Enter note for future reference" />
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary font-weight-bold">Confirm Receive</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </section>
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            $('.partial-receive').on('click', function() {
                const transactionId = $(this).data('transaction-id');
                $('#product-id').val($(this).data('product-id'));
                $('#purchase-line-id').val($(this).data('purchase-line-id'));
                $('#product-name').text($(this).data('product-name'));

                const formAction = "{{ route('product.partial.receive.save', ['id' => ':id']) }}".replace(':id', transactionId);
                $('#cardEditForm').attr('action', formAction);

                $('#partialReceiveModalSizeLg').modal('show');
            });

            // ----- Table search (now works with numbers) -----
            var searchInput = $('#tableSearch');
            var clearBtn = $('#clearSearch');
            var tableRows = $('#purchase_entry_table tbody tr');
            var searchTimer = null;

            function filterTable() {
                var searchTerm = searchInput.val().trim().toLowerCase();
                if (searchTerm === '') {
                    tableRows.show();
                    return;
                }
                // No minimum length – search on any input
                tableRows.each(function() {
                    var rowText = $(this).text().toLowerCase();
                    $(this).toggle(rowText.indexOf(searchTerm) !== -1);
                });
            }

            // Debounced search (300ms delay)
            searchInput.on('input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(filterTable, 300);
            });

            // Clear button: reset and show all rows
            clearBtn.on('click', function() {
                searchInput.val('');
                tableRows.show();
                clearTimeout(searchTimer);
            });
        });
    </script>
@endsection