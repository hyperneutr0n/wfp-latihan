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
      @foreach ($products as $product)
				<tr>
					<th scope="row">{{ $product->id }}</th>
					<td>{{ $product->name }}</td>
				</tr>
      @endforeach
    </tbody>
  </table>
@endsection
