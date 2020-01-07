<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customer;
use Illuminate\Support\Facades\Hash;
use\Carbon\Carbon;

class CustomerController extends Controller
{


    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Customer::orderBy('id', 'DESC')->paginate(3);
    }

    public function search($field, $query)
    {
        return new CustomerCollection(Customer::where($field,'LIKE',"%$query%")->latest()->paginate(5));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|email|unique:customers',
            'type'=>'required',
            'address'=>'required',
        ]);


        $image = $request->file('image');
        $slug = str_slug($request->name);
        if (isset($image)) {

            $currentDate = Carbon::now()->toDateString();
            $imagename = $slug.'-'.$currentDate.'-'.uniqid().'.'
            .$image->getClientOriginalExtension();

            if (!file_exists('img')) {
                mkdir('img',0777,true);
            }
            $image->move('img',$imagename);
        }
        else{
            $imagename = 'default.png';
        }

        // if($request->hasFile('image')){
        //     $imagename = $request->image->getClientOriginalName();
        //     $request->image->storeAs('img',$imagename);
        // }

        $data = new Customer;
        $data -> name = $request -> name;
        $data -> email = $request -> email;
        $data -> type = $request -> type;
        $data -> address = $request -> address;
        $data -> photo = $imagename;
        $data-> save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|email|unique:customers,email,'.$id,
            'type'=>'required',
            'address'=>'required',
        ]);

        $data = Customer::findOrFail($id);
        $data -> name = $request -> name;
        $data -> email = $request -> email;
        $data -> type = $request -> type;
        $data -> address = $request -> address;
        $data-> update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Customer::findOrFail($id);
        $data->delete();
    }
}
