@extends('layouts.dashboard-v1')

@section('content')
  <table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($customers as $customer)
        <tr>
          <th scope="row">{{ $customer->id }}</th>
          <td>{{ $customer->name }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
