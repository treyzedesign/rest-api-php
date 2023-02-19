## OVERVIEW
A RBAC BLOG SYSTEM API

This Role-Based-Access-Contol blogging system allows an enterprise to manage and control activities on thier systems 
by restricting network access based on the roles of individual users within that enterprise.

* LANGUAGE : PHP
* DATAbASE : MySQL
* TABLES
  - users
  - roles
  - blogs
  - policies

### ENDPOINTS

* registration
  - The user will be assigned the role of "super-admin" if this is the first user to be inserted, if not the user will be 
    assigned the role of "user"
  - you cannot register a user with an existing email in the DB table
  - The user should provide thier required details : fisrtname, lastname, email and password

  - url: localhost:8080/register.php
    method: POST
    request body: 
     ```
      {
          "email": "email@email.com",
          "password": "password",
          "cpassword" : "password",
          "lname: "John",
          "fname": "Doe"

      }
      ```

* Login 
  - the user should provide their details, if it exists in the database table the user would log in successfully
  - The user should provide thier required details : email and password

  - url: localhost:8080/login.php
    method: POST
    request body:
    ```
    {
        "email": "email@email.com",
        "password": "password",

    }
    ```

* Add Roles
  - This endpoint allows us to add roles to the roles table.
  - This endpoint requires the id of the "Super-admin" for authentication and authorization purposes which will passed in the request header.
  
  - url: localhost:8080/addrole.php 
    method: POST
    header : { Authorization: "Bearer {user id}" }
    request body: 
    ```
    {
      "user_id": 2,
      "policies": "can-update-users",
      "name": "creator-admin" 
    }
    ```

* delete Roles
  - This endpoint will delete role from the roles table
  - This endpoint requires the id of the "Super-admin" for authentication and authorization purposes which will passed in the request header.

  - url: localhost:8080/deleterole.php/{role_id}
    method: DELETE
    parameter: role id
    header: { Authorization: "Bearer {user id}" }

* Create Blog
  - You have to provide the id of the user so as to determine the author of the the blog.
  - The user id will be passed in the Authorization header

  - url: localhost:8080/createblog.php
  - method : POST
  - headers: {Authorization : "Bearer {user_id}"}
  - request body:
      ```
      {
          "title" : "PHP Explained",
          "category_id" : "2",
          "content": "PHP"
      }
      ```

* Get all blogs for admin
  - This endpoint allows us to fetch data from the DB for the admin so as to publish the blogs 
  - The admin must have the policy to update so the Admin's Id would be passed in the header for authentication and       authorization purpose.
  - This blogs will be restricted to specific role

  - url: localhost:8080/getblogsadmin/blogs
    method: GET
    header: {Authorization: "Bearer {user_id}"}

* Get all published blogs
  - This endpoint allows us to get all published blogs
  - This blog will be accessible to every roles

  - url: localhost:8080/getblogs.php/blogs
    method: GET

* Get single blog
  - This endpoint requires the id of the user for authentication and Authorization purposes which will passed in the request header
  - This endpoint requires the id of the blog to be found blogs table
  - Only the Role of the "Super-admin" and "Editor-admin" have the permissions to get a single blog.

  - url: localhost:8080/getblogs.php/blogs/{id}
    parameter: blog id
    method: GET
    header: {Authorization: "Bearer {user_id}"}

* update blog
  - This endpoint requires the id of the user for authentication and authorization purposes which will passed in the request header
  - This endpoint requires the id of the blog to be found blogs table

  - url:localhost:8080/updateblog.php/blogs/{id}
    parameter: blog id
    method: PUT or PATCH
    header: {Authorization: "Bearer {user id}" }
    request body:
    ```
    {
        "title" : "PHP Explained",
        "category_id" : "2",
        "content": "PHP",
        "published: "1" or "0"
    }
    ```
+ 
* delete blogs
  - This endpoint requires the id of the user for authentication and authorization purposes which will passed in the request header
  - This endpoint requires the id of the blog to be found blogs table 

  - url:localhost:8080/deleteblog.php/blogs/{id}
    parameter: blog id
    method: DELETE
    header: { Authorization: "Bearer {user id}" }

* get users
  - This endpoint requires the id of the user for authentication and authorization purposes which will passed in the request header
  - This will fetch the lists of users if the request user has the permission to view all users
  
  - url: localhost:8080/getusers.php
  - method: GET
  - header: { Authorization : "Bearer {user id}" }

* delete user 
  - This endpoint requires the id of the user for authentication and authorization purposes which will passed in the request header
  - This endpoint requires the id of the blog to be found blogs table 

  - url: localhost:8080/deleteuser.php/blogs/{id}
    parameter: user id
    method : DELETE
    header : { Authorization: "Bearer {user id}"}