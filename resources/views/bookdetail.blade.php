@extends('layoutss.app')

@section('main')
    <div class="container mt-3 ">
        <div class="row justify-content-center d-flex mt-5">
            <div class="col-md-12">
                <a href="{{ route('home') }}" class="text-decoration-none text-dark ">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp; <strong>Back to books</strong>
                </a>
                <div class="row mt-4">
                    <div class="col-md-4">
                        @if ($book->image != '')
                            <img src="{{ asset('uploads/books/' . $book->image) }}" alt="" class="card-img-top">
                            </a>
                        @else
                            {{-- <img src="https://placehold.co/900x400" alt="" class="card-img-top"></a> --}}
                        @endif
                    </div>
                    @php
                    if ($book->reviews_count > 0) {
                        $avgrating = $book->reviews_sum_rating / $book->reviews_count;
                    } else {
                        $avgrating = 0;
                    }
                     $avrratingper  = ($avgrating*100)/5 ;

                @endphp
                    <div class="col-md-8">
                        @include('layoutss.message')
                        <h3 class="h2 mb-3">{{ $book->title }}</h3>
                        <div class="h4 text-muted">{{ $book->author }}</div>
                        <div class="star-rating d-inline-flex ml-2" title="">
                            <span class="rating-text theme-font theme-yellow">{{ number_format($avgrating, 1) }}</span>
                            <div class="star-rating d-inline-flex mx-2" title="">
                                <div class="back-stars ">
                                    <i class="fa fa-star " aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>

                                    <div class="front-stars" style="{{ $avrratingper }}%">
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                            <span class="theme-font text-muted">({{ ($book->reviews_count > 0) ? $book->reviews_count.'Reviews' : $book->reviews_count.'Review' }} )</span>
                        </div>

                        <div class="content mt-3">
                            {{ $book->description }}
                        </div>

                        <div class="col-md-12 pt-2">
                            <hr>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h2 class="h3 mb-4">Readers also enjoyed</h2>
                            </div>
                            @if ($relatedbooks->isNotEmpty())
                                @foreach ($relatedbooks as $relatedbook)
                                    <div class="col-md-4 col-lg-4 mb-4">
                                        <div class="card border-0 shadow-lg">
                                            <a href="{{ route('book.detail', $relatedbook->id) }}">
                                                @if ($relatedbook->image != '')
                                                    <img src="{{ asset('uploads/books/' . $relatedbook->image) }}"
                                                        alt="" class="card-img-top">

                                            </a>
                                        @else
                                            {{-- <img src="https://placehold.co/900x400" alt="" class="card-img-top"></a> --}}
                                @endif
                                </a>
                                @php
                                if ($relatedbook->reviews_count > 0) {
                                    $avgrating = $relatedbook->reviews_sum_rating / $relatedbook->reviews_count;
                                } else {
                                    $avgrating = 0;
                                }
                                 $avrratingper  = ($avgrating*100)/5 ;

                            @endphp
                                <div class="card-body">
                                    <h3 class="h4 heading"> <a
                                            href="{{ route('book.detail', $relatedbook->id) }}">{{ $relatedbook->title }}
                                    </h3>
                                    <p>{{ $relatedbook->author }}</a></p>
                                    <div class="star-rating d-inline-flex ml-2" title="">
                                        <span class="rating-text theme-font theme-yellow">{{ number_format($avgrating, 1) }}</span>
                                        <div class="star-rating d-inline-flex mx-2" title="">
                                            <div class="back-stars ">
                                                <i class="fa fa-star " aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>

                                                <div class="front-stars" style="{{ $avrratingper }}%">
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <span class="theme-font text-muted">(0)</span> --}}
                                        <span class="theme-font text-muted">({{ ($relatedbook->reviews_count > 0) ? $relatedbook->reviews_count.'Reviews' : $relatedbook->reviews_count.'Review' }} )</span>
                                    </div>
                                </div>
                        </div>
                    </div>
                    @endforeach
                    @endif


                </div>
                <div class="col-md-12 pt-2">
                    <hr>
                </div>
                <div class="row pb-5">
                    <div class="col-md-12  mt-4">
                        <div class="d-flex justify-content-between">
                            <h3>Reviews</h3>
                            <div>
                                {{-- this will check if user is login  --}}
                                @if (Auth::check())
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop">
                                        Add Review
                                    </button>
                                @else
                                    <a class="btn btn-primary" href="{{ route('account.login') }}">Add review</a>
                                @endif


                            </div>
                        </div>


                        @if ($book->reviews->isNotEmpty())
                            @foreach ($book->reviews as $review)
                                <div class="card border-0 shadow-lg my-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="mb-3">{{ $review->user->name }}</h4>
                                                <span class="text-muted">
                                                    {{ \Carbon\Carbon::parse($review->created_at)->format('d M,Y') }}
                                                </span>
                                        </div>
                                        @php
                                            $ratingper = ($review->rating/5)*100;
                                        @endphp

                                        <div class="mb-3">
                                            <div class="star-rating d-inline-flex" title="">
                                                <div class="star-rating d-inline-flex " title="">
                                                    <div class="back-stars ">
                                                        <i class="fa fa-star " aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>

                                                        <div class="front-stars" style="{{ $ratingper }}%">
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="content">
                                            <p>{{ $review->review }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @else
                            <p>Review not found</p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <!-- Modal -->
    <div class="modal fade " id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Review for <strong>Atomic Habits</strong>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {{-- revie pop up section  --}}
                <form action="" id="bookReviewForm" name="bookReviewForm">
                    <input type="hidden" name="book_id" value="{{ $book->id }}">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="" class="form-label">Review</label>
                            <textarea name="review" id="review" class="form-control" cols="5" rows="5" placeholder="Review"></textarea>
                            <p class="invalid-feedback" id="review-error"></p>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Rating</label>
                            <select name="rating" id="rating" class="form-control">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    @endsection

    @section('script')
        <script>
            $("#bookReviewForm").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('book.savereview') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: $("#bookReviewForm").serializeArray(),

                    success: function(response) {
                        if (response.status == false) {
                            var errors = response.errors;
                            if (errors.review) {
                                $("#review").addClass('is-invalid');
                                $("#review-error").html(errors.review);
                            } else {
                                $("#review").removeClass('is-invalid');
                                $("#review-error").html('');
                            }
                        } else {
                            window.location.href = '{{ route('book.detail', $book->id) }}'
                        }
                    }
                });
            });
        </script>
    @endsection
