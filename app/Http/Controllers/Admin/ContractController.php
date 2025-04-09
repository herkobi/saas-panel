<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contract;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Services\Admin\ContractService;
use App\Http\Requests\Admin\Contract\ContractCreateRequest;
use App\Http\Requests\Admin\Contract\ContractUpdateRequest;
use Inertia\Inertia;

class ContractController extends Controller
{
    public function __construct(protected ContractService $contractService)
    {
    }

    /**
     * Display a listing of the contracts.
     */
    public function index()
    {
        $contracts = $this->contractService->getAllContracts();

        return Inertia::render('admin/contracts/Index', [
            'contracts' => $contracts->map(function ($contract) {
                return [
                    'id' => $contract->id,
                    'title' => $contract->title,
                    'slug' => $contract->slug,
                    'type' => $contract->type,
                    'date' => $contract->date,
                    'status' => $contract->status,
                    'created_at' => $contract->created_at->format('Y-m-d'),
                ];
            }),
        ]);
    }

    /**
     * Show the form for creating a new contract.
     */
    public function create()
    {
        return Inertia::render('admin/contracts/Create');
    }

    /**
     * Store a newly created contract in storage.
     */
    public function store(ContractCreateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->contractService->create($validated);

        return redirect()->route('panel.contracts.index')
            ->with('success', 'Sözleşme başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing the specified contract.
     */
    public function edit(Contract $contract)
    {
        return Inertia::render('admin/contracts/Edit', [
            'contract' => [
                'id' => $contract->id,
                'title' => $contract->title,
                'slug' => $contract->slug,
                'type' => $contract->type,
                'date' => $contract->date,
                'content' => $contract->content,
                'status' => $contract->status,
            ],
        ]);
    }

    /**
     * Update the specified contract in storage.
     */
    public function update(ContractUpdateRequest $request, Contract $contract): RedirectResponse
    {
        $validated = $request->validated();

        $this->contractService->update($contract, $validated);

        return redirect()->route('panel.contracts.index')
            ->with('success', 'Sözleşme başarıyla güncellendi.');
    }

    /**
     * Remove the specified contract from storage.
     */
    public function destroy(Contract $contract): RedirectResponse
    {
        $this->contractService->delete($contract);

        return redirect()->route('panel.contracts.index')
            ->with('success', 'Sözleşme başarıyla silindi.');
    }
}
