@extends('layouts.panel')
@section('content')
    @include('admin.include.header', [
        'title' => 'Planlar',
    ])
    <div class="page-content flex-grow-1 d-flex flex-column shadow-sm">
        <div class="row flex-grow-1">
            <div class="col-20 col-lg-3 col-md-3">
                <div class="page-menu rounded-2">
                    @include('admin.plans.include.navigation')
                </div>
            </div>
            <div class="col-80 col-lg-9 col-md-9">
                <div class="card h-100 border-0 mb-5">
                    <div class="card-header border-0 bg-white p-0 mb-3">
                        <div class="d-flex align-items-center justify-content-between w-100 border-bottom pb-2">
                            <h1 class="card-title">Özellik Bilgileri Güncelle</h1>
                        </div>
                    </div>
                    <form action="{{ route('panel.feature.update', $feature->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-md-3 col-form-label required">Özellik Adı</label>
                                <div class="col-lg-10 col-md-9">
                                    <input type="text" id="name" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $feature->name) }}" placeholder="Özellik Adı" required>
                                    <small class="form-hint">Lütfen özellik adını giriniz.</small>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-lg-10 col-md-9 offset-lg-2 offset-md-3">
                                    <div class="mb-3 row">
                                        <div class="col-lg-4">
                                            <div class="custom-control custom-switch">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="consumable" name="consumable" value="1"
                                                        {{ old('consumable', $feature->consumable) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="consumableCheck">Kullanımı Takip
                                                        Et</label>
                                                </div>
                                                <span class="form-hint small">Kullanımı farklı periyotlarla sınırlandırmak
                                                    isterseniz seçiniz. Günde 3 kayıt, saatlik 20 işlem vb. gibi</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="custom-control custom-switch">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="quota" name="quota" value="1"
                                                        {{ old('quota', $feature->quota) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="quota">Dosya Kotası
                                                        Tanımla</label>
                                                </div>
                                                <span class="form-hint small">Belirli bir kullanım alanı tanımlamak
                                                    isterseniz seçiniz.</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="custom-control custom-switch">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="postpaid" name="postpaid" value="1"
                                                        {{ old('postpaid', $feature->postpaid) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="postpaid">Sonradan Ödemeli</label>
                                                </div>
                                                <span class="form-hint small">Kullanım sonrasında ödeme yapılacaksa
                                                    işaretleyiniz.</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="consumableOptions"
                                        style="{{ old('consumable', $feature->consumable) ? 'display: block;' : 'display: none;' }}">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="mb-3">
                                                    <label for="periodicity_type" class="form-label">Yenilenme Sıklığı
                                                        Türü</label>
                                                    <select class="form-select" id="periodicity_type"
                                                        name="periodicity_type">
                                                        <option>Bir tür seçin</option>
                                                        <option value="PeriodicityType::Day"
                                                            {{ old('periodicity_type', $feature->periodicity_type) == 'PeriodicityType::Day' ? 'selected' : '' }}>
                                                            Gün</option>
                                                        <option value="PeriodicityType::Week"
                                                            {{ old('periodicity_type', $feature->periodicity_type) == 'PeriodicityType::Week' ? 'selected' : '' }}>
                                                            Hafta</option>
                                                        <option value="PeriodicityType::Month"
                                                            {{ old('periodicity_type', $feature->periodicity_type) == 'PeriodicityType::Month' ? 'selected' : '' }}>
                                                            Ay</option>
                                                        <option value="PeriodicityType::Year"
                                                            {{ old('periodicity_type', $feature->periodicity_type) == 'PeriodicityType::Year' ? 'selected' : '' }}>
                                                            Yıl</option>
                                                    </select>
                                                    @error('periodicity_type')
                                                        <span class="invalid-feedback"
                                                            role="alert">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="mb-3">
                                                    <label for="periodicity" class="form-label">Yenilenme Sıklığı</label>
                                                    <input type="number" class="form-control" id="periodicity"
                                                        name="periodicity"
                                                        value="{{ old('periodicity', $feature->periodicity) }}">
                                                    <span class="form-hint small">Yenileme sıklığını giriniz. İşlemin kaç
                                                        dönemde bir tekrar kullanılabilir olacağını belirtiniz.</span>
                                                    @error('periodicity')
                                                        <span class="invalid-feedback"
                                                            role="alert">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-lg-6">
                                            <button type="submit" class="btn btn-success btn-sm w-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-checks">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M7 12l5 5l10 -10" />
                                                    <path d="M2 12l5 5m5 -5l5 -5" />
                                                </svg>
                                                Özelliği Güncelle
                                            </button>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="float-end">
                                                <button type="button" class="btn btn-sm btn-outline-danger px-3"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M4 7l16 0" />
                                                        <path d="M10 11l0 6" />
                                                        <path d="M14 11l0 6" />
                                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('panel.feature.delete', $feature->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deleteModalLabel">Dikkat!</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <strong>{{ $feature->name }}</strong> isimli özelliği silmek üzeresiniz. Bu işlem geri alınamaz.
                        Emin misiniz?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">İptal
                            Et</button>
                        <button type="submit" class="btn btn-sm btn-danger">Evet, Özelliği Sil</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // Elementleri seçme
        const consumableCheckbox = document.getElementById('consumable');
        const quotaCheckbox = document.getElementById('quota');
        const postpaidCheckbox = document.getElementById('postpaid');
        const consumableOptions = document.getElementById('consumableOptions');

        // Yardımcı fonksiyonlar
        function setElementsRequired(container, isRequired) {
            const inputs = container.getElementsByTagName('input');
            const selects = container.getElementsByTagName('select');

            [...inputs, ...selects].forEach(element => {
                element.required = isRequired;
            });
        }

        function toggleVisibility(element, isVisible) {
            element.style.display = isVisible ? 'block' : 'none';
        }

        function toggleDisabled(checkbox, isDisabled) {
            checkbox.disabled = isDisabled;
        }

        // Consumable checkbox event listener'ı
        consumableCheckbox.addEventListener('change', function() {
            toggleVisibility(consumableOptions, this.checked);
            setElementsRequired(consumableOptions, this.checked);

            // quotaCheck ve postpaid switchlerini etkinleştir/devre dışı bırak
            toggleDisabled(quotaCheckbox, !this.checked);
            toggleDisabled(postpaidCheckbox, !this.checked);

            // Eğer consumable kapatılırsa, diğer checkboxları da kapat
            if (!this.checked) {
                quotaCheckbox.checked = false;
                postpaidCheckbox.checked = false;
            }
        });

        // İlk yükleme için durumu ayarla
        toggleDisabled(quotaCheckbox, !consumableCheckbox.checked);
        toggleDisabled(postpaidCheckbox, !consumableCheckbox.checked);
    </script>
@endsection
