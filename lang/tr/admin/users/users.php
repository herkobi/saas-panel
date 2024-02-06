<?php

return [

    'page.title' => 'Panel Kullanıcıları',
    'page.text' => 'Kayıtlı Panel Kullanıcıları',

    'add.button' => 'Yeni Ekle',
    'table.status' => 'Durum',
    'table.info' => 'Ad Soyad',
    'table.title' => 'Görev',
    'table.name' => 'Ad Soyad',
    'table.edit' => 'Düzenle',

    'edit.page.title' => 'Kullanıcı Bilgilerini Düzenle',
    'create.page.title' => 'Yeni Kullanıcı Ekle',
    'personal.title' => 'Kişisel Bilgiler',
    'personal.text' => 'Lütfen tüm alanları doldurunuz',
    'account.title' => 'Hesap Bilgileri',
    'account.text' => 'Lütfen tüm alanları doldurunuz',

    'form.status' => 'Durum',
    'form.name' => 'Ad',
    'form.surname' => 'Soyad',
    'form.title' => 'Görev',
    'form.email' => 'E-posta Adresi',
    'form.username' => 'Kullanıcı Adı',
    'form.password' => 'Şifre',
    'form.password.text' => 'Şifre Oluşturun',

    'form.submit' => 'Kullanıcı Ekle',
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
    'store.success' => 'Kullanıcı başarılı bir şekilde kayıt edildi',
    'update.success' => 'Kullanıcı bilgileri başarılı bir şekilde güncellendi',
];
