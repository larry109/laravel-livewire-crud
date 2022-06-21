<?php

namespace App\Http\Livewire;

use App\Models\car;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;


class CarsList extends Component
{
    public $cars;
    public $state = [];

    public $updateMode = false;

    public function mount()
    {
        $this->cars = car::all();
    }

    private function resetInputFields(){
        $this->reset('state');
    }

    public function store()
    {
        $validator = Validator::make($this->state, [
            'marque' => 'required',
            'prix' => 'required|numeric',
        ])->validate();
//dd($this->state);
//        $this->validate($this->state, [
//            'marque' => 'required',
//            'prix' => 'required|number',
//        ]);

        car::create($this->state);

        $this->reset('state');
        $this->cars = car::all();
    }

    public function edit($id)
    {
        $this->updateMode = true;

        $car = car::find($id);

        $this->state = [
            'id' => $car->id,
            'marque' => $car->marque,
            'prix' => $car->prix,
        ];
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->reset('state');
    }

    public function update()
    {
        $validator = Validator::make($this->state, [
            'marque' => 'required',
            'prix' => 'required|numeric',
        ])->validate();


        if ($this->state['id']) {
            $car = car::find($this->state['id']);
            $car->update([
                'marque' => $this->state['marque'],
                'prix' => $this->state['prix'],
            ]);


            $this->updateMode = false;
            $this->reset('state');
            $this->cars = car::all();
        }
    }

    public function delete($id)
    {
        if($id){
            car::where('id',$id)->delete();
            $this->cars = car::all();
        }
    }


    public function render()
    {
        return view('livewire.cars-list');
    }
}
