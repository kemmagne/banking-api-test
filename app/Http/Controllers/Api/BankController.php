<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateBanktRequest;
use App\Http\Requests\EditPostRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;

class BankController extends Controller
{
    public function index(){
        try{
    
            return response()->json([
                'status_code'=>200,
                'status_message'=>'All status has been feched',
                'data' => Bank::all()
            ]);
          } catch(Exception $e) {
                 return response()->json($e);
            }
        
    }


    public function store(CreateBanktRequest $request){
        try{
        $bank = new Bank();

        $bank->acountNumber = $request->acountNumber;
        $bank->amount = $request->amount;
        $bank->clesRib = $request->clesRib;
        $bank->user_id = auth()->user()->id;
        $bank->save();

        return response()->json([
            'status_code'=>200,
            'status_message'=>'The Bank account has been added',
            'data' => $bank
        ]);
      } catch(Exception $e) {
             return response()->json($e);
        }
    }

    public function update(EditBankRequest $request, Bank $bank){
        
        try{
         //  $bank = Bank::find($id);

            $bank->acountNumber = $request->acountNumber;
            $bank->amount = $request->amount;
            $bank->clesRib = $request->clesRib;
            if($bank->user_id == auth()->user()->id){
                $bank->save();
            }else{
                return response()->json([
                    'status_code' => 422,
                    'status_message' => 'vous etes sur'
                ]);
            }
           
            return response()->json([
                'status_code'=>200,
                'status_message'=>'Le bank a ete ajoute',
                'data' => $bank
            ]);
          } catch(Exception $e) {
                 return response()->json($e);
            }
    }

    public function transaction(Bank $acountNumber){
        $amount = (int) request()->$acountNumber;
        $sender = User::first();
        $receiver = User::orderByDesc('id')->first();
        
        try {
            \DB::transaction(function () use ($amount, $sender, $receiver) {
                // if the amount to be send is more than the sender balance, 
                // it will throw error and no balance changed.
                // you can catch the SQL error with your own error handling

                $sender->update(['balance' => $sender->balance - $amount]);

                if ($receiver->balance + $amount > 200) {
                    throw new \Exception('Receiver\'s balance should not exceed 200');
                }

                $receiver->update(['balance' => $receiver->balance + $amount]);
            });
        } 
        catch (\Exception $e) {
            return back()->withInput()->with('notif', $e->getMessage());
        }

        return back();
    }
}
