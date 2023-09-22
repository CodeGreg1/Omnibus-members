<?php

namespace Modules\Menus\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Renderable;
use Modules\Menus\Repositories\MenuRepository;
use Modules\Menus\Repositories\MenuLinkRepository;
use Modules\Base\Http\Controllers\Web\BaseController;

class MenuItemsController extends BaseController
{
    /**
     * @var MenuLinkRepository $menuLink
     */
    protected $menuLink;

    /**
     * @var MenuRepository $menus
     */
    protected $menus;

    public function __construct(MenuLinkRepository $menuLink, MenuRepository $menus) 
    {
        $this->menuLink = $menuLink;

        $this->menus = $menus;

        parent::__construct();
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        $this->authorize('admin.menus.delete-menu-item');
        
        if($request->get('id')) {
            $this->menuLink->delete($this->menuLink->find($request->get('id')));
        }

        return $this->successResponse(__('Menu item deleted successfully.'));
    }
}
