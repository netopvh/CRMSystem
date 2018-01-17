<?php

namespace App\Http\Controllers;

use App\Navigation;
use App\Menu;
use App\Page;
use App\Site;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NavigationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if (isset(Auth::user()->company_id)) {
          // Если у пользователя есть компания
          $navigations = Navigation::with('site')->whereCompany_id(Auth::user()->company_id)->paginate(30);
          $sites = Site::whereCompany_id(Auth::user()->company_id)->get()->pluck('site_name', 'id');
        } else {
          if (Auth::user()->god == 1) {
            // Если нет, то бог без компании
            $navigations = Navigation::with('site')->paginate(30);
            $sites = Site::get()->pluck('site_name', 'id');
          };
        };
        $page_info = Page::wherePage_alias('/navigations')->whereSite_id('1')->first();
        return view('navigations', compact('navigations', 'page_info', 'sites'));
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
        $user = Auth::user();
        $navigation = new Navigation;

        $navigation->navigation_name = $request->navigation_name;
        $navigation->site_id = $request->site_id;
        $navigation->company_id = $user->company_id;
        $navigation->author_id = $user->id;
        
        $navigation->save();

        if ($navigation) {
          return Redirect('/current_menu/'.$navigation->id.'/0');
        } else {
          $error = 'ошибка';
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
        $navigation = Navigation::findOrFail($id);
          // Отдаем даныне по навигации
          $result = [
            'navigation_name' => $navigation->navigation_name,
          ];
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
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
        $user = Auth::user();
        $navigation = Navigation::findOrFail($id);

        $navigation->navigation_name = $request->navigation_name;
        $navigation->site_id = $request->site_id;
        $navigation->company_id = $user->company_id;
        $navigation->editor_id = $user->id;
        
        $navigation->save();

        return Redirect('/current_menu/'.$navigation->id.'/0');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $nav = Navigation::findOrFail($id);
        $nav->editor_id = $user->id;
        // Удаляем сайт с обновлением
        $navigation = Navigation::destroy($id);
        if ($navigation) {
          return Redirect('/menus?site_id='.$nav->site_id);
        } else {
          echo 'произошла ошибка';
        };
    }
}