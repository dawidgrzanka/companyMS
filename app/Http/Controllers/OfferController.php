<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\OfferItem;
use Illuminate\Http\Request;
use App\Models\Item;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::all();
        return view('offers.index', compact('offers'));
    }

    public function create()
    {
        $availableItems = Item::all();
        return view('offers.create', compact('availableItems'));
    }

    public function store(Request $request)
    {
        $offer = Offer::create($request->only(['title', 'status', 'notes']));
        foreach ($request->items as $item) {
            $offerItem = new OfferItem($item);
            if (!empty($item['id'])) {
                $dbItem = Item::find($item['id']);
                $offerItem->description = $dbItem->description;
                $offerItem->price = $dbItem->price;
            }
            $offer->items()->save($offerItem);
        }
        return redirect()->route('offers.index');
    }

    public function show(Offer $offer)
    {
        $offer->load('items');
        return view('offers.show', compact('offer'));
    }

    public function edit(Offer $offer)
    {
        $availableItems = Item::all();
        return view('offers.edit', compact('offer', 'availableItems'));
    }

    public function update(Request $request, Offer $offer)
    {
        $offer->update($request->only(['title', 'status', 'notes']));
        $offer->items()->delete();
        foreach ($request->items as $item) {
            $offerItem = new OfferItem($item);
            if (!empty($item['id'])) {
                $dbItem = Item::find($item['id']);
                $offerItem->description = $dbItem->description;
                $offerItem->price = $dbItem->price;
            }
            $offer->items()->save($offerItem);
        }
        return redirect()->route('offers.index');
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();
        return redirect()->route('offers.index');
    }
}
