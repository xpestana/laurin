<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Service;
use Inertia\Inertia;
use Redirect;
use Image;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('Invoices');
    }
    public function FileName($file)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $images=array("jpg", "jpeg", "png");
        
        $pin = mt_Rand(1000000, 9999999)
               . mt_Rand(1000000, 9999999)
               . $characters[Rand(0, strlen($characters) - 1)];
        
        $nameFile = $file->getClientOriginalName();

        $extension = pathinfo($nameFile, PATHINFO_EXTENSION);
        $fileName  = 'file_'.$pin.'.'.$extension;

        $response = [
            'extension' => $extension,
            'fileName' => $fileName,
        ];

        return $response;
    }
    public function store(Request $request)
    {
         
          
        if ($request->canceled == "on" || $request->goa == "on") {
            $validator = $this->validate($request, [
                'enterprise'        => 'required|string',
            ]);
        }else{
            $validator = $this->validate($request, [
                'enterprise'        => 'required|string',
                'service'           => 'required|string',
                'dateTime'          => 'required|string',
                'time_services'     => 'required|string',
                'enterprise_own'    => 'required',
                'searchAddress'     => 'required|string',
                'name'              => 'required|string',
                'phone'             => 'required|string',
                'email'             => 'required|string',
                'file'              => 'nullable|image|mimes:jpg,jpeg,png,gif,svg',
            ]);


            /*** Direcciones ***/
            if ($request->enterprise_own == "1") {
                $enterprise_own = "1620 rang Saint Eduard, St-Liboire QC J0H 1R0";
            }
            if ($request->enterprise_own == "2") {
                $enterprise_own = "1200 Rue Daniel - Johnson O, Saint-Hyacinthe, QC J2S 7K7";
            }
            $address = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".urlencode($enterprise_own)."&destinations=".urlencode($request->searchAddress)."&key=AIzaSyDsK-qVLYQGl_YSAHFQAmwxCqpCt-C0CZc";
            $response = Http::get($address)->collect();
            if (!$response->isEmpty()) {
                $km = $response["rows"][0]["elements"][0]["distance"]["value"]/1000;
            }else{
                return Redirect::back()->withErrors(['msg' => 'Erreur, Les adresses ne correspondent pas!']);
            }
        }

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

        $image = $request->file;

        //falta destination lat y destination log
        if ($request->file) {
            $Path = public_path('storage/img/');
            $pathName = '/';

            if (!file_exists($Path)) {
                mkdir($Path, 777, true);
            }

            $nameFile =$this->FileName($image); //nombre de archivo original
            $imgFileOriginal = Image::make($image->getRealPath());
            $imgFileOriginal->save($Path.$nameFile['fileName']);
            $name_image = $pathName.$nameFile['fileName'];
        }else{
            $name_image =null;
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
                'file' => $name_image,
                'flair' => $request->flair,
                'essence' => $request->essence,
                'km' => $km,
            ]);    
        }  catch (Exception $e) {
            dd($e->getMessage());
        }
        

       return Redirect::route('history.invoices')->with(['id'=>$service->id, 'message' => 'Inscription réussie', 'code' => 200, 'status' => 'success']);  
    }
    public function history()
    {   
        if (auth()->user()->getRoleNames()->first() == 'Driver') {
            $services = Service::where("user_id",auth()->user()->id)->paginate(10);
            $services->load('user.profile');

            return Inertia::render('Profile/History', compact('services'));
        }else{
            $services = Service::with('user.profile')->paginate(10);

            return Inertia::render('Profile/HistoryProfile', compact('services'));
        }
        
    }
}
