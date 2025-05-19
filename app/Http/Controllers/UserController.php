<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Memisahkan user berdasarkan domain email
        $admins = User::where('email', 'like', '%admin.pens.ac.id')->get();
        $lecturers = User::where('email', 'like', '%lecturer.pens.ac.id')->get();
        $students = User::where('email', 'like', '%student.pens.ac.id')->get();
        
        return view('admin.users.index', compact('admins', 'lecturers', 'students'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }
}