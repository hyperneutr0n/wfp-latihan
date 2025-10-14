@extends('layouts.dashboard-v1')

@section('content')
  <div class="container m-2">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($categories as $category)
          <tr>
            <th scope="row">{{ $category->id }}</th>
            <td>{{ $category->name }}</td>
            <td id="{{ 'count-' . $category->id }}">
              <a class="count" href="{{ route('category.count', $category->id) }}" data-id="{{ $category->id }}">Count</a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection

@section('scripts')
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
  <script>
    $(".count").click(function(e) {
      e.preventDefault();
      var url = $(this).attr('href');
      console.log(url);
      var id = $(this).attr('data-id');
      $.ajax({
        url: url,
        success: function(result) {
          $("#count-" + id).html(`This category has <b>${result.product_count}</b> products`);
        }
      });
    });
  </script>
@endsection
