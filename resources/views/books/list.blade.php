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
                        Books
                    </div>
                    <div class="card-body pb-0">

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('books.create') }}" class="btn btn-primary">Add Book</a>
                            {{-- search bar  --}}
                            <form action="" method="get">
                                <div class="d-flex">
                                    <input type="text" class="form-control" value="{{ Request::get('keyword') }}" name="keyword" placeholder="keyword">

                                    <button type="submit" class="btn btn-primary ms-2">Submit</button>
                                    <a href="{{ route('books.index') }}" class="btn btn-secondary" >Clear</a>
                                </div>
                            </form>

                        </div>
                        <table class="table  table-striped mt-3">
                            <thead class="table-dark">
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                    <th width="150">Action</th>
                                </tr>
                            <tbody>
                                @if ($books->isNotEmpty())
                                    @foreach ($books as $book)
                                    @php
                                    if ($book->reviews_count > 0) {
                                        $avgrating = $book->reviews_sum_rating / $book->reviews_count;
                                    } else {
                                        $avgrating = 0;
                                    }
                                     $avrratingper  = ($avgrating*100)/5 ;

                                @endphp
                                        <tr>
                                            <td>{{ $book->title }}</td>
                                            <td>{{ $book->author }}</td>
                                            <td>{{ $avgrating }}  <span class="theme-font text-muted">({{ ($book->reviews_count > 0) ? $book->reviews_count.'Reviews' : $book->reviews_count.'Review' }} )</span></td>
                                            <td>
                                                @if ($book->status == 1)
                                                    <span class="text-success">Active</span>
                                                @else
                                                    <span class="text-danger">Blocked</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-success btn-sm"><i
                                                        class="fa-regular fa-star"></i></a>
                                                <a href="{{ route('books.edit',$book->id) }}" class="btn btn-primary btn-sm"><i
                                                        class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <a href="javascript:void(0);" onclick="deletebook({{ $book->id }});" class="btn btn-danger btn-sm">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>

                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">Books not found</td>
                                    </tr>
                                @endif



                            </tbody>
                            </thead>
                        </table>
                        @if ($books->isNotEmpty())
                            {{ $books->links() }}
                        @endif

                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
function deletebook(id) {
    if (confirm('Are you sure you want to delete this book?')) {
        $.ajax({
            url: '{{ route('books.destroy', '') }}/' + id,  // Append the id to the URL
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status === 'true') {
                    alert(response.message);
                    window.location.href = '{{ route('books.index') }}';  // Redirect to the index after successful deletion
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                alert('An error occurred while trying to delete the book.');
            }
        });
    }
}


    </script>
@endsection
