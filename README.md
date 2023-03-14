# Laravel Open Structure

```
cp .env{.example,}
```

```bash
docker-compose up -d
```

```
docker-compose exec app sh
```

Try url http://localhost:22040


## Generate files

```
php artisan make:use-case --module=NewModule --cases=new_use_case_A,new_use_case_B,new_use_case_C 
```

## Dump db for testing

* Master data

```shell
php artisan db:seed
```

* User information

```shell
php artisan db:seed --class=UserSeeder
```