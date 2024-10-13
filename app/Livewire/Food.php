<?php

namespace App\Livewire;

use App\Models\Food as FoodModel;
use Livewire\Component;
use App\Models\FoodDetail;
use App\Models\ServingType;

class Food extends Component
{
    public FoodDetail $foodDetail;
    public $servingType = null;
    public int $quantity = 1;
    public float $price = 0.00;
    public $food;
    public ?array $slides;

    public array $headers = [
        [
            'key' => 'name',
            'label' => 'Serving Type',
            'class' => 'p-1'
        ],
        [
            'key' => 'pivot.price',
            'label' => 'Price',
            'class' => 'p-1'
        ],
    ];

    public function mount(FoodDetail $foodDetail)
    {
        $this->foodDetail = FoodDetail::with('foodCategory', 'servingTypes')
            ->where('id', $foodDetail->id)->first();
        $this->servingType = $this->foodDetail->servingTypes->first()->id;
        $this->food = FoodModel::where('food_detail_id', $this->foodDetail->id)
            ->where('serving_type_id', $this->servingType)
            ->first();
        $this->price = $this->foodDetail->servingTypes->first()->pivot->price * $this->quantity;

        if ($this->foodDetail->images != null) {
            $this->slides = $this->foodDetail->images->map(function ($image) {
                return [
                    'image' => asset('storage/' . $image->path),
                ];
            })->toArray();
        }
    }

    public function updatedQuantity()
    {
        $this->updatePrice();
    }

    public function updatedServingType()
    {
        $this->updatePrice();
    }

    public function updatePrice()
    {
        if ($this->servingType == null) {
            return;
        }

        $foodDetail = $this->foodDetail->servingTypes->where('id', $this->servingType)->first();
        if ($foodDetail) {
            $this->food = FoodModel::where('id', $foodDetail->pivot->id)->first();
            $this->price = $foodDetail->pivot->price * $this->quantity;
        }
    }

    public function addToCart()
    {
        $cart = session()->get('cart.foods');

        if (isset($cart[$this->food->id])) {
            $cart[$this->food->id]['quantity'] += $this->quantity;
            $cart[$this->food->id]['price'] += $this->price;
        } else {
            $cart[$this->food->id] = [
                'orderItem' => $this->food->load('foodDetail', 'servingType'),
                'foodDetailId' => $this->food->foodDetail->id,
                'servingTypeId' => $this->food->servingType->id,
                'quantity' => $this->quantity,
                'price' => $this->price,
            ];
        }

        session()->put('cart.foods', $cart);

        $this->dispatch('cart-item-added');
    }


    public function render()
    {
        return view('livewire.food');
    }
}
