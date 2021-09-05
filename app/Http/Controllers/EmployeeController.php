<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $employees = Employee::get();
        return view('backend.employees.employees',compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' =>'required|max:191',
            'email' =>'required|email|max:191',
            'phone' =>'required|min:11|max:191',
            'address' =>'required|max:191',
            'gender' =>'required|max:191',
            'position' =>'required|max:191',
            'salary' =>'required|max:191',
            'image' =>'required|mimes:jpg,png',
            'company_id' =>'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            $employees = new Employee;
            $employees->name = $request->name;
            $employees->email = $request->email;
            $employees->phone = $request->phone;
            $employees->address = $request->address;
            $employees->gender = $request->gender;
            $employees->position = $request->position;
            $employees->salary = $request->salary;
            $employees->company_id = $request->company_id;
            if($request->hasFile('image')){
                $image = $request->file('image');
                $image_name = time().'.'.$image->getClientOriginalExtension();
                $image->move(public_path().'/backend/image/employee/',$image_name);
                $employees->image = $image_name;
            }
            $employees->save();
            return response()->json(['success'=>'Data Add successfully.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employees = Employee::find($id);
       return view('backend.employees.employee-details', compact('employees'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employees = Employee::find($id);
        return view('backend.employees.employee-edit',compact('employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name' =>'required|max:191',
            'email' =>'required|email|max:191',
            'phone' =>'required|min:11|max:191',
            'address' =>'required|max:191',
            'position' =>'required|max:191',
            'salary' =>'required|max:191',
            'image' =>'mimes:jpg,png',
        ]);
        $employees = Employee::find($id);
        $employees->name = $request->name;
            $employees->email = $request->email;
            $employees->phone = $request->phone;
            $employees->address = $request->address;
            $employees->gender = $request->gender;
            $employees->position = $request->position;
            $employees->salary = $request->salary;
            $employees->company_id = $request->company_id;
            if($request->hasFile('image')){
                $destination = public_path().'/backend/image/employee/'.$employees->image;
                if(File::exists($destination)){
                    File::delete($destination);
                }
                $image = $request->file('image');
                $image_name = time().'.'.$image->getClientOriginalExtension();
                $image->move(public_path().'/backend/image/employee/',$image_name);
                $employees->image = $image_name;
            }
            $employees->update();
            $employees = Employee::all();
            return redirect()->route('employees');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);
        if(!is_null($employee)){

                $image_path = public_path().'/backend/image/employee/'.$employee->image;
                unlink($image_path);
                $employee->delete();
                return response()->json(['success'=>'Data Delete successfully.']);

        }

    }
}