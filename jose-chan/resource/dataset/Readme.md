## 应用管理模块数据库操作及ORM(学习自用)

#### 安装

````
composer require "jose-chan/app-dataset"
````

#### migration初始化数据库
````
php artisan migrate --path=vendor/jose-chan/app-dataset/database/migrations
````

#### Todo List

- 数据库测试
- Collection/Model可扩展
