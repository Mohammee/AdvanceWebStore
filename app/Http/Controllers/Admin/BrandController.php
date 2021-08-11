<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::with('translation')->paginate(5);

       return view('backend.brands.brands')
           ->with('brands' ,$brands);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return $this->edit(new Brand());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BrandRequest $request)
    {
       return  $this->update($request , new Brand());
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
       return view('backend.brands.add_edit')
           ->with('brand' , $brand);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BrandRequest $request, Brand $brand)
    {
        $action = $brand->exists ? 'Update' : 'Create new';

        $request->presist($brand);

        return redirect()->route('admin.brands.index')
            ->with('success_message', "Succeessfully $action brand! ");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();

        return back()->with('success_message', "Brand Delete With Translate Successfully.");
    }

    public function updateBrandStatus(Request $request)
    {

        if($request->ajax())
        {

            $validator = Validator::make($request->all() , [
                'status'  => 'required|numeric|in:0,1',
                'id' => 'required|numeric|exists:App\Models\Brand,id'
            ]);

            if($validator->fails())
            {
                return response()->json( ["Error! Validator Fials." => " {$validator->errors()}  status =  {$request->status}  id = {$request->id}"], 400);
            }

            $status = $request->status ? 0 : 1;
            Brand::find($request->id)->setAttribute('status' , $status)->save();

            return  response()->json(['status' => $status ,
                'brand_id' => $request->id]);
        }

        return response()->json(['Errors!'] , 400);
    }

}
