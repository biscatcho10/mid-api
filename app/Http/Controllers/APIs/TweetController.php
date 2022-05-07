<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Models\Tweet;
use App\Http\Resources\TweetResource;
use Illuminate\Http\Request;

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     **/
    public function index()
    {
        $tweets = TweetResource::collection(Tweet::paginate(request()->per_page, ['*'], 'page', request()->page));
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
            'user_id' => 'required|exists:users,id',
            'content' => 'required|string|min:4',
        ]);

        $tweet = Tweet::create($request->all());
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
            'user_id' => 'required|exists:users,id',
            'content' => 'required|string|min:4',
        ]);

        $tweet = Tweet::find($id);

        if (!$tweet) {
            return response()->json([
                'success' => false,
                'message' => 'Tweet not found.'
            ], 404);
        }

        $tweet->update($request->all());

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
        $tweet = Tweet::find($id);

        if (!$tweet) {
            return response()->json([
                'success' => false,
                'message' => 'Tweet not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tweet detail.',
            'data' => new TweetResource($tweet)
        ], 200);
    }
}
