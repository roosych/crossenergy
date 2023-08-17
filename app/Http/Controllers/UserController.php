<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeStatusRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Mail\User\PasswordMail;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function add()
    {
        $this->authorize('create', User::class);

        $permissions = Permission::query()
            ->where('active', true)
            ->get();

        return view('users.add', compact('permissions'));
    }

    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        $data = $request->validated();

        $password = Str::password(10);

        $data['password'] = Hash::make($password);
        //$data['password'] = Hash::make('password');

        //если пермишены не выбраны - передаем пустой массив, чтобы не было ошибки
        if (!$request->has('permissions'))
        {
            $data['permissions'] = [];
        }

        // из-за того что затрагиваются две модели, страхуемся транзакциями
        DB::beginTransaction();

        try {
            $user = User::query()->create($data);

            $user->permissions()->sync($data['permissions']);

            DB::commit();
            Mail::to($data['email'])->send(new PasswordMail($password));
        }

        catch (\Exception $e) {

            DB::rollback();

            return redirect()->back()->with('error', 'Something went wrong!');

        }

        return redirect()->route('users.index')->with('success', 'User ' . $user->name . ' added!');

    }

    public function show(User $user)
    {
        $this->authorize('update', User::class);

        $permissions = Permission::query()
            ->where('active', true)
            ->get();

        return view('users.show', compact(['user', 'permissions']));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', User::class);

        $data = $request->validated();

        $user->permissions()->sync($request['permissions']);

        User::query()
            ->where('id', $user->id)
            ->update($data);

        return redirect()->back()->with('success', 'Successfully updated!');
    }

    public function delete(User $user)
    {
        $this->authorize('delete', User::class);

        $user = User::find($user->id);
        $user->delete();

        //логируем кто удалил
        info('User deleted', ['deleted_user' => $user->email, 'deleted_by' => auth()->user()->email]);

        return redirect()->route('users.index')->with('success', 'User ' . $user->name . ' deleted!');
    }

    public function status(Request $request)
    {
        $this->authorize('update', User::class);

        $data = [
            'active' => $request->active,
        ];

        User::query()
            ->where('id', $request->id)
            ->update($data);

        return response('success', 200);
    }
}
