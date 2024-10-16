<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AccountController extends Controller
{
    // this method will show register page , shadow@example pas 123123
    public function register()
    {
        return view('account.register');
    }

    // this will save users data
    public function processregister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:5',
            'password_confirmation' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.register')->withInput()->withErrors($validator);
        }

        //user data
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('account.login')
            ->with('Success', 'You have registered successfully');
    }

    // login
    public function login()
    {
        return view('account.login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'email' => 'required|email',
            'password' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect()->route('account.login')->withInput()->withErrors($validator);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('account.profile');
        } else {
            return redirect()->route('account.login')->with('error', 'Email or Password is incorrect');
        }
    }
    // this is to show profile page
    public function profile()
    {
        $user = User::find(Auth::user()->id);
        // dd($users);
        return view('account.profile', compact('user'));
    }

    // this wil update profile page
    public function updateprofile(Request $request)
    {

        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . Auth::user()->id . ',id',

        ];

        if (!empty($request->image)) {
            $rules['image'] = 'image|mimes:jpeg,png,jpg,gif';
        }



        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return redirect()->route('account.profile')->withInput()->withErrors($validator);
        }
        // this updates the records
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        //image upload
        if (!empty($request->image)) {
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imagename = time() . '' . $ext;
            $image->move(public_path('uploads/profile'), $imagename);

            $user->image = $imagename;
            $user->save();

            // create new image instance
            // $manager = new ImageManager(Driver::class);
            // $img = $manager->read(public_path('uploads/profile',$imagename));
            // $img->cover(150, 150);
            // $img->save(public_path('uploads/profile/thumb',$imagename));
        }

        return redirect()->route('account.profile')
            ->with('Success', '  Profile updated successfully');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login');
    }

    //my review section starts here
    public function myreviews(Request $request)
    {
        $reviews = Review::with('book')->where('user_id', Auth::user()->id);
        $reviews =   $reviews->orderBy('created_at', 'DESC');

        if (!empty($request->keyword)) {
            $reviews = $reviews->where('review', 'like', '%' . $request->keyword . '%');
        }

        $reviews =   $reviews->paginate(1);

        return view('account.myreviews.myreviews', compact('reviews'));
    }

   // edit my reviews
    public function editreview($id)
    {
        $review = Review::where([
            'id' => $id,
            'user_id' => Auth::user()->id
        ])->with('book')->first();

        return view('account.myreviews.editmyreview',compact('review'));
    }

    // this will help update my reviews
    public function updatereview($id, Request $request)
    {
        $review = Review::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'review' => 'required',
            'rating' => 'required'

        ]);

        if ($validator->fails()) {
            return redirect()->route('account.myreviews.editmyreview', $id)->withInput()->withErrors($validator);
        }

        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->save();
        // session()->flash('Success', 'Review updated successfully');
        return redirect()->route('account.myreviews')->with('Success', 'Review updated successfully');
    }
}
