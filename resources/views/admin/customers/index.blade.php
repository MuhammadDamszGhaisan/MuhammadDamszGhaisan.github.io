@extends('layouts.admin')

@section('title', 'Kelola Pelanggan')

@section('content')
<h4 class="mb-3"><i class="bi bi-people"></i> Kelola Pelanggan</h4>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr><th>Nama</th><th>Username</th><th>No. WA</th><th>Jumlah Pesanan</th><th>Total Belanja</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td class="fw-semibold">{{ $customer->name }}</td>
                        <td>{{ $customer->username }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td><span class="badge bg-secondary">{{ $customer->orders_count }}</span></td>
                        <td>Rp {{ number_format((float) ($customer->orders_sum_total ?? 0), 0, ',', '.') }}</td>
                        <td class="text-end"><a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada pelanggan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $customers->links() }}</div>
@endsection
