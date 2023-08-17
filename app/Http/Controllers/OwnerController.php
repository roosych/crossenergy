<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignDriverToOwnerRequest;
use App\Http\Requests\StoreOwnerRequest;
use App\Http\Requests\UpdateOwnerRequest;
use App\Models\Driver;
use App\Models\Owner;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function index()
    {
        $owners = Owner::query()
            ->with('drivers')
            ->latest()
            ->get();

        return view('owners.index', compact('owners'));
    }

    public function show(Owner $owner)
    {
        $this->authorize('update', Owner::class);


        $ownerless_drivers = Driver::query()->where('owner_id', null)->get();

        return view('owners.show', compact('owner', 'ownerless_drivers'));
    }

    public function store(StoreOwnerRequest $request)
    {
        $this->authorize('create', Owner::class);

        $data = $request->validated();

        try {
            Owner::query()->create($data);

            return response()->json([
                'success' => true,
                'data' => $data
            ], 200);

        } catch (\Exception $e)
        {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage(),
            ], 400 );
        }
    }

    public function update(UpdateOwnerRequest $request, Owner $owner)
    {
        $this->authorize('update', Owner::class);

        $data = $request->validated();

        Owner::query()
            ->where('id', $owner->id)
            ->update($data);

        return redirect()->back()->with('success', 'Successfully updated!');
    }

    public function delete(Owner $owner)
    {
        $this->authorize('delete', Owner::class);

        $owner = Owner::find($owner->id);
        $owner->delete();

        //логируем кто удалил
        info('Owner deleted', ['deleted_owner' => $owner->name, 'deleted_by' => auth()->user()->email]);

        return redirect()->route('owners.index')->with('success', 'Owner ' . $owner->name . ' deleted!');
    }

    public function assignDrivers(AssignDriverToOwnerRequest $request)
    {
        $this->authorize('update', Owner::class);

        $data = $request->all();
        $owner = $request->owner_id;

        //если водители не выбраны - передаем пустой массив, чтобы не было ошибки
        if (!$request->has('drivers'))
        {
            $data['drivers'] = [];
        }

        foreach ($data['drivers'] as $driver_id)
        {
            $driver = Driver::query()->find($driver_id);
            $driver->update([
                'owner_id' => $owner
            ]);
        }

        session()->flash('success_add', 'Driver ' . $driver->fullname . ' added!');

    }

    public function unAssignDrivers(Driver $driver)
    {
        $this->authorize('update', Owner::class);


        $driver = Driver::find($driver->id);
        $driver->update(['owner_id' => null]);

        //логируем кто удалил
        info('Driver updated', ['updated_driver' => $driver->fullname, 'updated_by' => auth()->user()->email]);

        return redirect()->back()->with('success_add', 'Driver ' . $driver->fullname . ' removed!');
    }

}
