<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\UpdateSiteSettingRequest;
use App\Repositories\Interfaces\SiteSettingRepositoryInterface;

class SiteSettingsController extends Controller
{
    private $siteSettingRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SiteSettingRepositoryInterface $siteSettingRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();

        $this->siteSettingRepository = $siteSettingRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        $records = $this->siteSettingRepository->findFirst();

        return view('admin.siteSettings', compact('records'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateSiteSettingRequest $request, $id)
    {

        $data = $request->except([
            '_token',
            '_method',
        ]);

        $this->siteSettingRepository->update($id, $data);

        return redirect()
            ->route('admin.site-settings.index')
            ->with('success', 'Site settings was updated successfully!');
    }
}
