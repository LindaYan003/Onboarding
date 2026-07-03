# 创建

Request

POST /packages HTTP/1.1

Host: example.com

Content-Type: application/json



{

"name": "Susan",

"address": "1234 Maple Street, City, State, zipcode",

"delivered": false,

"package_num": "12345",

"phone_num": "12345678901",

"email": "1234@example.com"

}



Response

HTTP/1.1 201 Created

Content-Type: application/json

Location: http://example.com



{

"id": 123,

"message": "Package added successfully",

"package_num": "12345"

}

# 删除

Request

DELET/number_packages HTTP/1.1

Host: example.com

Content-Type: application/json



{

"package_num": "12345",

}

Response

HTTP/1.1 200 OK

Content-Type: application/json



{

"id": 123,

"message": "Package deleted successfully",

"package_num": "12345"

}



HTTP/1.1 404 Not Found

Content-Type: application/json

{

"id": 123,

"message": "Package does not exist",

"package_num": "12345"

}



# 搜索

Request

GET /packages/account?name=Susan&phone_num=12345678901&email=1234@example.com HTTP/1.1

Host: example.com



GET /packages/12345 HTTP/1.1

Host: example.com 

Response

HTTP/1.1 404 Not Found

Content-Type: application/json



{

"message": "No package exists with this account",

"search_criteria": {

    "name": "Susan", 

    "phone_num": "12345678901", 

    "email": "1234@example.com" 

}

}

HTTP/1.1 404 Not Found

Content-Type: application/json



{

"message": "No package exists with this ID",

"search_criteria": {

“id”:”12345”

}

}





HTTP/1.1 200 OK

Content-Type: application/json



[

{

    "id": 123, 

    "name": "Susan", 

    "address": "1234 Maple Street, City, State, zipcode", 

    "delivered": false, 

    "package_num": "12345", 

    "phone_num": "12345678901", 

    "email": "1234@example.com" 

}

]

# 所有包裹

Request

GET /packages HTTP/1.1

Host: example.com



Response

HTTP/1.1 200 OK

Content-Type: application/json



[

{

    "id": 123, 

    "name": "Susan", 

    "address": "1234 Maple Street, City, State, zipcode", 

    "delivered": false, 

    "package_num": "12345", 

    "phone_num": "12345678901", 

    "email": "1234@example.com" 

},

{

    "id": 124, 

    "name": "Bod", 

    "address": "4321 GOOD Street, City, State, zipcode", 

    "delivered": false, 

    "package_num": "12345", 

    "phone_num": "12345678901", 

    "email": "1234@example.com" 

}



]



# 修改包裹

Request

PUT /packages HTTP/1.1

Host: example.com

Content-Type: application/json



{

"name": "Susan",

"address": "4321  New Street, City, State, zipcode",

"delivered": false,

"package_num": "12345",

"phone_num": "12345678901",

"email": "1234@example.com"

}



Response

HTTP/1.1 200 OK

Content-Type: application/json

Location: http://example.com



{

"id": 123,

"message": "Package information changed",

"package_num": "12345"

} 

 