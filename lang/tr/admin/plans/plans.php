<?php

return [

    'page.title' => 'Planlar',
    'page.text' => 'Kayıtlı Planlar',
    'plan.title' => 'Plan Bilgileri',
    'plan.text' => 'Plana Ait Genel Bilgiler',

    'add.button' => 'Yeni Ekle',
    'table.status' => 'Durum',
    'table.title' => 'Plan Adı',
    'table.usage' => 'Plan Döngüsü',
    'table.price' => 'Fiyatı',
    'table.free' => 'Ücretsiz',
    'table.period' => 'Her :interval :unit bir ödemeli',

    'table.edit' => 'Düzenle',
    'table.restore' => 'Geri Al',
    'table.destroy' => 'Tamamen Sil',

    'edit.page.title' => 'Plan Bilgilerini Düzenle',
    'create.page.title' => 'Yeni Plan Ekle',
    'personal.title' => 'Kişisel Bilgiler',
    'personal.text' => 'Lütfen tüm alanları doldurunuz',
    'account.title' => 'Hesap Bilgileri',
    'account.text' => 'Lütfen tüm alanları doldurunuz',

    'form.status' => 'Durum',
    'form.title' => 'Plan Adı',
    'form.price' => 'Ücreti',
    'form.currency_id' => 'Para Birimi',
    'form.periodicity' => 'Ödeme Döngüsü',
    'form.periodicity_type' => 'Döngü',
    'form.grace_days' => 'Ödeme Beklenecek Ek Gün',

    'form.submit' => 'Plan Ekle',
    'form.update' => 'Bilgileri Güncelle',
    'form.delete' => 'Planı Sil',

    'confirm.title' => 'Planı Sil',
    'confirm.text' => 'Bu işlem geri alınamaz. Lütfen silme işleminden önce gerekli kontrolleri yaptığınızdan emin olunuz.',
    'confirm.cancel' => 'İptal Et',
    'confirm.submit' => 'Evet, Sil',

    'status' => [
        'required' => 'Lütfen durum seçiniz',
        'integer' => 'Lütfen geçerli formatta durum seçiniz',
    ],

    'name' => [
        'required' => 'Lütfen isim giriniz',
        'string' => 'Lütfen geçerli bir isim giriniz',
        'max255' => 'Lütfen 255 karakterden kısa isim giriniz',
    ],

    'surname' => [
        'required' => 'Lütfen soyisim giriniz',
        'string' => 'Lütfen geçerli bir soyisim giriniz',
        'max255' => 'Lütfen 255 karakterden kısa soyisim giriniz',
    ],

    'title' => [
        'required' => 'Lütfen görev giriniz',
        'string' => 'Lütfen geçerli bir görev giriniz',
        'max255' => 'Lütfen 255 karakterden kısa görev giriniz',
    ],

    'username' => [
        'required' => 'Lütfen kullanıcı adı giriniz',
        'string' => 'Lütfen geçerli bir kullanıcı adı giriniz',
        'max255' => 'Lütfen 255 karakterden kısa kullanıcı adı giriniz',
        'unique' => 'Bu kullanıcı adı ile kayıt mevcut',
    ],

    'email' => [
        'required' => 'Lütfen e-posta adresi giriniz',
        'email' => 'Lütfen geçerli bir e-posta adresi giriniz',
        'max255' => 'Lütfen 255 karakterden kısa e-posta adresi giriniz',
        'unique' => 'Bu e-posta adresi ile kayıt mevcut',
    ],

    'password' => [
        'required' => 'Lütfen şifre giriniz',
        'password' => 'Lütfen geçerli bir şifre giriniz',
    ],

    /**
     * Form Requests
     */
    'store.success' => 'Plan başarılı bir şekilde kayıt edildi',
    'update.success' => 'Plan bilgileri başarılı bir şekilde güncellendi',
    'delete.success' => 'Plan başarılı bir şekilde silindi',
    'destroy.error' => 'Kayıt silinemedi. Lütfen ilgili kayıtları kontrol ediniz',
];
