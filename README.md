# API Articles

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
```json
{
  "code": "success",
  "message": "",
  "data": {
    "status": 200,
    "response": [{
      "id": 12,
      "title": "Article Three",
      "subtitle": "Article",
      "tags": [
        {
          "term_id": 7,
          "name": "great",
          "slug": "great",
          "term_group": 0,
          "term_taxonomy_id": 7,
          "taxonomy": "post_tag",
          "description": "",
          "parent": 0,
          "count": 1,
          "filter": "raw"
        },
        {
          "term_id": 8,
          "name": "perfect",
          "slug": "perfect",
          "term_group": 0,
          "term_taxonomy_id": 8,
          "taxonomy": "post_tag",
          "description": "",
          "parent": 0,
          "count": 5,
          "filter": "raw"
        }
      ],
      "imageUrl": "https://domain.com/wp-content/uploads/2021/06/mirastar-boxes-img-1.jpg",
      "articleUrl": "https://domain.com/app/api/v1/article/article-three/",
      "articleSlug": "article-three",
      "viewed": 0,
      "createdAt": "2021-06-01 08:12:51"
    }]
  }
},
{
...
}
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

    <!-- Article > Single -->
    <div class="app_article__single">

        <!-- Title -->
        <h1 class="app_article__title">
            Article Title
        </h1>
        <!-- End Title -->

        <!-- Image -->
        <div class="app_article__image">
            <img src="https://domain.com/wp-content/uploads/2021/06/mirastar-boxes-img-1.jpg" alt="" title="" />
        </div>
        <!-- End Image -->

        <!-- Text -->
        <div class="app_article__text">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod</p>
        </div>
        <!-- End Text -->

    </div>
    <!-- End Article > Single -->

</div>
<!-- End Article -->
```