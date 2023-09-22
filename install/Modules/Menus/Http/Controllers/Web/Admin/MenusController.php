<?php

namespace Modules\Menus\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Modules\Base\Support\JsPolicy;
use Modules\Menus\Support\MenuType;
use Modules\Menus\Events\MenusCreated;
use Modules\Menus\Events\MenusUpdated;
use Illuminate\Support\Facades\Session;
use Modules\Menus\Support\MenuLinkType;
use Modules\Base\Support\LanguageDefault;
use Modules\Menus\Support\HtmlMenuBuilder;
use Illuminate\Contracts\Support\Renderable;
use Modules\Menus\Repositories\MenuRepository;
use Modules\Menus\Support\HtmlMenuLinksBuilder;
use Modules\Menus\Http\Requests\StoreMenuRequest;
use Modules\Menus\Http\Requests\UpdateMenuRequest;
use Modules\Menus\Repositories\MenuLinkRepository;
use Modules\Menus\Repositories\MenuRoleRepository;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Languages\Repositories\LanguagesRepository;

class MenusController extends BaseController
{   
    /**
     * @var MenuRepository
     */
    protected $menus;

    /**
     * @var MenuLinkRepository
     */
    protected $menuLink;

    /**
     * @var MenuRoleRepository
     */
    protected $menuRole;

    /**
     * @var LanguagesRepository
     */
    protected $languages;

    /**
     * @var $redirectedTo
     */
    protected $redirectedTo = '/admin/menus';

    public function __construct(
        MenuRepository $menus, 
        MenuLinkRepository $menuLink, 
        MenuRoleRepository $menuRole,
        LanguagesRepository $languages) 
    {
        $this->menus = $menus;
        $this->menuLink = $menuLink;
        $this->menuRole = $menuRole;
        $this->languages = $languages;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('admin.menus.index');

        return view('menus::admin.index', [
            'pageTitle' => config('menus.name'),
            'policies' => JsPolicy::get('menus')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * 
     * 
     * @return Renderable
     */
    public function create()
    {
        $this->authorize('admin.menus.create');

        return view('menus::admin.create', [
            'pageTitle' => __('Create new menu'),
            'routes' => app()->routes->getRoutes(),
            'menuTypes' => MenuType::lists(),
            'menuLinkTypes' => MenuLinkType::lists(),
            'languages' => $this->languages->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(StoreMenuRequest $request)
    {   
        $menu = $this->menus->create([
            'language_id' => LanguageDefault::ID,
            'name' => $request->get('name'),
            'type' => $request->get('type'),
            'class' => $request->get('class')
        ]);

        foreach ($request->get('menu-create-output') as $lang => $menuLinks) {
            if ($lang=='en') {
                $this->handleUpdateOrCreateMenuItem('create', $menu, json_decode($menuLinks,true));
            } else {
                $this->handleUpdateOrCreateRelatedMenu('create', $menu, $lang, json_decode($menuLinks,true));
            }
        }

        event(new MenusCreated($menu));

        return $this->handleAjaxRedirectResponse(
            __('Menu created successfully.'), 
            $this->redirectedTo
        );
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {   
        $this->authorize('admin.menus.edit');

        $menus = $this->menus->getMenuWithRelatedById($id);

        return view('menus::admin.edit', [
            'pageTitle' => __('Edit menu'),
            'routes' => app()->routes->getRoutes(),
            'menuTypes' => MenuType::lists(),
            'menuLinkTypes' => MenuLinkType::lists(),
            'menus' => $menus,
            'languages' => $this->languages->all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateMenuRequest $request, $id)
    {
        $menu = $this->menus->find($id);

        $this->menus->update($menu, [
            'language_id' => LanguageDefault::ID,
            'name' => $request->get('name'),
            'type' => $request->get('type'),
            'class' => $request->get('class')
        ]);

        $menu->fresh();

        foreach ($request->get('menu-edit-output') as $lang => $menuLinks) {
            if ($lang=='en') {
                $this->handleUpdateOrCreateMenuItem('update', $menu, json_decode($menuLinks,true));
            } else {
                $this->handleUpdateOrCreateRelatedMenu('update', $menu, $lang, json_decode($menuLinks,true));
            }
        }

        event(new MenusUpdated($menu));
        
        return $this->handleAjaxRedirectResponse(
            __('Menu updated successfully.'), 
            $this->redirectedTo
        );
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        $this->authorize('admin.menus.delete');

        foreach($request->ids as $id) {
            $this->menus->delete($this->menus->find($id));
        }

        return $this->successResponse(__('Selected menu(s) deleted successfully.'));
    }

    /**
     * Handle update or create related language menu
     * 
     * @param string $action
     * @param Menu $parent
     * @param string $lang
     * @param array $menuLinks
     * 
     * @return mixed
     */
    protected function handleUpdateOrCreateRelatedMenu($action, $parent, $lang, $menuLinks) 
    {
        if($menuLinks) {

           $language = $this->languages->getByCode($lang);

            $menu = $this->menus->updateOrCreate([
                'language_id' => $language->id,
                'parent_id' => $parent->id
            ],[
                'language_id' => $language->id,
                'parent_id' => $parent->id,
                'name' => $parent->name.'_'.$lang,
                'type' => $parent->type,
                'class' => $parent->class
            ]);

            $this->handleUpdateOrCreateMenuItem($action, $menu, $menuLinks); 
        }
        
    }

    /**
     * Handle updating or create menu item
     * 
     * @param Menu $string
     * @param array $menuLinks
     * @param integer|null $parentId
     * 
     * @return mixed
     */
    protected function handleUpdateOrCreateMenuItem($action, $menu, $menuLinks, $parentId = null) 
    {
        $ordering = 1;
        foreach ($menuLinks as $menuLink) {
            $menuLinkData = [
                'id' => $action == 'create' ? null : $menuLink['id'] ?? null,
                'menu_id' => $menu->id,
                'ordering' => $ordering,
                'label' => $menuLink['name'],
                'icon' => $menuLink['icon'],
                'type' => $menuLink['type'],
                'link' => $menuLink['link'],
                'class' => !is_null($menuLink['classname'])?$menuLink['classname']:null,
                'target' => $menuLink['target']==1?'_blank':null,
                'status' => $menuLink['status']==1?1:0,
                'parent_id' => $parentId ?? null
            ];

            $createdMenuLink = $this->menuLink->updateOrCreate(['id' => $menuLinkData['id']], $menuLinkData);

            if ( isset($menuLink['children']) ) {
                $this->handleUpdateOrCreateMenuItem($action, $menu, $menuLink['children'], $createdMenuLink->id);
            }

            $ordering++;
        }
    }
}
