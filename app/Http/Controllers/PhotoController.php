<?php

namespace App\Http\Controllers;

// Подключаем модели
use App\Photo;
use App\Album;
use App\User;
use App\List_item;
use App\Booklist;
use App\AlbumEntity;

use App\PhotoSetting;

use App\Entity;

use App\Http\Controllers\Session;

// Валидация
use App\Http\Requests\PhotoRequest;

// Подключаем фасады
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

// Транслитерация
use Transliterate;

// use Intervention\Image\Facades\Image as Image;

// use Intervention\Image\ImageManagerStatic as Image;
// use Image;

use Intervention\Image\ImageManagerStatic as Image;

class PhotoController extends Controller
{

    // Настройки сконтроллера
    public function __construct(Photo $photo)
    {
        $this->middleware('auth');
        $this->photo = $photo;
        $this->class = Photo::class;
        $this->model = 'App\Photo';
        $this->entity_alias = with(new $this->class)->getTable();
        $this->entity_dependence = false;
    }

    public function index(Request $request, $alias)
    {

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), Photo::class);

        $answer_album = operator_right('albums', false, getmethod(__FUNCTION__));

        // Получаем сайт
        $album = Album::with('album_settings')->moderatorLimit($answer_album)->whereAlias($alias)->first();
        // dd($album);

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_alias, $this->entity_dependence, getmethod(__FUNCTION__));
        // dd($answer);

        // --------------------------------------------------------------------------------------------------------------------------------------
        // ГЛАВНЫЙ ЗАПРОС
        // --------------------------------------------------------------------------------------------------------------------------------------

        $photos = Photo::with(['author', 'company'])
        ->whereHas('album', function ($query) use ($alias) {
            $query->whereAlias($alias);
        })
        ->moderatorLimit($answer)
        ->companiesLimit($answer)
        ->filials($answer) // $industry должна существовать только для зависимых от филиала, иначе $industry должна null
        ->authors($answer)
        ->systemItem($answer) // Фильтр по системным записям
        ->booklistFilter($request)
        ->orderBy('moderation', 'desc')
        ->orderBy('sort', 'asc')
        ->paginate(30);

        // $album = Album::with(['author', 'photos' => function ($query) {
        //     $query->orderBy('sort', 'asc');
        //   }])
        //   ->whereAlias($alias)
        //   ->moderatorLimit($answer_album)
        //   ->companiesLimit($answer_album)
        // ->filials($answer_album) // $industry должна существовать только для зависимых от филиала, иначе $industry должна null
        // ->authors($answer_album)
        // ->systemItem($answer_album) // Фильтр по системным записям
        // ->booklistFilter($request)
        // ->orderBy('sort', 'asc')
        // ->first();

        // $photos = $album->photos;

        // dd($photos);

        // Инфо о странице
        $page_info = pageInfo($this->entity_alias);

        // Так как сущность имеет определенного родителя
        $parent_page_info = pageInfo('albums');

        return view('photos.index', compact('photos', 'page_info', 'parent_page_info', 'album', 'alias'));
    }

    public function create(Request $request, $alias)
    {

        $user = $request->user();

        // Подключение политики
        $this->authorize(__FUNCTION__, Photo::class);

        // Получаем альбом
        $answer_album = operator_right('sites', $this->entity_dependence, getmethod('index'));
        $album = Album::moderatorLimit($answer_album)->whereAlias($alias)->first();

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_alias, $this->entity_dependence, getmethod(__FUNCTION__));

        // Функция из Helper отдает массив со списками для SELECT
        $departments_list = getLS('users', 'view', 'departments');
        $filials_list = getLS('users', 'view', 'departments');

        // Получаем настройки по умолчанию
        $settings = config()->get('settings');
        // dd($settings);

        // Выдергиваем настройки из альбома
        if (isset($album->album_settings->img_max_size)) {
            $settings['img_max_size'] = $album->album_settings->img_max_size;
        }
        if (isset($album->album_settings->img_formats)) {
            $settings['img_formats'] = $album->album_settings->img_formats;
        }
        if (isset($album->album_settings->img_min_width)) {
            $settings['img_min_width'] = $album->album_settings->img_min_width;
        }
        if (isset($album->album_settings->img_min_height)) {
            $settings['img_min_height'] = $album->album_settings->img_min_height;
        }

        $settings['upload_mode'] = $album->album_settings->upload_mode;
        // dd($settings);

        $photo = new Photo;

        // Инфо о странице
        $page_info = pageInfo($this->entity_alias);

        // Так как сущность имеет определенного родителя
        $parent_page_info = pageInfo('albums');

        return view('photos.create', compact('alias', 'photo', 'album', 'roles_list', 'page_info', 'parent_page_info', 'settings'));
    }

    public function store(Request $request, $alias)
    {

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), Photo::class);

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer_album = operator_right('albums', false, getmethod('index'));
        $album = Album::moderatorLimit($answer_album)->whereAlias($alias)->first();

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_alias, $this->entity_dependence, getmethod(__FUNCTION__));

        $user = $request->user();

        // Смотрим компанию пользователя
        $company_id = $user->company_id;

        // Скрываем бога
        $user_id = hideGod($user);

        if ($request->hasFile('photo')) {

            // Вытаскиваем настройки
            // Вытаскиваем базовые настройки сохранения фото
            $settings = config()->get('settings');

            // Начинаем проверку настроек, от компании до альбома
            // Смотрим общие настройки для категории
            $get_settings = PhotoSetting::where(['entity' => 'albums', 'entity_id' => $album->id])->first();

            if ($get_settings) {

                if ($get_settings->img_small_width != null) {
                    $settings['img_small_width'] = $get_settings->img_small_width;
                }

                if ($get_settings->img_small_height != null) {
                    $settings['img_small_height'] = $get_settings->img_small_height;
                }

                if ($get_settings->img_medium_width != null) {
                    $settings['img_medium_width'] = $get_settings->img_medium_width;
                }

                if ($get_settings->img_medium_height != null) {
                    $settings['img_medium_height'] = $get_settings->img_medium_height;
                }

                if ($get_settings->img_large_width != null) {
                    $settings['img_large_width'] = $get_settings->img_large_width;
                }

                if ($get_settings->img_large_height != null) {
                    $settings['img_large_height'] = $get_settings->img_large_height;
                }

                if ($get_settings->img_formats != null) {
                    $settings['img_formats'] = $get_settings->img_formats;
                }

                if ($get_settings->img_min_width != null) {
                    $settings['img_min_width'] = $get_settings->img_min_width;
                }

                if ($get_settings->img_min_height != null) {
                    $settings['img_min_height'] = $get_settings->img_min_height;
                }

                if ($get_settings->img_max_size != null) {
                    $settings['img_max_size'] = $get_settings->img_max_size;

                }
            }

            $directory = $user->company_id.'/media/albums/'.$album->id.'/img';
            // $name = $album->alias.'-'.time();

            // Отправляем на хелпер request(в нем находится фото и все его параметры, id автора, id сомпании, директорию сохранения, название фото, id (если обновляем)), в ответ придет МАССИВ с записсаным обьектом фото, и результатом записи
            $array = save_photo($request, $directory, $album->alias.'-'.time(), $album->id, null, $settings);

            $photo = $array['photo'];

            if(!isset($album->photo_id)){
                $album->photo_id = $photo->id;
                $album->save();
            }

            // $album->photos()->attach($photo->id);

            $media = new AlbumEntity;
            $media->album_id = $album->id;
            $media->entity_id = $photo->id;
            $media->entity = 'photos';
            $media->save();

            $upload_success = $array['upload_success'];


            // }
            // Storage::disk('public')->put($directory.'/small/'.$image_name, $small->stream()->__toString());
            //   // dd($photo);

            if ($upload_success) {
                return response()->json($upload_success, 200);
            } else {
                return response()->json('error', 400);
            }
        } else {
            return response()->json('error', 400);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit(Request $request, $alias, $id)
    {

        // ГЛАВНЫЙ ЗАПРОС:
        $photo = Photo::with('album')->moderatorLimit(operator_right($this->entity_alias, $this->entity_dependence, getmethod(__FUNCTION__)))->findOrFail($id);

        // dd($photo);

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $photo);

        $album = $photo->album;

        // Инфо о странице
        $page_info = pageInfo($this->entity_alias);

        // Так как сущность имеет определенного родителя
        $parent_page_info = pageInfo('albums');
        // dd($album);

        return view('photos.edit', compact('photo', 'parent_page_info', 'page_info', 'album'));
    }

    public function update(Request $request, $alias, $id)
    {

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer_album = operator_right('albums', false, getmethod('index'));
        $album = Album::moderatorLimit($answer_album)->whereAlias($alias)->first();

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right('albums', false, getmethod(__FUNCTION__));
        $photo = Photo::moderatorLimit($answer)->findOrFail($id);

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $photo);

        // Получаем данные для авторизованного пользователя
        $user = $request->user();

        // Скрываем бога
        $user_id = hideGod($user);

        if ($request->avatar == 1) {
            $album->photo_id = $id;
            $album->save();
        }

        // Модерация и системная запись
        $photo->system_item = $request->system_item;
        $photo->moderation = $request->moderation;

        // Отображение на сайте
        $photo->display = $request->display;

        $photo->editor_id = $user_id;
        $photo->title = $request->title;
        $photo->description = $request->description;
        $photo->link = $request->link;

        $photo->color = $request->color;

        $photo->save();


        // Инфо о странице
        $page_info = pageInfo($this->entity_alias);

        // Так как сущность имеет определенного родителя
        $parent_page_info = pageInfo('albums');

        if ($photo) {

            return redirect('/admin/albums/'.$alias.'/photos');
        } else {
            abort(403, 'Ошибка при обновления фотографии!');
        }
    }

    public function destroy(Request $request, $alias, $id)
    {

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_alias, $this->entity_dependence, getmethod(__FUNCTION__));

        // ГЛАВНЫЙ ЗАПРОС:
        $photo = Photo::with(['avatar', 'album' => function ($query) use ($alias) {
            $query->whereAlias($alias);
        }])->moderatorLimit($answer)->findOrFail($id);

        // Подключение политики
        $this->authorize(getmethod(__FUNCTION__), $photo);

        if ($photo) {

            $album = $photo->album->first();

            if (isset($photo->album->name)) {
                $album = Album::findOrFail($photo->album->id);
                $album->photo_id = null;
                $album->save();

                if ($album == false) {
                    abort(403, 'Ошибка при удалении аватара альбома');
                }
            }
            $directory = $album->company_id.'/media/albums/'.$album->id.'/img';


            $small = Storage::disk('public')->delete($directory.'/small/'.$photo->name);
            $medium = Storage::disk('public')->delete($directory.'/medium/'.$photo->name);
            $large = Storage::disk('public')->delete($directory.'/large/'.$photo->name);
            $original = Storage::disk('public')->delete($directory.'/original/'.$photo->name);
            // dd($storage);

            $user = $request->user();

            // Скрываем бога
            $user_id = hideGod($user);
            $photo->editor_id = $user_id;
            $photo->save();

            $photo->albums()->detach();

            // Удаляем страницу с обновлением
            $photo = Photo::destroy($id);
            if ($photo) {
                return redirect('/admin/albums/'.$alias.'/photos');
            } else {
                abort(403, 'Ошибка при удалении фотографии');
            }
        } else {
            abort(403, 'Фотография не найдена');
        }
    }

    // ------------------------------------------ Ajax --------------------------------------------------------

    public function ajax_index(Request $request)
    {

        $entity = Entity::whereAlias($request->entity)->first();

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($entity->alias, $this->entity_dependence, getmethod('index'));

        $model = 'App\\'.$entity->model;
        $item = $model::with('album.photos')
        ->moderatorLimit($answer)
        ->findOrFail($request->id);
        // dd($item);

        // Подключение политики
        // $this->authorize(getmethod('index'), $this->class);

        return view('photos.photos', compact('item'));
    }

    // Сохраняем фото через dropzone
    public function ajax_store(Request $request)
    {

        // Подключение политики
        // $this->authorize(getmethod('store'), $this->class);

        if ($request->hasFile('photo')) {

            // Обновляем id альбома
            $entity = Entity::whereAlias($request->entity)->first();
            $model = 'App\\'.$entity->model;
            $item = $model::with('album')->findOrFail($request->id);

            if ($item->has('album')) {
                $album = $item->album;
            } else {
                // Получаем пользователя
                $user = $request->user();

                $album = Album::firstOrCreate(
                    [
                        'name' => $request->name,
                        'albums_category_id' => 1,
                        'company_id' => $user->company_id,
                    ], [
                        'description' => $request->name,
                        'alias' => Transliterate::make($request->name, ['type' => 'url', 'lowercase' => true]),
                        'author_id' => hideGod($user),
                    ]
                );

                $item->album_id = $album->id;
                $item->save();
            }

            // if (isset($request->album_id)) {
            //     $album = Album::findOrFail($request->id);
            // } else {
            //     $album = new Album;
            //     $album->name = $request->name;
            //     $album->description = $request->name;
            //     $album->alias = Transliterate::make($request->name, ['type' => 'url', 'lowercase' => true]);
            //     $album->albums_category_id = 1;

            //     // Получаем пользователя
            //     $user = $request->user();
            //     $album->company_id = $user->company_id;
            //     $album->author_id = hideGod($user);

            //     $album->save();
            // }

            // $model::where('id', $request->id)->update(['album_id' => $album->id]);

            // Cохраняем / обновляем фото
            $result = savePhotoInAlbum($request, $album);

            $album->photos()->attach($result['photo']->id);

            return response()->json($result['upload_success'], 200);
            // return response()->json($photo, 200);

        } else {
            return response()->json('error', 400);
        }
    }

    public function ajax_edit(Request $request, $id)
    {

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_alias, $this->entity_dependence, getmethod('edit'));

        $photo = Photo::with('album')
        ->moderatorLimit($answer )
        ->findOrFail($id);

        // Подключение политики
        // $this->authorize(getmethod('edit'), $photo);

        return view('photos.photo_edit', compact('photo'));
    }

    public function ajax_update(Request $request, $id)
    {

        // Получаем из сессии необходимые данные (Функция находиться в Helpers)
        $answer = operator_right($this->entity_alias, $this->entity_dependence, getmethod('update'));

        $photo = Photo::moderatorLimit($answer)
        ->findOrFail($id);

        // Подключение политики
        // $this->authorize('update', $photo);

        $photo->title = $request->title;
        $photo->description = $request->description;

        // Модерация и системная запись
        $photo->system_item = $request->system_item;
        $photo->moderation = $request->moderation;
        $photo->display = $request->display;

        $photo->editor_id = hideGod($request->user());
        $photo->save();

        return response()->json(isset($photo) ?? 'Ошибка обновления информации!');
    }




}
