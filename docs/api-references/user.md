## Login API

### `POST` Login API from FE
```
api/login
```
Login by API

### Request header
|Key|Value|
|---|---|
| Accept | application/json |

#### Parameters
| Key | Type | Required | Description |
|---|---|---|---|
| email | String | required | email to login |
| password | String | required | password |

#### Sample Request body
```json
{
  "email": "an.nguyen@gmail.com",
  "password": "123456",
}
```

#### Sample Response
```json
{
    "meta": {
        "message": null,
        "code": 200
    },
    "data": {
        "id": 18,
        "employee_code": "AT0123",
        "name": "An Nguyen Q.",
        "email": "an.nguyen@gmail.com.vn",
        "team": "PHP",
        "avatar_url": "http://172.16.110.17/images/user/avatar/350/40c1fc7286.png",
        "role": 1,
        "access_token": "40604dab9b3c87be058a3096d4f4f5e8",
        "created_at": "2018-02-01 07:51:59",
        "updated_at": "2018-02-09 03:37:16",
        "deleted_at": null
    }
}
```
#### Sample Request body
Password is empty
```json
{
  "email": "an.nguyen@gmail.com",
  "password": "",
}
```

#### Sample Request body
Failure password 
```json
{
  "email": "an.nguyen@gmail.com",
  "password": "abc",
}
```
```json
{
    "meta": {
        "message": "login_failure",
        "code" : 400
    },
}
```

## User Information API
### `GET` show information from FE
```
api/users/{id}
```
### Request header
|Key|Value|
|---|---|
| Accept | application/json |

#### Parameter
| Field | Type | Description |
|-------|------|-------------|
| id | Number | Id of user |

####Sample Response Success
```json
{
    "meta": {
        "message": null,
        "code": 200
    },
    "data": {
        "id": 1,
        "employee_code": "AT-351",
        "name": "Caden Kulas PhD",
        "email": "jordỹn@example.org",
        "team": "Android",
        "role": 0,
        "total_borrowed": 0,
        "total_donated": 4
    }
}
```
####Sample Response Fail
```json
{
    "meta": {
        "code": 404,
        "message": "Page Not Found"
    }
}
```
