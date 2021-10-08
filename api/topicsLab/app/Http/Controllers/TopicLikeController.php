<?php

namespace App\Http\Controllers;
use App\Models\Topic;
use App\Models\POST;
use App\Models\TopicLike;
use Illuminate\Http\Request;

class TopicLikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $topic = Topic::find($request->topicId);
        
        $TopicLike = new TopicLike();
        $TopicLike->user()->associate($user);
        $TopicLike->topic()->associate($topic);
        $TopicLike->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TopicLike  $topicLike
     * @return \Illuminate\Http\Response
     */
    public function show(TopicLike $topicLike)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TopicLike  $topicLike
     * @return \Illuminate\Http\Response
     */
    public function edit(TopicLike $topicLike)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TopicLike  $topicLike
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TopicLike $topicLike)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TopicLike  $topicLike
     * @return \Illuminate\Http\Response
     */
    public function destroy(TopicLike $topicLike)
    {
        //
    }
}
