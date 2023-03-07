<?php

namespace ChatModule\Http\Controllers;

use ChatModule\Models\Channel;
use ChatModule\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ChannelController extends Controller
{
//    use ChannelData;

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => [
                'required',
                Rule::exists(config('chatmodule.users_table'), 'id')
            ]
        ]);

        try {
            $auth = Auth::user();
            $user = User::find($request->user_id);

            $channel = Channel::where(function ($q) use ($auth, $user) {
                return $q->whereRaw("participants = CAST('" . json_encode([$auth->id, $user->id]) . "' AS JSON)")
                    ->orWhereRaw("participants = CAST('" . json_encode([$user->id, $auth->id]) . "' AS JSON)");
            })->first();

//            dd($channel);

//            dd($channel, $channel->userDelete()->where([
//                ['user_id', Auth::id()],
////                ['is_trashed']
//            ])->first());

            if (is_null($channel)) {
                $channel = new Channel;
                $channel->creator_id = $auth->id;
                $channel->users()->attach([$auth->id, $user->id]);
                $channel->save();
            } /*else {
                $user_delete = $channel->userDelete()->where([
                    ['user_id', Auth::id()],
//                ['is_trashed']
                ])->first();
                if (!is_null($user_delete)) {
                    $user_delete->is_trashed = true;
                    $user_delete->save();
                }
            }*/

            $cover_data = $channel->users()
                ->select('id', 'name')
                ->where('id', '<>', Auth::id())
                ->first();


            $cover_data->profile_img = $cover_data->getFirstMedia('profile_img')->original_url ?? asset('chatmodule/images/profile-image.jpg');
            unset($cover_data->media);

            /*$cover_data = null;
            if ($channel->chat_type == 'individual') {
                $cover_data = $channel->users()
                    ->select('id', 'name')
                    ->where('id', '<>', Auth::id())
                    ->first();

                $cover_data->profile_img = ProfileUtils::profileImg($cover_data, 'profile_image');
            }elseif ($item->chat_type == 'group') {
                $cover_data = $item->group()
                    ->select('id', 'name')
                    ->first();

                $cover_data->profile_img = $cover_data->getFirstMedia('group_media')->original_url ?? null;
            }*/

            unset(
                $channel->updated_at,
                $channel->deleted_at,
                $channel->participants,
            );
            $channel->cover_data = $cover_data;

//            return redirect()->back()
//                ->with('success', 'Conversation created successfully!')
//                ->with('v_data', [
//                    'channel' => $channel,
//                    'cover_data' => $cover_data
//                ]);
            return response()->json([
                'message' => 'Conversation created successfully!',
                'data' => $channel
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, $channel_id)
    {
        try {
            $channel = $this->getChannelDetails($request, $channel_id);

            return response()->json($channel);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function viewedNotification(Request $request, $channel_id)
    {
        try {
            $channel = Channel::find($channel_id);

            if (is_null($channel))
                throw new \Exception('Invalid channel id!');

            $channel->notifications()->where([
                ['viewed', 0],
                ['user_id', Auth::id()],
            ])->update([
                'viewed' => 1
            ]);

            return redirect()->back()->with('success', 'Successfully viewed notifications!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function userDelete(Request $request, $channel_id)
    {
        try {
            $channel = Channel::whereHas('users', function ($q) {
                $q->where('id', Auth::id());
            })->where('id', $channel_id)->first();

            if (is_null($channel))
                throw new \Exception('Invalid channel id!');

//            dd($channel->userDelete);
//            if (!is_null($channel->userDelete))
            $channel->userDelete()->where('user_id', Auth::id())->delete();

            $channel->userDelete()->create([
                'user_id' => Auth::id()
            ]);

            /*$channel->notifications()->where([
                ['viewed', 0],
                ['user_id', Auth::id()],
            ])->update([
                'viewed' => 1
            ]);*/

            return redirect()->back()->with('success', 'Successfully delete conversation!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}