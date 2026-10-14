<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billing;

class BillingController extends Controller
{
    public function createBilling(Request $request){
        $validated = $request->validate([
            'record_date' => 'required|date',
            'meter_reading' => 'required|numeric',
            'units_used' => 'required|numeric',
            'amount' => 'required|numeric',
            'plot_id' => 'required|integer|exists:plots,id'
        ]);

        $billing = new Billing();
        $billing->record_date =$validated['record_date'];
        $billing->meter_reading =$validated['meter_reading'];
        $billing->units_used =$validated['units_used']; 
        $billing->amount =$validated['amount'];
        $billing->plot_id =$validated['plot_id'];
    

        try {
            $billing->save();
            return response()->json([
                'message' => 'Billing saved successfully.',
                'billing' => $billing
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error trying to save billing.',
                'error' => $e->getMessage()
            ], 500);
        }

    } 

    public function readBilling($id){
        try {
            $billing = Billing::where('id', $id)->first();
            return response()->json([
                'billing' => $billing
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error trying to fetch.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateBilling($id, Request $request)
    {
         $validated = $request->validate([
            'record_date' => 'required|date',
            'meter_reading' => 'required|numeric',
            'units_used' => 'required|numeric',
            'amount' => 'required|numeric',
            'plot_id' => 'required|integer|exists:plots,id'
        ]);

        $billing = Billing::where('id', $id)->first();
        $billing->record_date =$validated['record_date'];
        $billing->meter_reading =$validated['meter_reading'];
        $billing->units_used =$validated['units_used']; 
        $billing->amount =$validated['amount'];
        $billing->plot_id =$validated['plot_id'];

        try {
            $billing->save();
            return response()->json([
                'message' => 'Billing updated successfully.',
                'billing' => $billing
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error trying to update.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteBilling($id)
    {
        try {
            $billing = Billing::where('id', $id)->first();

            if (!$billing) {
                return response()->json("Record not found.");
            }

            $billing->delete();

            return response()->json("Record deleted successfully.");
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error trying to delete.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function searchBilling($name){
        try {
            $billings = Billing::where('record_date', 'LIKE', "%($name)%")->get();
            return response()->json([
                'billings' => $billings
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error trying to fetch.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
