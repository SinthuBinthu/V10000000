<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Cart;
use App\Models\Product;
use App\Models\Wishlist;
class WishlistController extends Controller
{
    public function add_to_wishlist(Request $request)
    {
        Cart::instance('wishlist')
            ->add($request->id, $request->name, $request->quantity, $request->price)
            ->associate(Product::class);

        return back();
}
}