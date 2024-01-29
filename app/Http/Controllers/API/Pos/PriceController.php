<?php

namespace App\Http\Controllers\API\Pos;

use App\Http\Controllers\API\Traits\AppUtility;
use App\Http\Controllers\Controller;
use App\Http\Requests\PriceRequest;
use App\Models\Pos\Price;


class PriceController extends Controller
{    use AppUtility;
    public function index()
    {
        $prices=Price::latest()->get();
        return $this->AppResponse(['prices'=>$prices]);
    }

    public function store(PriceRequest $request)
    {
        $price=Price::create($request->validated());
        return $this->AppResponse(['price'=>$price],201);;
    }

    public function show(Price $price)
    {
        return $this->AppResponse(['price'=>$price]);
    }

    public function update(PriceRequest $request, Price $price)
    {
        $price->update($request->validated());
        return AppResponse(['price'=>$price]);
    }

    public function destroy(Price $price)
    {
        $price->delete();
        return $this->appResponse(['message' => 'Price deleted successfully'],203);
    }
}

