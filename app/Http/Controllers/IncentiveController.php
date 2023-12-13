<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Consultant;
use App\Models\Consultation;
use App\Models\Incentive;
use App\Models\Spouse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Reference;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Visibility;
use Illuminate\Support\Facades\Response;

class IncentiveController extends Controller
{
    //redirect staff to manage consultation page
    public function manage()
    {
        $datas = Incentive::with('consultant', 'applicant', 'spouse', 'status')->where('ref_status_id',1)
        ->paginate(3);
        return view('manageIncentive.manage', compact('datas'));
    }

    //redirect user to user manage consultation page
    public function userManage()
    {
        $applicant = Applicant::where('user_id', Auth()->user()->id)->first();
        $datas = Incentive::with('consultant', 'applicant', 'spouse', 'status',)->where('app_id', $applicant->id)->paginate(9);
        return view('manageIncentive.userManage', compact('datas'));
    }
     //redirect user to create consultation page
     public function create()
     {
         $locations = Reference::where('name', 'location')->orderBy('code')->get();
         $slots = Reference::where('name', 'slot')->orderBy('code')->get();
         $user =  Auth()->user();
         return view('manageIncentive.create', compact('user', 'locations', 'slots'));
     }
    //store the newly create consultation in the database
    public function store(Request $request)
    {
        $file = $request->file('file');
        $fileName = $request->input('applicant_name') . $request->input('date') . '.pdf';
    
        Storage::disk('local')->makeDirectory('template');
    
        Storage::disk('local')->put('template/' . $fileName, file_get_contents($file->getRealPath()));
    
        $user = ([
            'name' => $request->applicant_name,
            'email' =>  $request->applicant_email,
            'contact' => $request->applicant_phoneNo,
        ]);
    
        User::where('id', Auth()->user()->id)->update($user);
    
        $applicant = Applicant::where('user_id', Auth()->user()->id)->first();
        $applicant->birthdate = $request->applicant_birthdate;
        $applicant->nationality = $request->applicant_nationality;
        $applicant->houseaddress = $request->applicant_address;
        $applicant->save();
        $existingSpouse = Spouse::where('ic', $request->spouse_IcNum)->first();
    
        if ($existingSpouse) {
            $spouse = $existingSpouse;
        } else {
            $spouse = new Spouse();
            $spouse->ic = $request->spouse_IcNum;
        }
    
        $spouse->name = $request->spouse_name;
        $spouse->birthdate = $request->spouse_birthdate;
        $spouse->email = $request->spouse_email;
        $spouse->gender = $request->spouse_gender;
        $spouse->phonenumber = $request->spouse_phoneNo;
        $spouse->nationality = $request->spouse_nationality;
        $spouse->address = $request->spouse_address;
        $spouse->save();
    
        $incentives = ([
            'sp_id' => $spouse->id,
            'app_id' => $applicant->id,
            'date' => $request->date,
            'ref_location_id' => $request->ref_location_id,
            'ref_slot_id' => $request->ref_slot_id,
            'description' => $request->description,
            'document' => $fileName,
        ]);
        Incentive::create($incentives);
        return redirect()->route('user.incentive.manage')
            ->with('success', "consultation Successfully Posted!");
        }
    //redirect the staff to edit consultation page
    public function edit($id)
    {
        $data = Incentive::with('spouse', 'applicant.user', 'slot', 'location')->find($id)->toArray();
        $consultants = Consultant::all();
    
        return view('manageIncentive.edit', compact('data', 'consultants', 'id'));
    }

    //update the consultation detail in the database
    public function update(Request $request, $id)
    {
        $request->merge([
            'ref_status_id' => 3,
            'managed_by' => auth()->user()->id,
        ]);
        Incentive::create($incentives);



        return redirect()->route('user.incentive.manage')
            ->with('success', "Incentive Successfully Posted!");
    }
    //update the incentive status to decline in the database
    public function decline($id)
    {
        $status = [
            'ref_status_id' => 2,
        ];
        Incentive::find($id)->update($status);


        return redirect()->route('staff.incentive.manage');
    }

    //redirect the staff to show incentive page
    public function show($id)
    {
        $data = Incentive::with('spouse', 'applicant.user', 'location', 'slot', 'status')->find($id)
            ->toArray();
        return view('manageIncentive.show', compact('data', 'id'));
    }

    //redirect user to to show consultation page
    public function userShow($id)
    {
        $data = Incentive::with('consultant','spouse', 'applicant.user', 'location', 'slot', 'status')->find($id)
            ->toArray();
        return view('manageIncentive.userShow', compact('data', 'id'));
    }

    //display file
    public function displayFile($fileName)
    {
        $filePath = 'template/' . $fileName;

        // Check if the file exists
        if (Storage::disk('local')->exists($filePath)) {
            $file = Storage::disk('local')->get($filePath);
            $mimeType = Storage::disk('local')->mimeType($filePath);

            // Return the file response with appropriate headers
            return Response::make($file, 200, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            ]);
        } else {
            // File not found
            abort(404);
        }
    }
    }
