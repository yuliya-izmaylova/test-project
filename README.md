#Solution comments

1. Solution implemented as laravel console command. Please find the code in the following repository: app/Console/Commands.
Please, use
    **php artisan transaction:calculate-commissions input.txt**
or
    **php artisan transaction:calculate-commissions input.txt --addCeiling**
2. Please note, that in real case Services should be moved to appropriate folder, but for the sake of simplicity I've left them in this folder.
3. Unit tests are implemented in the following repository: tests/Unit.
4. RateServiceTest actually do nothing but mocking. But it could be extended to test real API calls.
4. For real case curl call could be replaced with GuzzleHttp client (or wrapped into separate service), 
but for the sake of simplicity I've left it as it is.
5. For real case, I would use .env file for storing API urls and keys, but for the sake of simplicity I've left it in the code.
6. Interfaces were added to add a possibility to switch BIN and currency rates providers.
7. **BIN service is not working properly itself. It has harsh restrictions for calls quantity**

