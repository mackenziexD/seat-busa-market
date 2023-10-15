@extends('web::layouts.grids.12', ['viewname' => 'seat-busa-market::orders'])

@section('page_header', 'WELCUM TO BUSA-MART')

@section('full')

    <div class="card">
        <div class="card-header">
            Order {{ $order->id }}
        </div>
        <div class="card-body">            
            <div class="row">
                <div class="col-md-4">
                    <h5>Items Requested: {{count(json_decode($order->order_json))}}</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(json_decode($order->order_json) as $item)
                            <tr>
                                <td>{{$item->name}}</td>
                                <td>{{$item->quantity}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2">Total: {{number_format($order->estimated_price)}} ISK</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="col-md-4">
                    <span>Created By: {{ $order->creator->name }}</span>
                    <br>
                    <span>Janice Link: <a href="{{ $order->janice_link }}" target="_blank">{{ $order->janice_link }}</a></span>
                    <br>
                    <span>Status: {{ $order->status }}</span>
                    <br>
                    <span>Created At: {{ $order->created_at }}</span>
                    <br>
                </div>

                <div class="col-md-4">
                    Actions
                    <br><br>
                    @if($order->status == 'Outstanding')
                    <form action="{{ route('seat-busa-market.completeOrder', ['id' => $order->id]) }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="status" value="accepted">
                        <button type="submit" class="btn btn-info">Complete</button>
                    </form>
                    @elseif($order->status == 'Completed')
                    <button type="submit" class="btn btn-success disabled">Completed</button>
                    @endif
                    <br>
                </div>
            </div>
        </div>
    </div>
@stop

@push('javascript')
@endpush


