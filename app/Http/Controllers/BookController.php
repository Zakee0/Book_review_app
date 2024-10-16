<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    //
    public function index(Request $request)
    {

        // searchbar code is here
        $books = Book::orderBy('created_at', 'DESC');

        if (!empty($request->keyword)) {
            $books->where('title', 'like', '%' . $request->keyword . '%');
        }

        $books = $books->withCount('reviews')->withSum('reviews','rating')->paginate(4);

        return view('books.list', compact('books'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {

        $rules = [
            'title' => 'required|min:5',
            'author' => 'required|min:3',
            'status' => 'required',

        ];

        if (!empty($request->image)) {
            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('books.create')->withInput()->withErrors($validator);
        }

        //save book in DB
        $book = new Book();
        $book->title = $request->title;
        $book->description = $request->description;
        $book->author = $request->author;
        $book->status = $request->status;
        $book->save();

        //image upload function
        if (!empty($request->image)) {
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imagename = time() . '' . $ext;
            $image->move(public_path('uploads/books'), $imagename);

            $book->image = $imagename;
            $book->save();

            // create new image instance
            // $manager = new ImageManager(Driver::class);
            // $img = $manager->read(public_path('uploads/profile',$imagename));
            // $img->cover(150, 150);
            // $img->save(public_path('uploads/profile/thumb',$imagename));
        }

        return redirect()->route('books.index')
            ->with('Success', 'Books added successfully');
    }

    public function edit($id)
    {

        $book = Book::findOrFail($id);
        return view('books.edit', compact('book'));
    }

    public function update($id, Request $request)
    {
        $book = Book::findOrFail($id);

        $rules = [
            'title' => 'required|min:5',
            'author' => 'required|min:3',
            'status' => 'required',

        ];

        if (!empty($request->image)) {
            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('books.edit', $book->id)->withInput()->withErrors($validator);
        }

        //update book in DB
        // $book = new Book();
        $book->title = $request->title;
        $book->description = $request->description;
        $book->author = $request->author;
        $book->status = $request->status;
        $book->save();

        //image upload function
        if (!empty($request->image)) {

            // this will delete old image
            File::delete(public_path('uploads/books' . $book->image));

            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imagename = time() . '' . $ext;
            $image->move(public_path('uploads/books'), $imagename);

            $book->image = $imagename;
            $book->save();

            // create new image instance
            // $manager = new ImageManager(Driver::class);
            // $img = $manager->read(public_path('uploads/profile',$imagename));
            // $img->cover(150, 150);
            // $img->save(public_path('uploads/profile/thumb',$imagename));
        }

        return redirect()->route('books.index')
            ->with('Success', 'Books updated successfully');
    }


    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        if (!$book) {
            session()->flash('error', 'Book not found');
            return response()->json([
                'status' => 'false',
                'message' => 'Book not found',
            ]);
        }

        File::delete(public_path('uploads/books/' . $book->image));
        $book->delete();

        session()->flash('success', 'Book deleted successfully');
        return response()->json([
            'status' => 'true',
            'message' => 'Book deleted successfully',
        ]);
    }

}
