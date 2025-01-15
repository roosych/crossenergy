<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        //$drivers = Driver::all();


        return view('dashboard.index',);
    }

    public function getAllDrivers()
    {
        $data = Driver::with(['vehicle_type', 'equipment', 'owner'])
            //->where('status', true)
            ->get()
            ->toArray();

        //dd($data);

        $res = array();

        foreach ($data as $item)
        {
            //dd($item['equipment']);

            $equipments = [];

            foreach ($item['equipment'] as $equ)
            {
                array_push($equipments, $equ['title']);
            }

            // Получаем данные об овнере
            $ownerName = $item['owner']['name'] ?? 'N/A';
            $ownerNumber = $item['owner']['number'] ?? 'N/A';

            //dd($equipments);

            array_push($res, [
                "type" => "FeatureCollection",
                "features" => [
                    [
                        "type" => "Feature",
                        "properties" => [
                            "id" => (int)$item['id'],
                            "fullname" => (string)$item['fullname'],
                            "number" => (string)$item['number'],
                            "availability" => (boolean)$item['availability'],
                            "citizenship" => (string)$item['citizenship'],
                            "dnu" => (boolean)$item['dnu'],
                            "latitude" => (string)$item['latitude'],
                            "longitude" => (string)$item['longitude'],
                            "phone" => (string)$item['phone'],
                            "location" => (string)$item['location'],
                            "zipcode" => (string)$item['zipcode'],
                            "capacity" => (string)$item['capacity'],
                            "dimension" => (string)$item['dimension'],
                            "vehicle_type" => (string)$item['vehicle_type']['title'],
                            "vehicle_type_color" => (string)$item['vehicle_type']['color'],
                            "note" => (string)$item['note'],
                            "equipments" => (array)$equipments,

                            // Новые данные об овнере
                            "owner_name" => (string)$ownerName,
                            "owner_number" => (string)$ownerNumber,

                            "future_location" => (string)$item['future_location'],
                            "future_zipcode" => (string)$item['future_zipcode'],
                            "future_latitude" => (string)$item['future_latitude'],
                            "future_longitude" => (string)$item['future_longitude'],
                            "future_datetime" => (string)$item['future_datetime'],
                            "register_expdate" => (string)$item['register_expdate'],
                        ],
                        "geometry" => [
                            "type" => "Point",
                            "coordinates" => [$item['longitude'], $item['latitude']],
                        ],
                    ],
                ],
            ]);

        }

        $drivers = json_encode($res);

        //dd($drivers);

        return $drivers;
    }
}
