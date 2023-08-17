<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleTypeRequest;
use App\Http\Requests\UpdateVehicleTypeRequest;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
{
    public function index()
    {
        $types = VehicleType::query()
            ->with('drivers')
            ->latest()
            ->get();

        return view('vehicletypes.index', compact('types'));
    }

    public function show(VehicleType $vehicle)
    {
        return view('vehicletypes.show', compact('vehicle'));
    }

    public function store(StoreVehicleTypeRequest $request)
    {
        $this->authorize('create', VehicleType::class);

        $data = $request->validated();

        try {
            VehicleType::query()->create($data);

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

    public function update(UpdateVehicleTypeRequest $request, VehicleType $vehicle)
    {
        $this->authorize('update', VehicleType::class);

        $data = $request->validated();

        VehicleType::query()
            ->where('id', $vehicle->id)
            ->update($data);

        return redirect()->back()->with('success', 'Successfully updated!');
    }

    public function delete(VehicleType $vehicle)
    {
        $this->authorize('delete', VehicleType::class);

        $vehicle = VehicleType::find($vehicle->id);
        $vehicle->delete();

        //логируем кто удалил
        info('Vehicle type deleted', ['deleted_vhicletype' => $vehicle->title, 'deleted_by' => auth()->user()->email]);

        return redirect()->route('vehicletypes.index')->with('success', 'Vehicle type ' . $vehicle->title . ' deleted!');
    }
}
