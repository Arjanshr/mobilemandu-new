@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Statistics Widgets -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $pendingOrderCount }}</h3>
                        <p>Pending Orders</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <a href="{{ route('orders') }}" class="small-box-footer">
                        Manage Orders <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $userCount }}</h3>
                        <p>Total Users</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('users') }}" class="small-box-footer">
                        Manage Users <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $orderCount }}</h3>
                        <p>Total Orders</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <a href="{{ route('orders') }}" class="small-box-footer">
                        Manage Orders <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>Rs{{ number_format($totalRevenue, 2) }}</h3>
                        <p>Total Revenue</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recently Added Products -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recently Added Products</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($recentProducts as $product)
                                <li class="list-group-item">
                                    <strong>
                                        <a href="{{ route('product.show', $product->id) }}">
                                            {{ $product->name }} - Rs{{ number_format($product->price, 2) }}
                                        </a>
                                    </strong>
                                    @if ($product->variants->count() > 0)
                                        <ul class="mt-2">
                                            @foreach ($product->variants as $variant)
                                                <li>
                                                    Variant: {{ $variant->sku }} - Rs{{ number_format($variant->price, 2) }} (Stock: {{ $variant->stock_quantity }})
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Orders -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Orders</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($recentOrders as $order)
                                <li class="list-group-item">
                                    <a href="{{ route('order.show', $order->id) }}">
                                        Order #{{ $order->id }} - Rs{{ number_format($order->grand_total, 2) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Users</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($recentUsers as $user)
                                <li class="list-group-item">
                                    {{ $user->name }} - {{ $user->email }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


@section('right-sidebar')
    <div class="p-3">
        <h5>User Activities</h5>
        <ul class="list-group">
            @forelse ($activities as $activity)
                <li class="list-group-item">
                    {{ $activity->causer ? $activity->causer->name : 'Someone' }} 
                    {{ $activity->description }} 
                    {{ $activity->subject ? $activity->subject->name : 'a '.$activity->subject_type }} 
                    <small class="text-muted">({{ $activity->created_at->diffForHumans() }})</small>
                </li>
            @empty
                <li class="list-group-item text-center">No recent activities.</li>
            @endforelse
        </ul>
    </div>
@endsection
