> ## ModaNisa Case (Symfony 4.3 / Promotion Management)


### Used Components
 - EasyAdmin (Creates administration backends for your Symfony applications.)
 - FosRestBundle (This bundle provides various tools to rapidly develop RESTful API's & applications with Symfony.)
 - FosUserBundle (The FOSUserBundle adds support for a database-backed user system in Symfony2+.)
 - MoneyPhp/Money (PHP library to make working with money safer, easier, and fun!)
 - Carbon (A simple PHP API extension for DateTime.)
 - Symfony Guard TokenAuthenticator
 
### Installation

- composer install
- bin/console doctrine:database:create
- bin/console doctrine:migrations:migrate
- bin/console doctrine:fixtures:load

Default User And Admin Panel
 - Admin Panel Path: /admin
 - Default Username: admin // Email : admin@domain.com
 - Password: 123456

### API
 - Login
 > - Path: "api/login"
 > - Request Example: { "email": "admin@domain.com", "password": "123456" }
 > - Response Examle: { "token":"feac2f9c9f3429ba8f7e3a03f5a1e301" }

 - Sale Price Calculator 
 > - Path: "/api/sale-price-calculate"
 > - Request Example: {
                       "basket": {
                           "items": [
                               {
                                   "productId": 1,
                                   "price": 100,
                                   "salePrice": 0
                               },
                               {
                                   "productId": 2,
                                   "price": 110,
                                   "salePrice": 0
                               },
                               {
                                   "productId": 3,
                                   "price": 79.99,
                                   "salePrice": 0
                               }
                           ],
                           "price": 289.99,
                           "salePrice": 0
                       }
                   }
 
  > - Response Example: {
                            "basket": {
                                "items": [
                                    {
                                        "productId": 1,
                                        "price": 100,
                                        "salePrice": 0
                                    },
                                    {
                                        "productId": 2,
                                        "price": 110,
                                        "salePrice": 0
                                    },
                                    {
                                        "productId": 3,
                                        "price": 79.99,
                                        "salePrice": 0
                                    }
                                ],
                                "price": 289.99,
                                "salePrice": 0
                            }
                        }
   
   
 > PostmanCollection: https://www.getpostman.com/collections/d52514426791d8afae7b
   
