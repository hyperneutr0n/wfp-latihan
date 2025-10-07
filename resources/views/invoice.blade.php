@extends('layouts.dashboard-v1')

@section('content')
  <table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Date</th>
        <th scope="col">Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($invoices as $invoice)
        <tr>
          <th scope="row">{{ $invoice->id }}</th>
          <td>{{ $invoice->date }}</td>
          <td>{{ $invoice->status }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
