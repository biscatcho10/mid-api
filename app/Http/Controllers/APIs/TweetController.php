<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Http\Resources\SingleTweet;
use App\Models\Tweet;
use App\Http\Resources\TweetResource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     **/
    public function index()
    {
        $tweets = Tweet::paginate(request()->per_page, ['*'], 'page', request()->page);

        $tweets = $tweets->map(function ($tweet) {
            return [
                "tweet" => $tweet->content,
                "date" => Carbon::parse($tweet->created_at)->format('d/m/Y H:i:s'),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Tweets list.',
            'data' => $tweets
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     **/
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|min:4',
        ]);

        $tweet = new Tweet();
        $tweet->content = $request->content;
        $tweet->user_id = auth()->user()->id;
        $tweet->save();

        return response()->json([
            'success' => true,
            'message' => 'Tweet created successfully.',
            'data' => new TweetResource($tweet)
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * */
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|min:4',
        ]);

        $tweet = Tweet::find($id);

        if (!$tweet) {
            return response()->json([
                'success' => false,
                'message' => 'Tweet not found.'
            ], 404);
        }

        $tweet->content = $request->content;
        $tweet->save();

        return response()->json([
            'success' => true,
            'message' => 'Tweet updated successfully.',
            'data' => new TweetResource($tweet)
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     * */
    public function destroy($id)
    {
        $tweet = Tweet::find($id);

        if (!$tweet) {
            return response()->json([
                'success' => false,
                'message' => 'Tweet not found.'
            ], 404);
        }

        $tweet->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tweet deleted successfully.'
        ], 200);
    }

    /**
     * Display the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     * */
    public function show($id)
    {
        $tweet_data = Tweet::with('users')->withCount('users')->find($id);

        if (!$tweet_data) {
            return response()->json([
                'success' => false,
                'message' => 'Tweet not found.'
            ], 404);
        }


        $users = $tweet_data->users;

        $users = $users->map(function ($user) use ($tweet_data) {
            return [
                "tweet_data" => new TweetResource($tweet_data),
                "users" => $user->name,
            ];
        });


        return response()->json([
            'success' => true,
            'message' => 'Tweet detail.',
            'data' => $users
        ], 200);
    }
}
