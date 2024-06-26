<?php

namespace App\Http\Controllers;

use App\Models\ListModel;
use App\Models\SharedList;
use App\Models\User;
use Illuminate\Http\Request;

class SharedListController extends Controller
{
    public function share(Request $request) {

        $userID = User::where('email', $request->email)->value('id');
        if (!$userID) {
            return $this->success();
        }

        $sharedList = new SharedList();
        $sharedList->list_id = $request->listId;
        $sharedList->user_id = $userID;
        $sharedList->save();

        return $this->success();
    }

    public function getSharedListUsers(Request $request) {
        $sharedListUserIDs = SharedList::where('list_id', $request->list_id)->get()->pluck('user_id')->toArray();

        $users = User::select(['name', 'avatar', 'email'])->whereIn('id', $sharedListUserIDs)->get();

        return $this->success($users);
    }

    public function removeShare(Request $request) {

        $hasUserList = ListModel::where([
            'id' => $request->listId,
            'user_id' => auth()->user()->id
        ])->value('user_id');

        if (!$hasUserList) {
            return $this->fail(['message' => "Just the owner can remove someone from list."]);
        }

        SharedList::where(['list_id' => $request->listId, 'user_id' => $request->user_id])->delete();

        return $this->success(['message' => 'Someone was removed from the list.']);

    }
}
