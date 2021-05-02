<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Image;

class EmployeeController extends Controller
{
    // get all listings of employee
    public function index()
    {
        $employees = Employee::all();
        return response()->json($employees);
    }

    // save employee data
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|unique:employees|max:255',
            'email' => 'required|unique:employees',
            'phone' => 'required|unique:employees'
        ]);

        if ($request->photo) {
            $position = strpos($request->photo, ';');
            $sub = substr($request->photo, 0, $position);
            $ext = explode('/', $sub)[1];

            $name = time() . "." . $ext;
            $img = Image::make($request->photo)->resize(240, 200);
            $upload_path = 'uploads/employees/';
            $image_url = $upload_path . $name;
            $img->save($image_url);

            // $name = time() . "." . $ext;
            // $imageFile = $request->file('photo');
            // $img = Image::make($imageFile)->resize(240, 200);
            // $upload_path = 'uploads/employees/';
            // $file_path = $upload_path . time() . $imageFile->getClientOriginalName();
            // $thImage = $img->save($file_path);

            // $file = $img->storeAs($upload_path, Str::random(25) . '.' . $imageFile->getClientOriginalExtension(), 'public');

            // $image_url = $upload_path . $name;
            // $img->save($image_url);

            // $imageName = time() . '.' . $request->photo->extension();
            // $imagePath = $request->photo->move(public_path('uploads/employees'), $imageName);

            $employee = new Employee();
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->phone = $request->phone;
            $employee->salary = $request->salary;
            $employee->address = $request->address;
            $employee->join_date = $request->join_date;
            $employee->photo = $image_url;
            $employee->save();
        } else {
            $employee = new Employee();
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->phone = $request->phone;
            $employee->salary = $request->salary;
            $employee->address = $request->address;
            $employee->join_date = $request->join_date;
            $employee->save();
        }
    }

    // show specific resource
    public function show($id)
    {
        $employee = Employee::where('id', $id)->first();
        return response()->json($employee);
    }

    // update specific employee information
    public function update(Request $request, $id)
    {
        $data = array();
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        $data['salary'] = $request->salary;
        $data['address'] = $request->address;
        $data['join_date'] = $request->join_date;
        $image = $request->newPhoto;
        // return $data;
        if ($image) {
            $position = strpos($image, ';');
            $sub = substr($image, 0, $position);
            $ext = explode('/', $sub)[1];

            $name = time() . "." . $ext;
            $img = Image::make($image)->resize(240, 200);
            $upload_path = 'uploads/employees/';
            $image_url = $upload_path . $name;
            $success = $img->save($image_url);
            // $imageFile = $request->file('photo');
            // $img = Image::make($imageFile)->resize(240, 200);
            // $upload_path = 'uploads/employees/';
            // $file_path = $upload_path . time() . $imageFile->getClientOriginalName();
            // $success = $img->save($file_path);
            // // $success = $img->save($image_url);

            if ($success) {
                $data['photo'] = $image_url;
                $img = Employee::where('id', $id)->first();
                $image_path = $img->photo;
                $done = unlink($image_path);
                $user = Employee::where('id', $id)->update($data);
            }
        } else {
            $oldphoto = $request->photo;
            $data['photo'] = $oldphoto;
            $user = Employee::where('id', $id)->update($data);
        }
    }

    //delete employee data
    public function destroy($id)
    {
        $employee = Employee::where('id', $id)->first();
        $photo = $employee->photo;
        if ($photo) {
            unlink($photo);
            Employee::where('id', $id)->delete();
        } else {
            Employee::where('id', $id)->delete();
        }
    }
}
