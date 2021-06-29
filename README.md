# API Articles

____

### Инструкция подключения
____

###### 1. Папка с модулем

```
Помеcтить папку "api" в корень темы в папку "classes".

Пример:
https://domain.com/wp-content/themes/{THEME_NAME}/classes/api
```
____
###### 2. Подключение модуля
```php
/**
 * Вставить эту строку в файл functions.php
 * Файл functions.php находится в корне темы: https://domain.com/wp-content/themes/functions.php
 * 
 */
require get_template_directory() . '/classes/api/CustomAPI.php';
```
____
Описание запросов
-----------
**Метод GET**
```php
https://domain.com/wp-json/api/v1/articles/?key=value
```

| Ключ       | Значение                | Описание |
| :------------- |:------------------:| :-----|
| tags     | string    | Выборка записей по тэгам (tag_1,tag_2 ...) |
| sort     | string    | Сортировка записей (ASC or DESC) |
| offset     | integer    | Постраничная навигация |
| limit     | integer    | Лимит записей |
| readerId     | integer    | ID пользователя, для того, что бы отсеять записи, которые он просмотрел |
| viewed     | integer    | Показать записи, которые пользователь смотрел (true / false) |
| createdFrom     | integer    | Дата от (Unix - time()) |
| createdTo     | integer    | Дата до (Unix - time()) |
```json
[
  {
    "id": "26",
    "title": "Article Five",
    "subtitle": "Five",
    "tags": [
      "tour"
    ],
    "imageUrl": "https://domain.com/wp-content/uploads/2021/06/mirastar-boxes-img-1.jpg",
    "articleUrl": "https://domain.com/app/api/v1/article/article-five/",
    "viewed": false,
    "createdAt": 1622623317
  },
  {
    "id": "18",
    "title": "Article Seven",
    "subtitle": "Seven",
    "tags": [
      "tour"
    ],
    "imageUrl": "https://domain.com/wp-content/uploads/2021/06/mirastar-boxes-img-1.jpg",
    "articleUrl": "https://domain.com/app/api/v1/article/article-seven/",
    "viewed": false,
    "createdAt": 1622621735
  },
  {
    ...
  }
]
```

```php
https://domain.com/app/api/v1/article/{articleSlug}/?readerId=ID
```

| Ключ       | Значение                | Описание |
| :------------- |:------------------:| :-----|
| articleSlug     | string    | Slug Записи, является уникальным |
| readerId     | integer    | ID пользователя, для записи в базу что он прочитал эту запись |

```html
<!-- Article -->
<div class="app_article">

    Your html

</div>
<!-- End Article -->
```
