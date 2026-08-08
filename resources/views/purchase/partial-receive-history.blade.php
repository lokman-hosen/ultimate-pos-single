@extends('layouts.app')
@section('title', 'Purchase Partial Receive')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">Partial Receive History <i class="fa fa-keyboard-o hover-q text-muted" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="bottom" data-content="@include('purchase.partials.keyboard_shortcuts_details')" data-html="true" data-trigger="hover" data-original-title="" title=""></i></h1>
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary'])

        <div class="row">
            <div class="col-sm-12">
              <h3>
                  <b>Product Name:</b> {{$partialReceiveHistories->first()->product->name.'(SKU:'. $partialReceiveHistories->first()->product->sku.'),'}}
              </h3>

              <h4>
                  <b>Purchase Quantity:</b> {{@format_quantity($partialReceiveHistories->first()->purchase_quantity)}}
              </h4>
              <h4>
                  <b>Total Received:</b> {{@format_quantity($partialReceiveHistories->sum('received_quantity'))}}
              </h4>
                <br>
            </div>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-condensed table-bordered table-th-green text-center table-striped"
                           id="purchase_entry_table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th class="add_without_price_hide">Date</th>
                            <th class="add_without_price_hide">Receive Quantity</th>
                            <th class="add_without_price_hide">Note</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($partialReceiveHistories as $key => $history)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{date("F j, Y, g:i a",strtotime($history->date))}}</td>
                                <td>{{@format_quantity($history->received_quantity)}}</td>
                                <td>{{$history->note ?? '--'}}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3"> No history</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endcomponent
</section>
@endsection

