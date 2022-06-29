<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contract\ContractCreateRequest;
use App\Http\Requests\Contract\ContractUpdateRequest;
use App\Models\Contract;
use Illuminate\Http\Request;

class ContractController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContractCreateRequest $request)
    {

        $request['no_of_months'] = parent::getMonthsCount($request->contract_start, $request->contract_end);

        try {
            Contract::create($request->all());
            $data['error']='false';
            $data['message']='Contract Info! Has Been Created';
        } catch (Exception $e) {
            $data['error']='false';
            $data['message'] =  $e->getMessage();
        }
        return $data;
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
    public function update(ContractUpdateRequest $request, $id)
    {
        $request->no_of_months = parent::getMonthsCount($request->contract_start, $request->contract_end);

        try {
            Contract::updateOrCreate(['id' => $id], $request->all());
            $data['error']='false';
            $data['message']='Contract Info! Has Been Updated';
        } catch (Exception $e) {
            $data['error']='false';
            $data['message'] =  $e->getMessage();
        }
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
