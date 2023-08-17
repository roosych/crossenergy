<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipmentRequest;
use App\Http\Requests\UpdateEquipmentRequest;
use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipments = Equipment::query()->latest()->get();
        return view('equipments.index', compact('equipments'));
    }

    public function show(Equipment $equipment)
    {
        $this->authorize('update', Equipment::class);

        return view('equipments.show', compact('equipment'));
    }

    public function store(StoreEquipmentRequest $request)
    {
        $this->authorize('create', Equipment::class);

        $data = $request->validated();

        try {
            Equipment::query()->create($data);

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

    public function update(UpdateEquipmentRequest $request, Equipment $equipment)
    {
        $this->authorize('update', Equipment::class);

        $data = $request->validated();

        Equipment::query()
            ->where('id', $equipment->id)
            ->update($data);

        return redirect()->back()->with('success', 'Successfully updated!');
    }

    public function delete(Equipment $equipment)
    {
        $this->authorize('delete', Equipment::class);

        $equipment = Equipment::find($equipment->id);
        $equipment->delete();

        //логируем кто удалил
        info('Equipment deleted', ['deleted_equipment' => $equipment->title, 'deleted_by' => auth()->user()->email]);

        return redirect()->route('equipment.index')->with('success', 'Equipment ' . $equipment->title . ' deleted!');
    }
}
