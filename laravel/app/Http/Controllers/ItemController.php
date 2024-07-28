<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemsList;
use App\Models\ListModel;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function getItems(Request $request) {
        $items = Item::where('list_id', $request->list_id)->paginate();

        return $this->success([
            'total' => $items->total(),
            'items' => $items->items(),
            'perPage' => $items->perPage(),
            'currentPage' => $items->currentPage(),
        ]);
    }

    public function createItem(Request $request) {

        $item = new Item();
        $item->list_id = $request->list_id;
        $item->user_id = auth()->user()->id;
        $item->name = $request->name;
        $item->quantity = $request->quantity;
        $item->unit = $request->unit;
        $item->save();


        $list = ListModel::find($request->list_id);
        $list->increment('items_count');

        return $this->success([
            'id' => $item->id,
            'name' => $item->name,
            'quantity' => $item->quantity,
            'unit' => $item->unit,
        ]);
    }

    public function makAsDone(Request $request, $id) {
        $item = Item::where([
            'list_id' => $request->list_id,
            'id' => $id
        ])->first();

        $item->done = !$item->done;
        $item->save();

        return $this->success(['item' => $item]);
    }

    public function deleteItem(Request $request, $id) {
        Item::where([
            'list_id' => $request->list_id,
            'id' => $id
        ])->delete();

        $list = ListModel::find($request->list_id);
        $list->decrement('items_count');

        return $this->success();
    }

    public function updateItem(Request $request, $id) {
        $item = Item::where([
            'list_id' => $request->list_id,
            'id' => $id
        ])->first();

        $item->name = $request->name ?? $item->name;
        $item->quantity = $request->quantity ?? $item->quantity;
        $item->unit = $request->unit ?? $item->unit;
        $item->save();

        return $this->success();
    }

    public function getItemsList(Request $request) {

        $items = ItemsList::orderBy('used', 'ASC')->paginate(20);

        return $this->success([
            'total' => $items->total(),
            'items' => $items->items(),
            'perPage' => $items->perPage(),
            'currentPage' => $items->currentPage(),
        ]);
    }
}
