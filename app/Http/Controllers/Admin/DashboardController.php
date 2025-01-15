<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\Settings\Agreement\Accept;
use App\Enums\AgreementVersionStatus;
use App\Enums\Status;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Agreement\AgreementAcceptRequest;
use App\Models\Agreement;
use App\Models\User;
use App\Traits\AuthUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{

    use AuthUser;

    protected $accept;

    public function __construct(
        Accept $accept,
    ) {
        $this->accept = $accept;
        $this->initializeAuthUser();
    }

    public function index(): View
    {
        return view('admin.index');
    }

    public function passive(User $user): View
    {
        return view('layouts.passive', [
            'user' => $user
        ]);
    }

    public function sign(): View
    {
        $agreements = Agreement::where('user_type', $this->user->type)
            ->where('status', Status::ACTIVE)
            ->whereHas('versions', function($query) {
                $query->where('status', AgreementVersionStatus::PUBLISHED);
            })
            ->with(['versions' => function($query) {
                $query->where('status', AgreementVersionStatus::PUBLISHED)
                     ->latest('published_at');
            }])
            ->get()
            ->filter(function($agreement) {
                $latestVersion = $agreement->latestVersion();
                return $latestVersion && !$this->user->hasAcceptedVersion($latestVersion);
            });

        return view('user.sign', [
            'agreements' => $agreements
        ]);
    }

    public function accept(AgreementAcceptRequest $request): RedirectResponse
    {
        $agreement = $this->accept->execute($request->validated());
        return redirect()->back()
            ->with('success', $agreement->title . ' başarıyla kabul edildi.');
    }
}
