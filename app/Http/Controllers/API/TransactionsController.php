<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Models\Transactions;

class TransactionsController extends Controller
{
//
public function store(Request $request)
{
    //
    try {
        //code...
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|numeric',
            'title' => 'required|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:cr,db',
            'status' => 'required|in:0,1',
            'date' => 'required|date_format:Y-m-d'
        ]);

        if ($validator->fails()) {
            return $this->getResponse(406, $validator->errors()->first());
        }

        $transactions = new Transactions;
        $transactions->id_user = $request->id_user;
        $transactions->id_parent = $request->id_parent;
        $transactions->title = $request->title;
        $transactions->amount = $request->amount;
        $transactions->type = $request->type;
        $transactions->status = $request->status;
        $transactions->date = $request->date;
        $transactions->save();

        return $this->getResponse(200);
    } catch (\Throwable $th) {
        //throw $th;
        return $this->getResponse(500, $th);
    }
}

public function show(Request $request)
{
    //
    try {
        //code...
        $param = $request->header('param');
        if ($param) {
            # code...
            $param = json_decode($param, true);
            $validator = Validator::make($param, [
                'id' => 'numeric',
                'id_user' => 'numeric',
                'title' => 'max:255',
                'min_amount' => 'numeric|min:0',
                'max_amount' => 'numeric|min:0',
                'type' => 'in:cr,db',
                'status' => 'in:0,1',
                'start_date' => 'date_format:Y-m-d',
                'end_date' => 'date_format:Y-m-d'
            ]);
            
            if ($validator->fails()) {
                return $this->getResponse(406, $validator->errors()->first());
            }

            $query = Transactions::query();
            if (isset($param['id'])) $query->where('id', $param['id']);
            if (isset($param['id_parent'])) $query->where('id_parent', $param['id_parent']);
            else $query->whereNull('id_parent');
            if (isset($param['id_user'])) $query->where('id_user', $param['id_user']);
            if (isset($param['title'])) $query->where('title', 'like', '%'.$param['title'].'%');
            if (isset($param['type'])) $query->where('type', $param['type']);
            if (isset($param['status'])) $query->where('status', $param['status']);
            if (isset($param['min_amount'])) $query->where('amount', '>=', $param['min_amount']);
            if (isset($param['max_amount'])) $query->where('amount', '<=', $param['max_amount']);
            if (isset($param['start_date'])) $query->where('date', '>=', $param['start_date']);
            if (isset($param['end_date'])) $query->where('date', '<=', $param['end_date']);
            $transactions = $query->orderBy('id', 'desc')->get();

            return $this->getResponse(200, false, $transactions);
        }
        else {
            $transactions = Transactions::with('children')->whereNull('id_parent')->get();
            return $this->getResponse(200, false, $transactions);
        }
    } catch (\Throwable $th) {
        //throw $th;
        dd($th);
        return $this->getResponse(500, $th);
    }
}

public function update(Request $request)
{
    //
    try {
        //code...
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'id_user' => 'required|numeric',
            'title' => 'required|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:cr,db',
            'status' => 'required|in:0,1',
            'date' => 'required|date_format:Y-m-d'
        ]);

        if ($validator->fails()) {
            return $this->getResponse(406, $validator->errors()->first());
        }

        $transactions = Transactions::where('id', $request->id)->where('id_user', $request->id_user)->first();

        if ($transactions) {
            # code...
            if (isset($request->title)) $transactions->title = $request->title;
            if (isset($request->amount)) $transactions->amount = $request->amount;
            if (isset($request->type)) $transactions->type = $request->type;
            if (isset($request->status)) $transactions->status = $request->status;
            if (isset($request->date)) $transactions->date = $request->date;
            $transactions->save();

            return $this->getResponse(200);
        }
        else {
            return $this->getResponse(404);
        }
    } catch (\Throwable $th) {
        //throw $th;
        return $this->getResponse(500, $th);
    }
}

public function destroy(Request $request)
{
    //
    try {
        //code...
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'id_user' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->getResponse(406, $validator->errors()->first());
        }

        $transactions = Transactions::where('id', $request->id)->where('id_user', $request->id_user)->first();

        if ($transactions) {
            # code...
            $transactions->delete();

            return $this->getResponse(200);
        }
        else {
            return $this->getResponse(404);
        }
    } catch (\Throwable $th) {
        //throw $th;
        return $this->getResponse(500, $th);
    }
}
}
