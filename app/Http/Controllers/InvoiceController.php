<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;
use App\Models\Service;
use Inertia\Inertia;
use Redirect;
use Image;
use Carbon\Carbon;
class InvoiceController extends Controller
{
    protected $fpdf;

     public function __construct()
    {
        $this->fpdf = new Fpdf;
    }
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
    public function annuled($service){
         $amount_col = 0;
            if ($service->enterprise == "PDG" || $service->enterprise == "URGENTLY") {
              $amount_col = 25;
            }
            if ($service->enterprise == "AXA") {
              $amount_col = 60 + (3 * ($service->km - 5));
            }
            if ($service->enterprise == "GSA") {
              $amount_col = 22 + (2.75 * ($service->km - 5));
            }
        return $amount_col;
    }
    public function goa($service){
         $amount_col = 0;
          if ($service->enterprise == "PDG") {
              $amount_col = 45;
          }
          if ($service->enterprise == "ALLSTATE") {
              $amount_col = 55;
          }
          if ($service->enterprise == "GSA") {
              $amount_col = 35;
          }
          if ($service->enterprise == "ASSISTEL") {
              $amount_col = 30;
          }
          if ($service->enterprise == "URGENTLY") {
            if ($service->service == "T1" || $service->service == "T3" || $service->service == "T4" || $service->service == "T7" ) {
              $amount_col = 50 + (3 * ($service->km - 5));
            }
            if ($service->service == "T5" || $service->service == "T6" || $service->service == "T8") {
              $amount_col = 80 + (3 * ($service->km - 5));
            }
          }

          return $amount_col;
    }
    public function firstT($service, $feriado, $nocturno){

        $amount_col = 0;
        $km = 0;
        $Tkmxh = 0;
        $timeXtra = $service->timeservices-30;

          if ($service->enterprise == "PDG") {
              $base = 48;
              $km = $service->km - 5;
              $Tkmxh = 2.75 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.25;
                $amount_col = $amount_col + ($timeXtra * 1.25);
              }
              if ($nocturno == 1) {
                $noct = 20;
                $amount_col = $amount_col + 20;
              }
              if ($feriado == 1) {
                $fer = 20;
                $amount_col = $amount_col + 20;
              }
          }
          if ($service->enterprise == "ALLSTATE") {
              $base = 45;
              $km = $service->km - 10;
              $Tkmxh = 2 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.17;
                $amount_col = $amount_col + ($timeXtra * 1.17);
              }
              if ($nocturno == 1) {
                $noct = 12;
                $amount_col = $amount_col + 12;
              }
              if ($feriado == 1) {
                $fer = 12;
                $amount_col = $amount_col + 12;
              }
          }
          if ($service->enterprise == "AXA") {
              $base = 60;
              $km = $service->km - 5;
              $Tkmxh = 3 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.67;
                $amount_col = $amount_col + ($timeXtra * 1.67);
              }
              if ($nocturno == 1) {
                $noct = 20;
                $amount_col = $amount_col + 20;
              }
              if ($feriado == 1) {
                $fer = 20;
                $amount_col = $amount_col + 20;
              }
          }
          if ($service->enterprise == "GSA") {
              $base = 50;
              $km = $service->km - 5;
              $Tkmxh = 2.75 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.25;
                $amount_col = $amount_col + ($timeXtra * 1.25);
              }
              if ($nocturno == 1) {
                $noct = 12;
                $amount_col = $amount_col + 12;
              }
              if ($feriado == 1) {
                $fer = 12;
                $amount_col = $amount_col + 12;
              }
          }
          if ($service->enterprise == "ASSISTEL") {
              $base = 60;
              $km = $service->km - 5;
              $Tkmxh = 3 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.17;
                $amount_col = $amount_col + ($timeXtra * 1.17);
              }
              if ($nocturno == 1) {
                $noct = 12;
                $amount_col = $amount_col + 12;
              }
              if ($feriado == 1) {
                $fer = 12;
                $amount_col = $amount_col + 12;
              }
          }
          if ($service->enterprise == "ROAD") {
              $base = 52.5;
              $km = $service->km - 10;
              $Tkmxh = 2.5 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.17;
                $amount_col = $amount_col + ($timeXtra * 1.17);
              }
              if ($nocturno == 1) {
                $noct = 12;
                $amount_col = $amount_col + 12;
              }
              if ($feriado == 1) {
                $fer = 12;
                $amount_col = $amount_col + 12;
              }
          }
          if ($service->enterprise == "ASSISTENZA") {
              $base = 52.5;
              $km = $service->km - 5;
              $Tkmxh = 2.5 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.17;
                $amount_col = $amount_col + ($timeXtra * 1.17);
              }
              if ($nocturno == 1) {
                $noct = 12;
                $amount_col = $amount_col + 12;
              }
              if ($feriado == 1) {
                $fer = 12;
                $amount_col = $amount_col + 12;
              }
          }
          if ($service->enterprise == "URGENTLY") {
              $base = 50;
              $km = $service->km - 5;
              $Tkmxh = 3 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.67;
                $amount_col = $amount_col + ($timeXtra * 1.67);
              }
              if ($nocturno == 1) {
                $noct = 20;
                $amount_col = $amount_col + 20;
              }
              if ($feriado == 1) {
                $fer = 20;
                $amount_col = $amount_col + 20;
              }
          }

        $hour = Carbon::parse($service->date_Time)->format('h:m a');
        $date = Carbon::parse($service->date_Time)->format('d/M/Y');
        $this->fpdf->Ln();$this->fpdf->Ln();
        $this->fpdf->Cell(90,7,utf8_decode("Enterprise: ". $service->enterprise),1,0,'L');
        $this->fpdf->Cell(90,7,$base + $Tkmxh." $",1,0,'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(90,7,utf8_decode("Heure de l'événement ". $hour),1,0,'L');
        $this->fpdf->Cell(90,7,utf8_decode(($nocturno == 1) ? "Horaire nocturno: Oui ". $noct . " $" : "Horaire nocturno: Non"),1,0,'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(90,7,utf8_decode("Date de l'événement ". $date),1,0,'L');
        $this->fpdf->Cell(90,7,utf8_decode(($feriado == 1) ? "Férié: Oui ". $fer . " $" : "Férié: Non"),1,0,'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(90,7,utf8_decode("Taux horaire "),1,0,'L');
        $this->fpdf->Cell(90,7,utf8_decode(($timeXtra > 0) ? $timeXtra * $taux . " $" : "0 $"),1,0,'L');

          return $amount_col;
    }
    public function secondT($service, $feriado, $nocturno){
            $amount_col = 0;
            $km = 0;
            $Tkmxh = 0;
            $timeXtra = $service->timeservices-30;

          if ($service->enterprise == "PDG") {
              $base = 60;
              $km = $service->km - 5;
              $Tkmxh = 2.75 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.25;
                $amount_col = $amount_col + ($timeXtra * 1.25);
              }
              if ($nocturno == 1) {
                $noct = 20;
                $amount_col = $amount_col + 20;
              }
              if ($feriado == 1) {
                $fer = 20;
                $amount_col = $amount_col + 20;
              }
          }
          if ($service->enterprise == "ALLSTATE") {
              $base = 55;
              $km = $service->km - 10;
              $Tkmxh = 2 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.17;
                $amount_col = $amount_col + ($timeXtra * 1.17);
              }
              if ($nocturno == 1) {
                $noct = 12;
                $amount_col = $amount_col + 12;
              }
              if ($feriado == 1) {
                $fer = 12;
                $amount_col = $amount_col + 12;
              }
          }
          if ($service->enterprise == "AXA") {
              $base = 70;
              $km = $service->km - 5;
              $Tkmxh = 3 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.67;
                $amount_col = $amount_col + ($timeXtra * 1.67);
              }
              if ($nocturno == 1) {
                $noct = 20;
                $amount_col = $amount_col + 20;
              }
              if ($feriado == 1) {
                $fer = 20;
                $amount_col = $amount_col + 20;
              }
          }
          if ($service->enterprise == "GSA") {
              $base = 60;
              $km = $service->km - 5;
              $Tkmxh = 2.75 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.25;
                $amount_col = $amount_col + ($timeXtra * 1.25);
              }
              if ($nocturno == 1) {
                $noct = 12;
                $amount_col = $amount_col + 12;
              }
              if ($feriado == 1) {
                $fer = 12;
                $amount_col = $amount_col + 12;
              }
          }
          if ($service->enterprise == "ASSISTEL") {
              $base = 70;
              $km = $service->km - 5;
              $Tkmxh = 3 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.17;
                $amount_col = $amount_col + ($timeXtra * 1.17);
              }
              if ($nocturno == 1) {
                $noct = 12;
                $amount_col = $amount_col + 12;
              }
              if ($feriado == 1) {
                $fer = 12;
                $amount_col = $amount_col + 12;
              }
          }
          if ($service->enterprise == "ROAD") {
              $base = 87;
              $km = $service->km - 10;
              $Tkmxh = 2.5 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.17;
                $amount_col = $amount_col + ($timeXtra * 1.17);
              }
              if ($nocturno == 1) {
                $noct = 12;
                $amount_col = $amount_col + 12;
              }
              if ($feriado == 1) {
                $fer = 12;
                $amount_col = $amount_col + 12;
              }
          }
          if ($service->enterprise == "ASSISTENZA") {
              $base = 70;
              $km = $service->km - 5;
              $Tkmxh = 2.5 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.17;
                $amount_col = $amount_col + ($timeXtra * 1.17);
              }
              if ($nocturno == 1) {
                $noct = 12;
                $amount_col = $amount_col + 12;
              }
              if ($feriado == 1) {
                $fer = 12;
                $amount_col = $amount_col + 12;
              }
          }
          if ($service->enterprise == "URGENTLY") {
              $base = 80;
              $km = $service->km - 5;
              $Tkmxh = 3 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.67;
                $amount_col = $amount_col + ($timeXtra * 1.67);
              }
              if ($nocturno == 1) {
                $noct = 20;
                $amount_col = $amount_col + 20;
              }
              if ($feriado == 1) {
                $fer = 20;
                $amount_col = $amount_col + 20;
              }
          }

        $hour = Carbon::parse($service->date_Time)->format('h:m a');
        $date = Carbon::parse($service->date_Time)->format('d/M/Y');
        $this->fpdf->Ln();$this->fpdf->Ln();
        $this->fpdf->Cell(90,7,utf8_decode("Enterprise: ". $service->enterprise),1,0,'L');
        $this->fpdf->Cell(90,7,$base + $Tkmxh." $",1,0,'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(90,7,utf8_decode("Heure de l'événement ". $hour),1,0,'L');
        $this->fpdf->Cell(90,7,utf8_decode(($nocturno == 1) ? "Horaire nocturno: Oui ". $noct . " $" : "Horaire nocturno: Non"),1,0,'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(90,7,utf8_decode("Date de l'événement ". $date),1,0,'L');
        $this->fpdf->Cell(90,7,utf8_decode(($feriado == 1) ? "Férié: Oui ". $fer . " $" : "Férié: Non"),1,0,'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(90,7,utf8_decode("Taux horaire "),1,0,'L');
        $this->fpdf->Cell(90,7,utf8_decode(($timeXtra > 0) ? $timeXtra * $taux . " $" : "0 $"),1,0,'L');

          return $amount_col;
    }
    public function thirdT($service, $feriado, $nocturno){
         $amount_col = 0;
         $km = 0;
         $Tkmxh = 0;
         $timeXtra = $service->timeservices-30;

          if ($service->enterprise == "PDG") {
              $base = 80;
              $km = $service->km - 5;
              $Tkmxh = 2.75 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.25;
                $amount_col = $amount_col + ($timeXtra * 1.25);
              }
              if ($nocturno == 1) {
                $noct = 20;
                $amount_col = $amount_col + 20;
              }
              if ($feriado == 1) {
                $fer = 20;
                $amount_col = $amount_col + 20;
              }
          }
          if ($service->enterprise == "ALLSTATE") {
              $base = 80;
              $km = $service->km - 10;
              $Tkmxh = 2 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.17;
                $amount_col = $amount_col + ($timeXtra * 1.17);
              }
              if ($nocturno == 1) {
                $noct = 12;
                $amount_col = $amount_col + 12;
              }
              if ($feriado == 1) {
                $fer = 12;
                $amount_col = $amount_col + 12;
              }
          }
          if ($service->enterprise == "AXA") {
              $base = 80;
              $km = $service->km - 5;
              $Tkmxh = 3 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.67;
                $amount_col = $amount_col + ($timeXtra * 1.67);
              }
              if ($nocturno == 1) {
                $noct = 20;
                $amount_col = $amount_col + 20;
              }
              if ($feriado == 1) {
                $fer = 20;
                $amount_col = $amount_col + 20;
              }
          }
          if ($service->enterprise == "GSA") {
              $base = 80;
              $km = $service->km - 5;
              $Tkmxh = 2.75 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.25;
                $amount_col = $amount_col + ($timeXtra * 1.25);
              }
              if ($nocturno == 1) {
                $noct = 12;
                $amount_col = $amount_col + 12;
              }
              if ($feriado == 1) {
                $fer = 12;
                $amount_col = $amount_col + 12;
              }
          }
          if ($service->enterprise == "ASSISTEL") {
              $base = 80;
              $km = $service->km - 5;
              $Tkmxh = 3 * $km;            
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.17;
                $amount_col = $amount_col + ($timeXtra * 1.17);
              }
              if ($nocturno == 1) {
                $noct = 12;
                $amount_col = $amount_col + 12;
              }
              if ($feriado == 1) {
                $fer = 12;
                $amount_col = $amount_col + 12;
              }
          }
          if ($service->enterprise == "ROAD") {
              $base = 102;
              $km = $service->km - 10;
              $Tkmxh = 2.5 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.17;
                $amount_col = $amount_col + ($timeXtra * 1.17);
              }
              if ($nocturno == 1) {
                $noct = 12;
                $amount_col = $amount_col + 12;
              }
              if ($feriado == 1) {
                $fer = 12;
                $amount_col = $amount_col + 12;
              }
          }
          if ($service->enterprise == "ASSISTENZA") { 
              $base = 80;
              $km = $service->km - 5;
              $Tkmxh = 2.5 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.17;
                $amount_col = $amount_col + ($timeXtra * 1.17);
              }
              if ($nocturno == 1) {
                $noct = 12;
                $amount_col = $amount_col + 12;
              }
              if ($feriado == 1) {
                $fer = 12;
                $amount_col = $amount_col + 12;
              }
          }
          if ($service->enterprise == "URGENTLY") {
              $base = 80;
              $km = $service->km - 5;
              $Tkmxh = 3 * $km;
              $amount_col = $base + $Tkmxh;

              if ($timeXtra > 0) {
                $taux = 1.67;
                $amount_col = $amount_col + ($timeXtra * 1.67);
              }
              if ($nocturno == 1) {
                $noct = 12;
                $amount_col = $amount_col + 20;
              }
              if ($feriado == 1) {
                $fer = 12;
                $amount_col = $amount_col + 20;
              }
          }

        $hour = Carbon::parse($service->date_Time)->format('h:m a');
        $date = Carbon::parse($service->date_Time)->format('d/M/Y');

        $this->fpdf->Ln();$this->fpdf->Ln();
        $this->fpdf->Cell(90,7,utf8_decode("Enterprise: ". $service->enterprise),1,0,'L');
        $this->fpdf->Cell(90,7,$base + $Tkmxh." $",1,0,'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(90,7,utf8_decode("Heure de l'événement ". $hour),1,0,'L');
        $this->fpdf->Cell(90,7,utf8_decode(($nocturno == 1) ? "Horaire nocturno: Oui ". $noct . " $" : "Horaire nocturno: Non"),1,0,'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(90,7,utf8_decode("Date de l'événement ". $date),1,0,'L');
        $this->fpdf->Cell(90,7,utf8_decode(($feriado == 1) ? "Férié: Oui ". $fer . " $" : "Férié: Non"),1,0,'L');
        $this->fpdf->Ln();
        $this->fpdf->Cell(90,7,utf8_decode("Taux horaire "),1,0,'L');
        $this->fpdf->Cell(90,7,utf8_decode(($timeXtra > 0) ? $timeXtra * $taux . " $" : "0 $"),1,0,'L');

          return $amount_col;
    }
    public function print_facture($invoice)
    {
        $service = Service::find($invoice);
        $feriado = $nocturno = 0;

        
            
        $this->fpdf->AddPage("L", ['200', '180']);
        $this->fpdf->SetFont('Arial', 'B', 15);
        $this->fpdf->setY(5);
        $this->fpdf->setX(10);
        $this->fpdf->Image(PUBLIC_PATH('/img/logo.jpeg'),null,null,50,30);
        $this->fpdf->Ln();
        $this->fpdf->Ln();
        $this->fpdf->SetFont('Arial', 'B', 20);
        $this->fpdf->setY(45);
        $this->fpdf->setX(10);
        $this->fpdf->Cell(90,10,utf8_decode("Facture N°: "). str_pad($service->id, 5, "0", STR_PAD_LEFT),1,0,'L');
        if($service->annuled){
            $this->fpdf->Cell(90,10,utf8_decode("Service annulé"),1,0,'L');
            $this->fpdf->Ln();$this->fpdf->Ln();
            $this->fpdf->SetFont('Arial', 'B', 15);
            $this->fpdf->Cell(90,10,utf8_decode("Total"),1,0,'L');
            $this->fpdf->Cell(90,10,$this->annuled($service)." $",1,0,'L');
        }
        if($service->goa){
            if ($service->enterprise == "URGENTLY") {

                switch ($service->service) {
                    case 'T1':
                        $opc = " (Base x Km: 50$ x ".(3 * ($service->km - 5)).")";
                        break;
                    case 'T3':
                        $opc = " (Base x Km: 50$ x ".(3 * ($service->km - 5)).")";
                        break;
                    case 'T4':
                        $opc = " (Base x Km: 50$ x ".(3 * ($service->km - 5)).")";
                        break;
                    case 'T7':
                        $opc = " (Base x Km: 50$ x ".(3 * ($service->km - 5)).")";
                        break;
                    case 'T5':
                        $opc = " (Base x Km: 80$ x ".(3 * ($service->km - 5)).")";
                        break;
                    case 'T6':
                        $opc = " (Base x Km: 80$ x ".(3 * ($service->km - 5)).")";
                        break;
                    case 'T8':
                        $opc = " (Base x Km: 80$ x ".(3 * ($service->km - 5)).")";
                        break;
                }
            }else{
                $opc = null;
            }
            $this->fpdf->Cell(90,10,utf8_decode("Service GOA"),1,0,'L');
            $this->fpdf->Ln();
            $this->fpdf->SetFont('Arial', 'B', 12);
            $this->fpdf->Cell(90,8,utf8_decode("Enterprise"),1,0,'L');
            $this->fpdf->Cell(90,8,utf8_decode($service->enterprise).($opc),1,0,'L');
            $this->fpdf->Ln();
            $this->fpdf->Cell(90,8,utf8_decode("Total"),1,0,'L');
            $this->fpdf->Cell(90,8,$this->goa($service)." $",1,0,'L');
        }
        if($service->goa == false && $service->annuled == false){
            if ($service->date_Time != null) {
                /*Feriados*/
                $date = Carbon::parse($service->date_Time)->format('d-m');
                if ($date == "01-01" || $date == "01-07"  || $date == "25-12"  || $date == "24-06" ) {
                    $feriado = 1;
                }
                /*Nocturnos*/
                $hour = Carbon::parse($service->date_Time)->format('H');
                if ($hour >= "19" && $hour <= "23") {
                  $nocturno = 1;
                }
                if ($hour >= "00" && $hour <= "05") {
                    $nocturno = 1;
                }
            }
            $this->fpdf->SetFont('Arial', 'B', 12);
            $this->fpdf->Cell(90,10,utf8_decode("Service ". $service->service),1,0,'L');


            /*****  MONTOS TOTALES  ******/
            if ($service->service == 'T1' || $service->service == 'T2' || $service->service == 'T3' || $service->service == 'T4' || $service->service == 'T7')
            {
              $amount_col = $this->firstT($service, $feriado, $nocturno);
            }
            if ($service->service == 'T5' || $service->service == 'T6') {
              $amount_col = $this->secondT($service, $feriado, $nocturno);
            }
            if ($service->service == 'T8') {
              $amount_col = $this->thirdT($service, $feriado, $nocturno);
            }
            /*****  /MONTOS TOTALES  ******/

            /**** FLAIR Y ESSENCE ****/
            if ($service->flair > 0) {
                $amount_col = $amount_col + ($service->flair * 20);
            }
            if ($service->essence > 0) {
                $amount_col = $amount_col + $service->essence;
            }
            $this->fpdf->Ln();
            $this->fpdf->Cell(90,8,utf8_decode("Flair"),1,0,'L');
            $this->fpdf->Cell(90,8,($service->flair > 0) ? ($service->flair * 20)." $" : '0 $',1,0,'L');
            $this->fpdf->Ln();
            $this->fpdf->Cell(90,8,utf8_decode("Essence"),1,0,'L');
            $this->fpdf->Cell(90,8,($service->flair > 0) ? $service->essence." $" : '0 $',1,0,'L');
            $this->fpdf->Ln();
            $this->fpdf->Cell(90,8,utf8_decode("Total"),1,0,'L');
            $this->fpdf->Cell(90,8,$amount_col." $",1,0,'L');

        }
            $filename = 'Factura_'.str_pad($service->id, 5, "0", STR_PAD_LEFT).'.pdf';
            $this->fpdf->output($filename,"I");

        exit;
    }
}
