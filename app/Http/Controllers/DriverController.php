<?php

namespace App\Http\Controllers;

use App\Http\Requests\SetNoteRequest;
use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use App\Models\Driver;
use App\Models\Equipment;
use App\Models\Image;
use App\Models\Owner;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::query()
            ->leftJoin('owners', 'drivers.owner_id', '=', 'owners.id') // Соединение с таблицей owners
            ->select('drivers.*') // Выбираем только колонки из таблицы drivers
            ->orderBy('owners.number') // Сортируем по номеру владельца
            ->orderByDesc('drivers.number') // Сортируем по номеру водителя
            ->with('owner') // Предзагрузка владельцев для использования в шаблоне
            ->get();

        foreach ($drivers as $driver)
        {
            //echo $driver->future_datetime;
            if ($driver->availability == false && $driver->future_datetime != null && $driver->future_datetime < now()) {

                $driver->update([
                    'availability' => true,

                    //перегоняем фьючеры в карренты
                    'zipcode' => $driver->future_zipcode,
                    'location' => $driver->future_location,
                    'latitude' => $driver->future_latitude,
                    'longitude' => $driver->future_longitude,

                    //очищаем фьючеры
                    'future_zipcode' => null,
                    'future_location' => null,
                    'future_latitude' => null,
                    'future_longitude' => null,
                    'future_datetime' => null,
                ]);

            }
        }

        return view('drivers.index', compact('drivers'));
    }

    public function add()
    {
        $this->authorize('create', Driver::class);

        $owners = Owner::all();
        $vehicle_types = VehicleType::all();
        $equipment = Equipment::all();

        return view('drivers.add', compact('owners', 'vehicle_types', 'equipment'));

    }

    public function show(Driver $driver)
    {
        $owners = Owner::all();
        $vehicle_types = VehicleType::all();
        $equipment = Equipment::all();

        return view('drivers.show', compact('driver','owners', 'vehicle_types', 'equipment'));
    }

    public function store(StoreDriverRequest $request)
    {
        $this->authorize('create', Driver::class);

        $data = $request->validated();
        $owner = Owner::query()->where('id', $data['owner_id'])->firstOrFail();
        $ownerDriversCount = count($owner->drivers);

        $data['number'] = $ownerDriversCount + 1;

        if (!$request->has('equipment'))
        {
            $data['equipment'] = [];
        }

        // из-за того что затрагиваются две модели (водитель и экипировка), страхуемся транзакциями
        DB::beginTransaction();

        try {
            $driver = Driver::query()->create($data);

            $driver->equipment()->sync($data['equipment']);

            DB::commit();
        }

        catch (\Exception $e) {

            DB::rollback();

            return redirect()->back()->with('error', 'Something went wrong!');

        }

        return redirect()->route('drivers.index')->with('success', 'Driver ' . $driver->fullname . ' added!');

    }

    public function update(UpdateDriverRequest $request, Driver $driver)
    {
        $this->authorize('update', Driver::class);

        $data = $request->validated();

        //dd($data);

        $driver->equipment()->sync($request['equipment']);

        Driver::query()
            ->where('id', $driver->id)
            ->update($data);

        return redirect()->back()->with('success', 'Successfully updated!');

    }

    public function delete(Driver $driver)
    {
        $this->authorize('delete', Driver::class);

        $driver = Driver::find($driver->id);
        $driver->delete();

        //логируем кто удалил
        info('Driver deleted', ['deleted_driver' => $driver->fullname, 'deleted_by' => auth()->user()->email]);

        return redirect()->route('drivers.index')->with('success', 'Driver ' . $driver->name . ' deleted!');
    }

    public function setNote(SetNoteRequest $request)
    {
        $validated_data = $request->validated();
        $driver = Driver::find($validated_data['id']);

        try {
            $driver->update(
                ['note' => $validated_data['note']]
            );

            return response(['msg' => 'success', 'data' => $driver], 200);
        } catch (\Exception $e)
        {
            return response(['msg' => $e], 400);
        }
    }

    public function status(Request $request)
    {
        if($request->availability == 1) {
            $data = [
                'availability' => $request->availability,

                //future data = null when available is on
                'future_zipcode' => null,
                'future_location' => null,
                'future_latitude' => null,
                'future_longitude' => null,
                'future_datetime' => null,
            ];
        } else {
            $data = [
                'availability' => $request->availability,
            ];
        }

        //return $data;

        Driver::query()
            ->where('id', $request->id)
            ->update($data);

        return response(['msg' => 'success', 'availability' => $request->availability], 200);

    }

    public function availability(Request $request, Driver $driver)
    {

        $data = [
            'availability' => $driver->availability = false,
            'future_zipcode' => (int)$request->future_zipcode,
            'future_location' => (string)$request->future_location,
            'future_latitude' => (string)$request->future_latitude,
            'future_longitude' => (string)$request->future_longitude,
            'future_datetime' => (string)$request->future_datetime,
            //'note' => (string)$request->note,

            //устанавливаем фьчерс координаты на каррент
            'location' => (string)$request->future_location,
            'zipcode' => (int)$request->future_zipcode,
            'latitude' => (string)$request->future_latitude,
            'longitude' => (string)$request->future_longitude,
        ];

        try {
            $driver->update($data);

            return response(['msg' => 'success', 'data' => $data], 200);
        } catch (\Exception $e)
        {
            return response(['msg' => $e], 400);
        }


    }


    public function images(Driver $driver)
    {
        return view('drivers.images', compact('driver'));
    }

    public function getDriverImages(Driver $driver)
    {
        $data = Image::query()->where('driver_id', $driver->id)->get();
        return response()->json(['status' => 'success', 'data' => $data]);
    }

}
