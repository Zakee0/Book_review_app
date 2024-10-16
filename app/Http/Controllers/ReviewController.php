<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Colors\Rgb\Channels\Red;
use PhpParser\Node\Expr\FuncCall;

class ReviewController extends Controller
{
    //
    public function index(Request $request)
    {
        $reviews = Review::with('book', 'user')->orderBy('created_at', 'DESC');

        if (!empty($request->keyword)) {
            $reviews = $reviews->where('review', 'like', '%' . $request->keyword . '%');
        }

        $reviews = $reviews->paginate(1);
        return view('reviews.list', compact('reviews'));
    }

    //this will edit
    public function edit($id)
    {
        $review = Review::findOrFail($id);
        return view('reviews.edit', compact('review'));
    }

    //update method
    public function updatereview($id, Request $request)
    {
        $review = Review::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'review' => 'required',
            'status' => 'required'

        ]);

        if ($validator->fails()) {
            return redirect()->route('account.review.edit', $id)->withInput()->withErrors($validator);
        }

        $review->review = $request->review;
        $review->status = $request->status;
        $review->save();
        // session()->flash('success', 'Review updated successfully');
        return redirect()->route('account.review')->with('Success','Review updated successfully');
    }

    public function deletereview(Request $request)
    {
        $id = $request->id;

        // Find the review or fail with 404
        $review = Review::findOrFail($id);

        // Delete the review and set success message
        $review->delete();

        session()->flash('Success', 'Review deleted successfully');

        return response()->json([
            'status' => 'true',
        ]);
    }

    //myreviews

}
