@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Welcome to the Shop</h1>
    <span class="badge bg-warning text-dark" id="totalCoins">Koin: {{ Auth::user()->coins }}</span>
  </div>

  <p>Here you can purchase items with your coins!</p>

  <div class="row">
    @foreach($vouchers as $voucher)
    <div class="col-md-4 mb-3">
      <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title">{{ $voucher->name }}</h5>
        <p class="card-text">{{ $voucher->description }}</p>
        <p class="coin-badge">🪙 {{ $voucher->cost }}</p>
        <form action="{{ route('shop.redeem') }}" method="POST" class="redeem-form">
        @csrf
        <input type="hidden" name="voucher_id" value="{{ $voucher->id }}">
        <button type="submit" class="btn btn-success">Tukar</button>
        </form>
      </div>
      </div>
    </div>
  @endforeach
  </div>
</div>
@endsection

@section('other-js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    $('.redeem-form').on('submit', function (e) {
      e.preventDefault(); // Mencegah pengiriman form default

      var form = $(this);
      $.ajax({
        type: 'POST',
        url: form.attr('action'),
        data: form.serialize(), // Mengambil data dari form
        success: function (response) {
          // Update total koin
          $('#totalCoins').text('Koin: ' + response.coins);
          alert('Voucher berhasil ditukar!');
        },
        error: function (xhr) {
          alert('Koin tidak cukup untuk menukar voucher ini.');
        }
      });
    });
  });
</script>
@endsection