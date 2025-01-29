@extends('layouts.panel')
@section('content')
    @include('admin.include.header', [
        'title' => 'Planlar',
    ])
    @include('admin.plans.include.navigation')
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('admin.plans.include.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="page-form row">
                        <div class="col-lg-10 offset-lg-1">
                            <h3 class="form-title border-bottom mb-3 pb-2">Özellik Bilgilerini Düzenle</h3>
                            <form action="{{ route('panel.feature.update', $feature->id) }}" method="POST">
                                @csrf
                                <div class="row mb-3">
                                    <label for="name" class="col-form-label col-lg-2 col-md-3">Özellik Adı</label>
                                    <div class="col-lg-10 col-md-9">
                                        <div class="input-group">
                                            <div class="input-group-text rounded-0 border-end-0 bg-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path
                                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd"
                                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg>
                                            </div>
                                            <input id="name" type="text" name="name"
                                                class="form-control rounded-0 border-start-0"
                                                placeholder="Özellik Adını Giriniz"
                                                value="{{ old('name', $feature->name) }}" required>
                                        </div>
                                        <span class="form-hint small">Özellik adını giriniz</span>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <label for="name" class="col-form-label col-lg-2 col-md-3">Özellik Bilgileri</label>
                                    <div class="col-lg-10 col-md-9">
                                        <div class="row mb-3">
                                            <!-- Tüketilebilir Özellik -->
                                            <div class="col-lg-4">
                                                <div class="custom-control custom-switch">
                                                    <div class="form-check form-switch border-bottom pb-3 mb-2">
                                                        <input class="form-check-input" type="checkbox" id="consumable"
                                                            name="consumable" value="1">
                                                        <label class="form-check-label">Kullanımı Takip Et</label>
                                                    </div>
                                                    <span class="form-hint small">Kullanım miktarı takip edilecekse
                                                        seçin</span>
                                                </div>
                                            </div>
                                            <!-- Kota Takibi -->
                                            <div class="col-lg-4">
                                                <div class="custom-control custom-switch">
                                                    <div class="form-check form-switch border-bottom pb-3 mb-2">
                                                        <input class="form-check-input" type="checkbox" id="quota"
                                                            name="quota" value="1" disabled>
                                                        <label class="form-check-label">Kota Takibi</label>
                                                    </div>
                                                    <span class="form-hint small">Eğer özellik belirli bir dönemde
                                                        yenilenecek/sıfırlanacaksa bu seçeneği kapatıp periyot belirleyiniz.
                                                        Örn: Aylık SMS kotası</span>
                                                </div>
                                            </div>
                                            <!-- Sonradan Ödeme -->
                                            <div class="col-lg-4">
                                                <div class="custom-control custom-switch">
                                                    <div class="form-check form-switch border-bottom pb-3 mb-2">
                                                        <input class="form-check-input" type="checkbox" id="postpaid"
                                                            name="postpaid" value="1" disabled>
                                                        <label class="form-check-label">Sonradan Ödemeli</label>
                                                    </div>
                                                    <span class="form-hint small">Özellik kullanıldıktan sonra ödemeye izin
                                                        ver</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Periyot Alanları -->
                                        <div id="periodicityOptions" style="display: none;">
                                            <div class="row mb-3">
                                                <div class="col-lg-6">
                                                    <label class="col-form-label">Yenilenme Sıklığı Türü</label>
                                                    <select class="form-select" id="periodicity_type"
                                                        name="periodicity_type">
                                                        <option>Bir periyot seçin</option>
                                                        <option value="PeriodicityType::Day">Gün</option>
                                                        <option value="PeriodicityType::Week">Hafta</option>
                                                        <option value="PeriodicityType::Month">Ay</option>
                                                        <option value="PeriodicityType::Year">Yıl</option>
                                                    </select>
                                                    <span class="form-hint small">Yenileme sıklığını seçiniz.</span>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="col-form-label">Yenilenme Sıklığı</label>
                                                    <input type="number" class="form-control" id="periodicity"
                                                        name="periodicity">
                                                    <span class="form-hint small">Yenileme sıklığını giriniz.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-10 col-mg-9 offset-lg-2 offset-md-3">
                                        <div class="d-flex align-items-center justify-content-between w-100">
                                            <button type="submit" class="btn rounded-1 px-5">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    fill="currentColor" class="bi bi-floppy" viewBox="0 0 20 20">
                                                    <path d="M11 2H9v3h2z" />
                                                    <path
                                                        d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z" />
                                                </svg>
                                                GÜNCELLE
                                            </button>
                                            <button type="button" class="btn bg-white border-0 text-danger"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                    <path
                                                        d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                    <path
                                                        d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                                </svg>
                                                SİL
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
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
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <button type="button" class="btn bg-white border-0 text-dark" data-bs-dismiss="modal">İptal
                                Et</button>
                            <button type="submit" class="btn">Evet, Özelliği Sil</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const consumableCheckbox = document.getElementById('consumable');
            const quotaCheckbox = document.getElementById('quota');
            const postpaidCheckbox = document.getElementById('postpaid');
            const periodicityOptions = document.getElementById('periodicityOptions');
            const periodicityTypeSelect = document.getElementById('periodicity_type');
            const periodicityInput = document.getElementById('periodicity');

            // Tüketilebilir özellik değiştiğinde
            consumableCheckbox.addEventListener('change', function() {
                const isConsumable = this.checked;

                quotaCheckbox.disabled = !isConsumable;
                postpaidCheckbox.disabled = !isConsumable;

                if (isConsumable) {
                    // Tüketilebilir seçildiğinde quota otomatik true olsun
                    quotaCheckbox.checked = true;
                    periodicityOptions.style.display = 'none';
                    clearPeriodicityFields();
                } else {
                    // Tüketilebilir değilse hepsi kapansın
                    quotaCheckbox.checked = false;
                    postpaidCheckbox.checked = false;
                    periodicityOptions.style.display = 'none';
                    clearPeriodicityFields();
                }
            });

            // Kota seçeneği değiştiğinde
            quotaCheckbox.addEventListener('change', function() {
                const isQuota = this.checked;

                if (isQuota) {
                    periodicityOptions.style.display = 'none';
                    clearPeriodicityFields();
                } else {
                    periodicityOptions.style.display = 'block';
                    periodicityTypeSelect.required = true;
                    periodicityInput.required = true;
                }
            });

            function clearPeriodicityFields() {
                periodicityTypeSelect.value = '';
                periodicityInput.value = '';
                periodicityTypeSelect.required = false;
                periodicityInput.required = false;
            }

            // Sayfa yüklendiğinde mevcut duruma göre ayarla
            consumableCheckbox.dispatchEvent(new Event('change'));

            // Edit formu için eski değerleri doldur
            const oldFeatureData = {!! isset($feature) ? json_encode($feature) : 'null' !!};
            if (oldFeatureData) {
                consumableCheckbox.checked = oldFeatureData.consumable;
                quotaCheckbox.checked = oldFeatureData.quota;
                postpaidCheckbox.checked = oldFeatureData.postpaid;

                // Period alanlarını başlangıçta gizli tut
                periodicityOptions.style.display = 'none';

                // Eğer period bilgileri varsa
                if (oldFeatureData.periodicity_type && oldFeatureData.periodicity) {
                    periodicityTypeSelect.value = `PeriodicityType::${oldFeatureData.periodicity_type}`;
                    periodicityInput.value = oldFeatureData.periodicity;
                    periodicityOptions.style.display = 'block';
                }

                consumableCheckbox.dispatchEvent(new Event('change'));
                quotaCheckbox.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection
