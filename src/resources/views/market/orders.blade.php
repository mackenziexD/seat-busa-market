@extends('web::layouts.grids.12', ['viewname' => 'seat-busa-market::orders'])

@section('page_header', 'WELCUM TO BUSA-MART')

@section('full')

    <div class="card">
        <div class="card-header">
            Orders
        </div>
        <div class="card-body">            
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead class="">
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Janice link</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->creator->name }}</td>
                                <td><a href="{{ $order->janice_link }}" target="_blank">{{ $order->janice_link }}</a></td>
                                <td>{{ $order->status }}</td>
                                <td>{{ $order->created_at->diffForHumans() }}</td>
                                <td><a href="{{ route('seat-busa-market.order', ['id' => $order->id]) }}">View</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@push('javascript')
@endpush


