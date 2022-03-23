<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Inertia\Inertia;
use Redirect;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('Invoices');
    }
    public function store(Request $request)
    {dd($request->all());
        if ($request->canceled == "on") {
            $annulled = true;
        }else{
            $annulled = false;
        }

        if ($request->goa == "on") {
            $goa = true;
        }else{
            $goa = false;
        }

        try {
            $service = Service::create([
                'user_id' => auth()->user()->id,
                'annuled' => $annulled,
                'goa' => $goa,
                'service' => $request->service,
                'enterprise' => $request->enterprise,
                'date_Time' => $request->dateTime,
                'timeservices' => $request->time_services,
                'base' => $request->enterprise_own,
                'destination' => $request->searchAddress,
                'destination_lat' => $request->lat,
                'destination_long' => $request->lng,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'file' => auth()->user()->id,
                'flair' => $request->flair,
                'essence' => $request->essence,
            ]);    
        }  catch (Exception $e) {
            dd($e->getMessage());
        }
        

       return Redirect::route('history.invoices')->with(['id'=>$service->id, 'message' => 'Inscription réussie', 'code' => 200, 'status' => 'success']);  
    }
    public function history()
    {
        $services = Service::where("user_id",auth()->user()->id)->get();
        $services->load('user.profile');

        return Inertia::render('Profile/History', compact('services'));
    }
}
