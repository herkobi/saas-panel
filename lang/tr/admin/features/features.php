<?php

return [

    'page.title' => 'Özellikler',
    'page.text' => 'Kayıtlı Özellikler',
    'feature.title' => 'Özellik Bilgileri',
    'feature.text' => 'Özellika Ait Genel Bilgiler',

    'add.button' => 'Yeni Ekle',
    'table.status' => 'Durum',
    'table.title' => 'Özellik Adı',
    'table.usage' => 'Kullanım',
    'table.consumable' => 'Limit',
    'table.period' => 'Her :interval :unit bir yenilenir',
    'table.edit' => 'Düzenle',

    'edit.page.title' => 'Özellik Bilgilerini Düzenle',
    'create.page.title' => 'Yeni Özellik Ekle',

    'form.status' => 'Durum',
    'form.title' => 'Özellik Adı',
    'form.consumable' => 'Kullanımı Limitlendir',
    'form.postpaid' => 'Önce Öde Sonra Kullan',
    'form.quota' => 'Dosya Kotası Tanımla',
    'form.periodicity' => 'Kullanım Döngüsü',
    'form.periodicity_type' => 'Kullanım',

    'form.submit' => 'Özellik Ekle',
    'form.update' => 'Bilgileri Güncelle',

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
    'store.success' => 'Özellik başarılı bir şekilde kayıt edildi',
    'update.success' => 'Özellik bilgileri başarılı bir şekilde güncellendi',
];
