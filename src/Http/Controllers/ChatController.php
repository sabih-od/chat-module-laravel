<?php

namespace ChatModule\Http\Controllers;


use ChatModule\Models\Channel;
use ChatModule\Models\User;
use ChatModule\Rules\isValidMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::query()->whereNotIn('id', [Auth::id()])->get();

        return view('chatmodule::chat.index', compact('users'));
    }

    public function chatMessages(Request $request)
    {
        $channel = Channel::select('id', 'participants')
            ->whereHas('users', function ($q) {
                $q->where('id', Auth::id());
            })->where('id', $request->channel_id)
            ->first();

        if (is_null($channel))
            return [];

        $query = $channel
            ->messages()
            ->select('id', 'content', 'sender_id', 'created_at', 'channel_id')
//            ->with(['sender' => function ($q) {
//                $q->select('id', 'name');
//            }])
            ->whereDoesntHave('userDelete', function ($q) {
                $q->where('user_id', Auth::id());
            });

        $userDelete = $channel->userDelete()->where('user_id', Auth::id())->first();
        if (!is_null($userDelete)) {
            $query = $query->where('created_at', '>', $userDelete->created_at);
        }

        return $query
            ->latest()
            ->simplePaginate(15)
            ->through(function ($item, $key) {
//                if (!is_null($item->sender))
//                    $item->sender->profile_img = $item->sender->getFirstMedia('profile_image')->original_url ?? null;

                $media = $item->getFirstMedia('media');
                $item->file = $media ? [
                    'mime_type' => $media->mime_type,
                    'url' => $media->original_url,
                ] : null;

                unset(
//                    $item->sender_id,
                    $item->channel_id,
                );

                return $item;
            });
    }

    public function chatMessageStore(Request $request)
    {
        $channel = null;
        $rule = ['channel_id' => [
            'required',
            function ($attribute, $value, $fail) use (&$channel) {
                if (!$value) return;

                $channel = Channel::where('id', $value)
                    ->whereHas('users', function ($q) {
                        $q->where('id', Auth::id());
                    })->first();

                if (is_null($channel)) {
                    $fail("Invalid channel id!");
                }
            }
        ]];

        if ($request->has('file') && !is_null($request->file)) {
            $rule['file'] = [new isValidMedia];
        } else {
            $rule['message'] = ['required'];
        }

        $request->validate($rule);
        try {
            $auth = Auth::user();
//            $friend = $channel->users()->where('id', '<>', $auth->id)->first();

            $message = $channel->messages()->create([
                'sender_id' => $auth->id,
                'content' => $request->message ?? null
            ]);
            $media = null;

            if ($request->has('file') && !is_null($request->file)) {
                $message->addMediaFromRequest('file')
                    ->toMediaCollection('media');

                $f_media = $message->getFirstMedia('media');
                $media = $f_media ? [
                    'mime_type' => $f_media->mime_type,
                    'url' => $f_media->original_url,
                ] : null;
            }

//            $channel->last_message_at = $message->created_at;
            $channel->save();

//            event(new NewMessage($channel->id, $message));

//            $participants = collect($channel->participants)->filter(function ($item) {
//                return $item != Auth::id();
//            })->all();

//            foreach ($participants as $user_id) {
//                $body = "You have a new message!";
//                dispatch(new CreateNotification($channel->id, 'channel', $user_id, $body));
//                event(new NewNotification($user_id, $body, 'channel', $channel->id, $channel->last_message_at));
//            }

            return response()->json([
                'message' => 'Message stored it successfully!',
                'data' => [
                    'id' => $message->id,
                    'media' => $media
                ]
            ]);

//            return redirect()->route('chatIndex')
//                ->with('success', 'Message stored it successfully!')
//                ->with('v_data', [
//                    'id' => $message->id,
//                    'media' => $media
//                ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
//            return redirect()->route('chatIndex')->with('error', $e->getMessage());
        }
    }
}
