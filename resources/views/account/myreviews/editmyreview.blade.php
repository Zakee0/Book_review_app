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
                        Edit Reviews
                    </div>
                    <div class="card-body pb-3">
                        <form action="{{ route('account.myreviews.updatereview', $review->id) }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Book</label>
                                <div>
                                    <strong>
                                        {{-- {{ old('review',$review->book->title) }} --}}
                                        {{ $review->book->title }}
                                    </strong>
                                </div>

                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Review</label>
                                <textarea placeholder="review"
                                    class="form-control @error('review') is-invalid
                                @enderror " name="review"
                                    id="review">{{ old('review', $review->review) }}</textarea>
                                @error('review')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="rating" class="form-label">Rating</label>
                                <select name="rating"
                                    class="form-control  @error('rating') is-invalid
                        @enderror"
                                    id="status">
                                    <option value="1" {{ $review->status == 1 ? 'selected' : '' }}>1</option>
                                    <option value="2" {{ $review->status == 2 ? 'selected' : '' }}>2</option>
                                    <option value="3" {{ $review->status == 3 ? 'selected' : '' }}>3</option>
                                    <option value="4" {{ $review->status == 4 ? 'selected' : '' }}>4</option>
                                    <option value="5" {{ $review->status == 5 ? 'selected' : '' }}>5</option>
                                </select>
                                @error('status')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>

                            <button class="btn btn-primary mt-2">Update</button>
                        </form>
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
