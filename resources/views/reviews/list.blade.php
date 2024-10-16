@extends('layoutss.app')
@section('main')
    <div class="container">
        <div class="row my-5">
            <div class="col-md-3">
                @include('layoutss.sidebar')
            </div>
            <div class="col-md-9">
@include('layoutss.message')
                <div class="card border-0 shadow">
                    <div class="card-header  text-white">
                        My Reviews
                    </div>
                    <div class="card-body pb-0">
                        <div class="d-flex justify-content-end">

                            {{-- search bar  --}}
                            <form action="" method="get">
                                <div class="d-flex">
                                    <input type="text" class="form-control" value="{{ Request::get('keyword') }}" name="keyword" placeholder="keyword">

                                    <button type="submit" class="btn btn-primary ms-2">Submit</button>
                                    <a href="{{ route('account.review') }}" class="btn btn-secondary" >Clear</a>
                                </div>
                            </form>

                        </div>
                        <table class="table  table-striped mt-3">
                            <thead class="table-dark">
                                <tr>
                                    <th>Book</th>
                                    <th>Review</th>
                                    <th>Rating</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                    <th width="100">Action</th>
                                </tr>
                            <tbody>
                                @if ($reviews->isNotEmpty())
                                    @foreach ($reviews as $review)
                                        <tr>
                                            <td>{{ $review->book->title }}</td>
                                            <td>{{ $review->review }} </br><strong>{{ $review->user->name }}</strong></td>
                                            <td>{{ $review->rating }} </td>
                                            <td>{{ \Carbon\Carbon::parse($review->created_at)->format('d M, Y') }}</td>
                                            {{-- <td>3.0</td> --}}
                                            <td>
                                                @if ($review->status == 1)
                                                    <span class="text-success">Active</span>
                                                @else
                                                    <span class="text-danger">Block</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('account.review.edit',$review->id) }}" class="btn btn-primary btn-sm"><i
                                                        class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <a href="#" onclick="deletereview({{ $review->id }})" class="btn btn-danger btn-sm"><i
                                                        class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                            </thead>
                        </table>
                        {{ $reviews->links() }}
                        {{-- <nav aria-label="Page navigation ">
                            <ul class="pagination">
                                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                            </ul>
                        </nav> --}}
                    </div>

                </div>
            </div>
        </div>
    @endsection

    @section('script')
    <script>
        function deletereview(id) {
            if (confirm("Are you sure you want to delete?")) {
                $.ajax({
                    url: '{{ route('account.review.deletereview') }}',
                    data: { id: id },
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        window.location.href = '{{ route('account.review') }}';
                    }
                });
            }
        }
    </script>


    @endsection
