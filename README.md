# wisebits

1. Installation.

See the instructions in the repository https://github.com/bubble-double/wisebits-docker

2. API
   
The API has two methods.

2.1. Get statistic

Request
```angular2html
GET /api/statistics
```

Successful response
```angular2html
{
  "ru": "5",
  "fr": "10",
}

HTTP status code 200
```

Fail response
```angular2html
{
  "status": 400,
  "error": "Some error message",
}

HTTP status code 400
```

2.2 Update statistic
```angular2html
POST /api/statistics/{{ countryCode }}
```

Where {{ countryCode }} is a string with a short country code. For example: "fr".

Only lowercase letters: [a-z]+

Successful response
```angular2html
{}

HTTP status code 201
```

Fail response
```angular2html
{
  "status": 400,
  "error": "Some error message",
}

HTTP status code 400
```

-----------

3. The task description

```angular2html
Задание 5
Написать код на PHP, реализующий REST API, предназначенный для учёта посещений сайта 
с разбиением по странам.

Сервис должен предоставлять два метода:

Обновление статистики, принимает один аргумент – код страны (ru, us, it...).
Предполагаемая нагрузка: 1 000 запросов в секунду.
Получение собранной статистики по всем странам, возвращает JSON-объект вида:
{ код страны: количество, cy: 123, us: 456, ... }. 
Предполагаемая нагрузка: 1 000 запросов в секунду.
Хранилище данных: Redis.
Допустимо использование готовых библиотек, фреймворков и т.п..

На оценку влияет готовность к высоким нагрузкам, читаемость кода, обработка ошибок.
```
