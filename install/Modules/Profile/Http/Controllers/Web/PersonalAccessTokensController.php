<?php

namespace Modules\Profile\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Base\Support\JsPolicy;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\Base\Http\Controllers\Web\BaseController;
use Modules\Profile\Repositories\PersonalAccessTokenRepository;
use Modules\Profile\Http\Requests\CreatePersonalAccessTokenRequest;

class PersonalAccessTokensController extends BaseController
{
    /**
     * @var PersonalAccessTokenRepository $personalAccessTokens
     */
    protected $personalAccessTokens;

    public function __construct(PersonalAccessTokenRepository $personalAccessTokens) 
    {
        $this->personalAccessTokens = $personalAccessTokens;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('profile::personal-access-tokens', [
            'pageTitle' => __('Personal Access Tokens'),
            'policies' => JsPolicy::get('profile', '.'),
            'tokens' => auth()->user()->tokens()->orderBy('id', 'desc')->get()
        ]);
    }

    /**
     * Create new token
     * @param CreatePersonalAccessTokenRequest $request
     * @return JsonResponse
     */
    public function create(CreatePersonalAccessTokenRequest $request)
    {
        $token = $request->user()->createToken($request->token_name);
    
        return $this->successResponse('success', ['token' => $token->plainTextToken]);
    }

    /**
     * Revoke personal access token
     * @param Request $request
     * @return JsonResponse
     */
    public function revoke(Request $request)
    {
        $tokens = auth()->user()->tokens();

        $tokens = $tokens->where('id', $request->get('id'));
        
        if(is_null($tokens->first())) {
            return $this->errorNotFound();
        }

        $tokens->delete();

        return $this->successResponse('success');
    }
}
