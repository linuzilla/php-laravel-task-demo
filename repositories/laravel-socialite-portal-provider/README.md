# Laravel 專案使用 NCU Portal 登入

中央大學 Portal 支援 OAuth2 的協定，
應用系統採用 Laravel 框架可以很容易利用 Laravel Socialite 來達到簽入。

本文件測試的環境是使用 php 7.4.x, Laravel 7.0, Laravel Socialite 4.3

#### Step 1:
在一個 Laravel 專案的目錄下，建立一個 repositories 的資料夾，並從 github 把登入相關程式碼抄到本地

```shell
mkdir repositories
cd repositories
git clone https://github.com/ncucc/laravel-socialite-portal-provider.git
```

#### Step 2:
修改 composer.json 檔按，把 local 的路徑加進設定。

```json
"repositories": [
    {
       "type": "path",
       "url": "./repositories/laravel-socialite-portal-provider"
    }
]
```

#### Step 3:
用 composer 把 laravel socialite 及 Portal OAuth2 介接的程式加到專案。

```shell
composer require laravel/socialite
composer require ncucc/portal-oauth2-provider
```

#### Step 4:
在 config 目錄下建一 ncu.php 的檔案，內容如下:
```php
<?php

return [
    'portal' => [
	'client_id' => env('PORTAL_CLIENT_ID'),
	'client_secret' => env('PORTAL_CLIENT_SECRET'),
	'redirect' => env('PORTAL_REDIRECT'),
    ],
];
```

#### Step 5:
修改 .env 檔案, 設定好登入相關資料

```
PORTAL_CLIENT_ID=ClientId
PORTAL_CLIENT_SECRET=ClientSecret
PORTAL_REDIRECT=Return URL
```

#### Step 6:
在 app/Providers/AppServiceProvider.php 檔案中
加一個 bootPortalSocialite 的 function

```php
private function bootPortalSocialite()
{
    $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');

    $socialite->extend(
        'portal',
         function ($app) use ($socialite) {
             $config = $app['config']['ncu.portal'];
             return $socialite->buildProvider(\Ncucc\Portal\PortalBaseProvider::class, $config);
         }
    );
}
```
並在 boot() function 內加入呼叫 bootPortalSocialite() 的程式碼:

```php
$this->bootPortalSocialite();
```

#### Step 7:
產生一個 PortalLogin 的 controller

```shell
php artisan make:controller PortalLoginController
```

在這個 Controller 加兩個 method

```php
public function redirectToProvider()
{
    return \Socialite::with('portal')->redirect();
}

public function handleProviderCallback()
{
    $user = \Socialite::with('portal')->user();

    // dd($user);
    // 登入成功 ... 產生一個 User 的物作, 用 Auth:login() 去讓 User 登入
}
```


#### Step 8:

把新加的 method 註冊在 route 裡:

```php
Route::get('login', ['as' => 'login', 'uses' => 'PortalLoginController@redirectToProvider']);
Route::get('callback', ['as' => 'callback', 'uses' => 'PortalLoginController@handleProviderCallback']);
```

接下來就可以進行測試。
