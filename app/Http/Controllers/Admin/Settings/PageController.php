<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\ContractCreateRequest;
use App\Http\Requests\Admin\Settings\ContractUpdateRequest;
use App\Models\Admin\Contract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        $contracts = Page::all();
        return view('admin.settings.contracts.index', [
            'contracts' => $contracts
        ]);
    }

    public function create(): View
    {
        return view('admin.settings.contracts.create');
    }

    public function store(ContractCreateRequest $request): RedirectResponse
    {
        $contract = Contract::create([
            'status' => $request->status,
            'title' => $request->title,
            'text' => $request->text
        ]);

        return Redirect::route('panel.settings.contracts')->with('success', __('admin/settings/contract.store.success'));
    }

    public function edit(Contract $contract): View
    {
        return view('admin.settings.contracts.edit', [
            'contract' => $contract
        ]);
    }

    public function update(ContractUpdateRequest $request, Contract $contract): RedirectResponse
    {

        $contract->status = $request->status;
        $contract->title = $request->title;
        $contract->text = $request->text;
        $contract->save();

        return Redirect::route('panel.settings.contracts')->with('success', __('admin/settings/contract.update.success'));

    }
}
