<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    // this method will show home page
    public function index(Request $request)
    {
        // with count will help with counting reviews
        $books = Book::withCount('reviews')->withSum('reviews','rating')->orderBy('created_at', 'DESC');

        if (!empty($request->keyword)) {
            $books->where('title', 'like', '%' . $request->keyword . '%');
        }


        $books =   $books->where('status', 1)->paginate(4);
        // dd($books);

        return view('home', compact('books'));
    }

    // this method will show book detail page

    public function detail($id)
    {
        // reviews comes from relations
        $book = Book::with([ 'reviews.user','reviews' => function($query){
            $query->where('status' , 1);
        }])->withCount('reviews')->withSum('reviews','rating')->findOrFail($id);
        // dd($book);
        if ($book->status == 0) {
            abort(404);
        }

        $relatedbooks = Book::where('status', 1)
        ->withCount('reviews')->withSum('reviews','rating')
        ->take(3)->where('id', '!=', '$id')
        ->inRandomOrder()->get();
        // dd($relatedbooks);


        return  view('bookdetail', compact('book', 'relatedbooks'));
    }

    //this will save review

    public function savereview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'review' => 'required|min:10',
            'rating' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        //Apply user review count function
        $countreview = Review::where('user_id', Auth::user()->id)->where('book_id', $request->book_id)->count();
        if ($countreview > 0) {
            session()->flash('error', 'You already submitted the review');
            return response()->json([
                'status' => true,
                'errors' => $validator->errors()
            ]);
        }

        $review = new Review();
        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->user_id = Auth::user()->id;
        $review->book_id = $request->book_id;
        $review->save();

        session()->flash('Success', 'Review added successfully');

        return response()->json([
            'status' => true,
            'errors' => $validator->errors()
        ]);
    }
}
