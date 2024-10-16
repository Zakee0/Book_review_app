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
                        <form action="{{ route('account.review.updatereview',$review->id) }}" method="post" >
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Review</label>
                                <textarea placeholder="review" class="form-control @error('review') is-invalid
                        @enderror " name="review" id="review">{{ old('review', $review->review) }}</textarea>
                                @error('review')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" class="form-control  @error('review') is-invalid
                        @enderror" id="status">
                                    <option value="1" {{ $review->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $review->status == 0 ? 'selected' : '' }}>Block</option>
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
