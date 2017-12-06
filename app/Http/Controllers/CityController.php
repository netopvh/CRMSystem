<?php

namespace App\Http\Controllers;


use App\Region;
use App\Area;
use App\City;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CityController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
      
      // dd($data);

    // $data = Region::select('id', 'region_name')
    //         ->join('cities', 'areas.city_id', '=', 'cities.id')
    //         ->join('cities',)
    //         ->get();
    $regions = Region::withCount('areas', 'cities')->get();
    $areas = Area::withCount('cities')->get();
    $cities = City::all();

    return view('cities', ['regions' => $regions, 'areas' => $areas, 'cities' => $cities]); 


    // $data = City::with('area.region')->get();
    // dd($data);
    // return view('cities', compact('data'));  
    // $deleted_regions = Region::onlyTrashed()
    //             ->count();
    // return view('cities', compact('data'));    

    // foreach ($dates as $data) {
    //  echo "Id: ".$data->city_id.", название области: ".$data->city_name."\r\nId района: ".$data->area_id.", название района: ".$data->area_name."\r\n";
    // }
        
        // $cities = DB::table('cities')->get();

        // foreach ($cities as $city)
        // {
        //     var_dump($city->city_name);
        //     var_dump($city->area_name);
        // }
      // return view('cities', compact('dates'));
                // echo $dates[1]->area_id;
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
    $city_database = $request->city_database;
    // По умолчанию значение 0
    if ($city_database == 0) {
      // Проверка города и района в нашей базе данных
      $city_name = $request->city_name;
      $cities = City::where('city_name', '=', $city_name)->first();
      if ($cities) {
        $result = [
          'error_message' => 'Населенный пункт уже добавлен в нашу базу!',
          'error_status' => 1
        ];
      } else {
        $result = [
          'error_status' => 0
        ];
      }
      echo json_encode($result, JSON_UNESCAPED_UNICODE);
    };
    // Если город не найден, то меняем значение на 1, пишем в базу и отдаем результат
    if ($city_database == 1) {

      $region_name = $request->region_name;
      $area_name = $request->area_name;
      $city_name = $request->city_name;

      // Смотрим область
      $region = Region::where('region_name', '=', $region_name)->first();
      if ($region) {
        // Если существует, берем id существующий
        $region_id = $region->id;
      } else {

        if ($region_name == null) {
          $region_id = 0;
        } else {
          // Записываем новую область
          $region = new Region;

          $region->region_name = $region_name;

          $region->save();
          // Берем id записанной области
          $region_id = $region->id;
        };
      };

      // Смотрим район
      $area = Area::where('area_name', '=', $area_name)->first();
      if ($area) {
        // Если существует, берем id существующей
        $area_id = $area->id;
      } else {

        if ($area_name == null) {
          $area_id = 0;
        } else {
          // Записываем новый район
          $area = new Area;

          $area->area_name = $area_name;
          $area->region_id = $region_id;

          $area->save();

          // Берем id записанного района
          $area_id = $area->id;
        };
      };
      // Если у города нет области
      // if (condition) {
      //   # code...
      // };
      // Если у города нет района
      if ($area_id == 0) {
        $city = new City;

        $city->city_name = $city_name;
        $city->city_code = $request->city_code;
        $city->region_id = $region_id;
        $city->city_vk_external_id = $request->city_vk_external_id;

        $city->save();

        $city_id = $city->id;
      };

      if ($region_id != 0 && $area_id != 0) {
        // Записываем город, его наличие в базе мы проверили ранее
        $city = new City;

        $city->city_name = $city_name;
        $city->city_code = $request->city_code;
        $city->area_id = $area_id;
        $city->city_vk_external_id = $request->city_vk_external_id;

        $city->save();

        $city_id = $city->id;

      };
       return Redirect('current_city/'.$region_id.'/'.$area_id.'/'.$city_id);
    };
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
      //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $city = City::destroy($id);
    // $city = true;

    if ($city) {
      $data = [
        'status' => 1,
        'type' => 'cities',
        'id' => $id,
        'msg' => 'Успешно удалено'
      ];
    } else {
      $data = [
        'status' => 0,
        'msg' => 'Произошла ошибка'
      ];
    };
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
  }

  // Получаем список городов из базы вк
  public function get_vk_city(Request $request)
  {
    $city = $request->city;
    $request_params = [
    'country_id' => '1',
    'q' => $city,
    'need_all' => '0',
    'count' => '100',
    'v' => '5.69'
    ];
    $get_params = http_build_query($request_params);
    $result = (file_get_contents('https://api.vk.com/method/database.getCities?'. $get_params));

    // Если чекбокс не включен, то выдаем результат только по нашим областям
    if ($request->checkbox == 'false') {
      $regions = Region::select('region_name')
                        ->get();
      $vk_cities = json_decode($result);
      $items = $vk_cities->response->items;
      $count = $vk_cities->response->count;
      $objRes = (object) [];
      if ($count == 0) {
        $objRes->count = 0;
      } else {
        $objRes = (object) [];
        // Находим наши области
        foreach ($regions as $region) {
          $region_name = $region->region_name;
          // Перебираем пришедшие с vk
          foreach ($items as $item) {
            $title = $item->title;
            $id = $item->id;
            //Если нет области
            if (empty($item->region)) {
              $region = null;
            } else {
              $region = $item->region;
            };
            // Если нет района
            if (empty($item->area)) {
              $area = null;
            } else {
              $area = $item->area;
            };
            // Если имена областей совпали, заносим в наш обьект с результатами
            if ($region_name == $region) {
              $objRes->region[] = $region;
              $objRes->area[] = $area;
              $objRes->title[] = $title;
              $objRes->id[] = $id;
            };
          };
        };
      }
     
      echo json_encode($objRes, JSON_UNESCAPED_UNICODE);
    } else {
      // Если секбокс "искать везде" включен, отдаем данные, пришедшие с vk 
      echo $result;
    }
  }
  // Получаем сторонние данные по 
  public function current_city($region, $area, $city)
  {
    $regions = Region::withCount('areas', 'cities')->get();
    $areas = Area::withCount('cities')->get();
    $cities = City::all();
    $data = [
      'region_id' => $region,
      'area_id' => $area,
      'city_id' => $city,
    ];
    return view('cities', ['regions' => $regions, 'areas' => $areas, 'cities' => $cities, 'data' => $data]); 
  }
}
