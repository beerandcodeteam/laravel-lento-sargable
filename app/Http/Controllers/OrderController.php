<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function good(Request $request)
    {
        $date   = $request->query('date', now()->toDateString());
        $start  = Carbon::parse($date)->startOfDay();
        $end    = Carbon::parse($date)->endOfDay();

        $orders = Order::query()
            ->whereBetween('created_at', [$start, $end])
            ->orderByDesc('id')
            ->paginate(50)
            ->withQueryString();

        return view('orders.index', compact('orders', 'date'));
    }

    public function bad(Request $request)
    {
        $date = $request->query('date', now()->toDateString());

        $orders = Order::query()
            ->whereDate('created_at', $date)
            ->orderByDesc('id')
            ->paginate(50)
            ->withQueryString();

        return view('orders.index', compact('orders', 'date'));
    }
}
