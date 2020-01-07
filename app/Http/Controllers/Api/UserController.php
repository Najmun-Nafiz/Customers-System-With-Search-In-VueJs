<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Visitor;
use\Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\VisitorResource;
use App\Http\Resources\VisitorCollection;

class UserController extends Controller
{




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     return Visitor::orderBy('id', 'DESC')->paginate(3);
    // }

     public function index()
    {
        return new VisitorCollection(Visitor::orderBy('id', 'DESC')->paginate(3));
    }

    public function search($field, $query)
    {
        return new VisitorCollection(Visitor::where($field,'LIKE',"%$query%")->latest()->paginate(5));
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
            'email'=>'required|email|unique:visitors',
            'password'=>'required',
            'type'=>'required',
            'bio'=>'required',
            //'photo' => 'required|mimes:jpeg,jpg,png',
        ]);


        if($request->get('photo')) 
        {
            $photo = $request->get('photo');
            $name = time().'.' .explode('/', explode(':', substr($photo, 0, strpos($photo, ';')))[1])[1];

            if (!file_exists('imgs/')) {
                mkdir('imgs/',0777,true);
            }
            \Image::make($request->get('photo'))->save(public_path('imgs/').$name);
        }
        else{
            $name = 'default.png';
        }


        $data = new Visitor;
        $data -> name = $request -> name;
        $data -> email = $request -> email;
        $data -> password = Hash::make($request['password']);
        $data -> type = $request -> type;
        $data -> bio = $request -> bio;
        $data -> photo = $name;
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

    public function profile()
    {
        return Auth::user();
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
            'email'=>'required|email|unique:visitors,email,'.$id,
            'password'=>'required',
            'type'=>'required',
            'bio'=>'required',
        ]);

        $data = Visitor::findOrFail($id);

        $photo = $request->get('photo');
        if(isset($photo)) 
        {
            $name = time().'.' . explode('/', explode(':', substr($photo, 0, strpos($photo, ';')))[1])[1];

            if (!file_exists('imgs/')) {
                mkdir('imgs/',0777,true);
            }
            unlink('imgs/'.$data->photo);
            \Image::make($request->get('photo'))->save(public_path('imgs/').$name);
        }
        else{
            $name = 'default.png';
        }

        
        $data -> name = $request -> name;
        $data -> email = $request -> email;
        $data -> password = Hash::make($request['password']);
        $data -> type = $request -> type;
        $data -> bio = $request -> bio;
        $data -> photo = $name;
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
        $data = Visitor::findOrFail($id);
        if(file_exists('img/'.$data->photo)){
            unlink('img/'.$data->photo);
        }
        $data->delete();
    }
}
