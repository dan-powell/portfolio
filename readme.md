






## Installing


```
    php artisan vendor:publish
```



Add 'portfolio' directory as Storage disk





## Upgrading

```
    php artisan vendor:publish --tag=migrations --force
```



## Admin Area

/admin


### Adding users

```
    php artisan portfolio:adduser <Username> <Password> <Role - optional> <User ID to overwrite - optional>
```
