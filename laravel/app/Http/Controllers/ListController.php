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

        $sharedListIds = SharedList::where('user_id', $user->id)->get()->pluck('list_id')->toArray();

        $lists = ListModel::currentUser()
            ->orWhereIn('id', $sharedListIds)
            ->with('sharedLists', 'items')
            ->paginate();

        $listsItems = $lists->items();

        foreach($listsItems as $key => $listItem) {
            $listItem->owner = null;

            $usersIDs = $listItem->sharedLists->pluck('user_id')->toArray();

            $users = User::select('id', 'name', 'avatar', 'email')->whereIn('id', $usersIDs)->get();

            $users = $users->where('id', '!=', $user->id);

            // Dacă lista nu aparține acestui utilizator, adaugă utilizatorul
            if ($listItem->user_id !== $user->id) {
                $ownerList = User::select('id', 'name', 'avatar', 'email')->where('id', $listItem->user_id)->first();
                $users->push($ownerList);
                $listItem->owner = ucfirst($ownerList->name);
            }

            $listItem->sharedList = $users->values();
            $listItem->totalItems = $listItem->items_count;
            $listItem->checkedItems = $listItem->items()->where('done', 1)->count() ?? 0;
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
//        $list->tag = $request->tag;
        $list->save();

        return $this->success([
            'id' => $list->id,
            'name' => $list->name,
            'tag' => $list->tag,
            'totalItems' => 0,
            'checkedItems' => 0
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

        if ($request->remove) {
            SharedList::where('list_id', $id)->currentUser()->delete();
            return $this->success();
        }

        $list = ListModel::currentUser()->find($id);
        $list->delete();

        return $this->success();
    }
}
