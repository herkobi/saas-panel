<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use App\Actions\Admin\AccountGroup\Create;
use App\Actions\Admin\AccountGroup\Delete;
use App\Actions\Admin\AccountGroup\Update;
use App\Http\Requests\Admin\AccountGroup\AccountGroupCreateRequest;
use App\Http\Requests\Admin\AccountGroup\AccountGroupUpdateRequest;
use App\Services\Admin\Accounts\AccountGroupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AccountGroupsController extends Controller
{
    protected $accountGroupService;
    protected $create;
    protected $update;
    protected $delete;

    public function __construct(
        AccountGroupService $accountGroupService,
        Create $create,
        Update $update,
        Delete $delete
    ) {
        $this->accountGroupService = $accountGroupService;
        $this->create = $create;
        $this->update = $update;
        $this->delete = $delete;
    }

    public function index(): View
    {
        $accountgroups = $this->accountGroupService->getAllAccountGroups();
        return view('admin.accountgroups.index', [
            'groups' => $accountgroups
        ]);
    }

    public function detail(string $id): View
    {
        $group = $this->accountGroupService->getAccountGroupById($id);
        return view('admin.accountgroups.detail', [
            'group' => $group
        ]);
    }

    public function create(): View
    {
        return view('admin.accountgroups.create');
    }

    public function store(AccountGroupCreateRequest $request): RedirectResponse
    {
        $created = $this->create->execute($request->validated());
        return $created
                ? Redirect::route('panel.accountgroups')->with('success', 'Yeni müşteri grubu başarılı bir şekilde oluşturuldu')
                : Redirect::back()->with('error', 'Yeni müşteri grubu oluşturulurken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function edit(string $id): View
    {
        $group = $this->accountGroupService->getAccountGroupById($id);
        return view('admin.accountgroups.edit', [
            'group' => $group
        ]);
    }

    public function update(AccountGroupUpdateRequest $request, string $id): RedirectResponse
    {
        $updated = $this->update->execute($id, $request->validated());
        return $updated
                ? Redirect::route('panel.accountgroups')->with('success', 'Müşteri grubu başarılı bir şekilde güncellendi')
                : Redirect::back()->with('error', 'Müşteri grubu güncellenirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $group = $this->accountGroupService->getAccountGroupById($id);

        if ($group->users()->count() > 0) {
            return Redirect::back()->with('error', 'Bu kategorinin alt kategorileri bulunmaktadır. Önce alt kategorileri silmeniz gerekmektedir.');
        }

        $deleted = $this->delete->execute($id);
        return $deleted
                ? Redirect::route('panel.accountgroups')->with('success', 'Müşteri grubu başarılı bir şekilde silindi.')
                : Redirect::back()->with('error', 'Müşteri grubu silinirken bir hata oluştu. Lütfen tekrar deneyiniz.');
    }
}
