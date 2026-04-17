<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
     // GET /referrals — list all
    public function index()
    {
        $referrals = Referral::orderBy('name')->get();
        return response()->json(['success' => true, 'data' => $referrals]);
    }
 
    // POST /referrals — create
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'type'  => 'required|in:referral,agent,staff',
        ]);
 
        $referral = Referral::create($request->only('name', 'email', 'phone', 'type'));
 
        return response()->json([
            'success' => true,
            'message' => 'Referral created successfully.',
            'data'    => $referral,
        ], 201);
    }
 
    // PUT /referrals/{id} — update
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'type'  => 'required|in:referral,agent,staff',
        ]);
 
        $referral = Referral::findOrFail($id);
        $referral->update($request->only('name', 'email', 'phone', 'type'));
 
        return response()->json([
            'success' => true,
            'message' => 'Referral updated successfully.',
            'data'    => $referral,
        ]);
    }
 
    // DELETE /referrals/{id} — soft delete
    public function destroy($id)
    {
        Referral::findOrFail($id)->delete();
 
        return response()->json([
            'success' => true,
            'message' => 'Referral deleted.',
        ]);
    }
}
