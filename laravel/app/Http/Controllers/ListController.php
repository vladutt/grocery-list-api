<?php

namespace App\Http\Controllers;

use App\Models\ListModel;
use App\Models\SharedList;
use App\Models\User;
use Illuminate\Http\Request;

class ListController extends Controller
{
    public function getLists(Request $request) {

        $user = $request->user();

        $sharedListIds = SharedList::where('user_id', $user->id)->get()->pluck('id')->toArray();

        $lists = ListModel::currentUser()
            ->orWhereIn('user_id', $sharedListIds)
            ->with('sharedLists', 'items')
            ->paginate();

        $listsItems = $lists->items();

        foreach($listsItems as $listItem) {
            $usersIDs = $listItem->sharedLists->pluck('user_id')->toArray();

            $users = User::select('id', 'name', 'avatar', 'email')->whereIn('id', $usersIDs)->get();
            $listItem->sharedList = $users;
            $listItem->totalItems = $listItem->items_count;
            $listItem->checkedItems = $listItem->items->where('done', 1)->count();
        }

        return $this->success([
            'total' => $lists->total(),
            'items' => $listsItems,
            'perPage' => $lists->perPage(),
            'currentPage' => $lists->currentPage(),
        ]);

    }

    public function createList(Request $request) {

        $list = new ListModel();
        $list->user_id = auth()->user()->id;
        $list->name = $request->name;
        $list->tag = $request->tag;
        $list->save();

        return $this->success([
            'id' => $list->id,
            'name' => $list->name,
            'tag' => $list->tag,
        ]);
    }

    public function updateList(Request $request, $id) {

        $list = ListModel::currentUser()->find($id);
        $list->name = $request->name;
        $list->tag = $request->tag;
        $list->save();

        return $this->success([
            'id' => $list->id,
            'name' => $list->name,
            'tag' => $list->tag,
        ]);
    }

    public function deleteList(Request $request, $id) {
        $list = ListModel::currentUser()->find($id);
        $list->delete();

        return $this->success();
    }
}
