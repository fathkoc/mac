<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{

    public function showProfile()
    {
        return view('admin.profile');
    }

    // Handle the profile update
    public function updateProfile(Request $request)
    {
        // Create a Validator instance
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'unique:admins,email,' . Auth::guard('admin')->id(),
            ],
            'password' => 'nullable|string|min:5',
        ], [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a valid string.',
            'name.max' => 'Name cannot be longer than 255 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'The email address is already in use.',
            'password.string' => 'Password must be a valid string.',
            'password.min' => 'Password must be at least 5 characters long.',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        // Get the currently authenticated admin
        $admin = Admin::find(Auth::guard('admin')->id());

        if (!$admin) {
            return redirect()->back()->with('error', 'Admin not found.');
        }

        // Update profile details
        $admin->name = $request->input('name');
        $admin->email = $request->input('email');

        // Update password if provided
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->input('password'));
        }

        // Save changes
        if ($admin->save()) {
            return redirect()->route('admin.profile')->with('success', 'Profil başarıyla güncellendi.');
        } else {
            return redirect()->back()->with('error', 'Profil güncellenemedi.');
        }
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        return view('admin.dashboard');
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:5',
        ], [
            'name.required' => 'Ad gerekli.',
            'email.required' => 'E-posta gerekli.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'email.unique' => 'Bu e-posta adresi zaten kullanımda.',
            'password.required' => 'Şifre gerekli.',
            'password.min' => 'Şifre en az 5 karakter uzunluğunda olmalıdır.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $admin = new Admin();
        $admin->name = $request->input('name');
        $admin->email = $request->input('email');
        $admin->password = Hash::make($request->input('password'));
        $admin->save();

        return redirect()->route('admin.create')->with('success', 'Admin kullanıcısı başarıyla oluşturuldu.');
    }

    public function index()
    {
        $admins = Admin::all();
        return view('admin.admins.index', compact('admins'));
    }

    public function edit($id)
    {
        $admin = Admin::findOrFail($id);

        if ($admin->isDefault()) {
            abort(403, 'Varsayılan admin üzerinde değişiklik yapılamaz.');
        }

        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $id,
            'password' => 'nullable|string|min:6',
        ]);

        $admin = Admin::findOrFail($id);

        if ($admin->isDefault()) {
            abort(403, 'Varsayılan admin üzerinde değişiklik yapılamaz.');
        }

        $admin->name = $request->input('name');
        $admin->email = $request->input('email');

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->input('password'));
        }

        $admin->save();

        return redirect()->to('admin/admins')->with('success', 'Admin successfully updated.');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);

        if ($admin->isDefault()) {
            abort(403, 'Varsayılan admin üzerinde değişiklik yapılamaz.');
        }

        $admin->delete();
        return redirect()->to('admin/admins')->with('success', 'Admin successfully deleted.');
    }
}
